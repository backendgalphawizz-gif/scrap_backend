import re
import time
import os
import pymysql
import random
from datetime import datetime
from dotenv import load_dotenv
from playwright.sync_api import sync_playwright
from playwright_stealth import Stealth
from pyvirtualdisplay import Display

load_dotenv()

DB_CONFIG = {
    "host": os.getenv("DB_HOST"),
    "user": os.getenv("DB_USER"),
    "password": os.getenv("DB_PASSWORD"),
    "database": os.getenv("DB_NAME"),
    "cursorclass": pymysql.cursors.DictCursor
}

IG_EMAIL = os.getenv("IG_EMAIL")
IG_PASSWORD = os.getenv("IG_PASSWORD")
TARGET_PROFILE = os.getenv("TARGET_PROFILE") or "rexarix_official"
IG_SESSION_DIR = os.getenv("IG_SESSION_DIR") or "ig_session"
IG_HEADLESS = (os.getenv("IG_HEADLESS") or "false").lower() == "true"
SERVER_MODE = (os.getenv("SERVER_MODE") or "false").lower() == "true"
IG_LOGIN_ONLY = (os.getenv("IG_LOGIN_ONLY") or "false").lower() == "true"
IG_USE_STEALTH = (os.getenv("IG_USE_STEALTH") or "false").lower() == "true"


def _wait_for_manual_challenge_resolution(page, timeout_seconds=300):
    """Allow manual challenge completion and wait until Instagram redirects."""
    print("Instagram challenge/checkpoint detected.")
    print(
        f"Complete verification in the browser window. Waiting up to {timeout_seconds} seconds..."
    )

    deadline = time.time() + timeout_seconds
    while time.time() < deadline:
        current = page.url.lower()
        if (
            "challenge" not in current
            and "checkpoint" not in current
            and "two_factor" not in current
            and "login" not in current
        ):
            print("Challenge resolved successfully.")
            return
        time.sleep(2)

    raise Exception("Challenge was not resolved in time")


def extract_hashtags(text):
    return ",".join(re.findall(r"#\w+", text.lower()))


def extract_mentions(text):
    return ",".join(re.findall(r"@\w+", text.lower()))


def save_to_db(data):

    conn = pymysql.connect(**DB_CONFIG)

    try:
        with conn.cursor() as cursor:

            sql = """
            INSERT INTO tagged_posts_test
            (instagram_post_id, post_url, username, caption, hashtags, mentions, scraped_at)
            VALUES (%s,%s,%s,%s,%s,%s,%s)
            ON DUPLICATE KEY UPDATE scraped_at=VALUES(scraped_at)
            """

            cursor.execute(sql, (
                data["post_id"],
                data["url"],
                data["username"],
                data["caption"],
                data["hashtags"],
                data["mentions"],
                datetime.now()
            ))

        conn.commit()

    finally:
        conn.close()



def ensure_logged_in(page):

    print(" Checking login session...")

    page.goto("https://www.instagram.com/accounts/login/")
    page.wait_for_load_state("domcontentloaded")
    time.sleep(5)

    current_url = page.url.lower()

    if "login" not in current_url:
        print(" Already logged in")
        return

    print("⚠ Session not found → handling login flow")

    try:
        if page.locator("button:has-text('Continue')").count() > 0:

            print("Found Continue screen")

            page.locator("button:has-text('Continue')").click()
            time.sleep(3)

            if page.locator("input[type='password']").count() > 0:

                print("Entering password")

                password = page.locator("input[type='password']").first
                password.fill(IG_PASSWORD)

                page.locator("button:has-text('Log in')").click()

        else:

            print("Using standard login form")

            page.wait_for_selector("input[type='password']", timeout=30000)

            username = page.locator("input[name='username'], input[type='text']").first
            password = page.locator("input[type='password']").first

            username.click()
            username.type(IG_EMAIL, delay=120)

            password.click()
            password.type(IG_PASSWORD, delay=120)

            password.press("Enter")

            print("Submitted login form")

            time.sleep(8)

    except Exception as e:
        print("Login interaction error:", e)

    time.sleep(8)

    try:
        if page.locator("button:has-text('Not now')").count() > 0:
            page.locator("button:has-text('Not now')").click()
    except:
        pass

    try:
        if page.locator("button:has-text('Not Now')").count() > 0:
            page.locator("button:has-text('Not Now')").click()
    except:
        pass

    current_url = page.url.lower()

    if "challenge" in current_url or "checkpoint" in current_url or "two_factor" in current_url:
        _wait_for_manual_challenge_resolution(page)
        return

    if "login" in current_url:
        raise Exception("Login failed or Instagram challenge triggered")

    print(" Login successful")



# def scrape_tagged_posts():

#     with sync_playwright() as p:

#         chromium_args = ["--start-maximized"]

#         if SERVER_MODE:
#             chromium_args.extend([
#                 "--no-sandbox",
#                 "--disable-dev-shm-usage",
#             ])

#         browser = p.chromium.launch_persistent_context(
#             user_data_dir=os.path.abspath(IG_SESSION_DIR),
#             headless=IG_HEADLESS,
#             slow_mo=80,
#             args=chromium_args,
#             viewport=None
#         )

#         page = browser.new_page()

#         if IG_USE_STEALTH:
#             stealth = Stealth()
#             stealth.apply_stealth_sync(page)

#         ensure_logged_in(page)

def scrape_tagged_posts():

    # --- Start the virtual display if on the server ---
    if SERVER_MODE:
        print("Starting virtual display for server mode...")
        display = Display(visible=0, size=(1920, 1080))
        display.start()
    # -------------------------------------------------------

    with sync_playwright() as p:

        chromium_args = ["--window-size=1920,1080"] 

        if SERVER_MODE:
            chromium_args.extend([
                "--no-sandbox",
                "--disable-dev-shm-usage",
            ])

        # ---> CHANGE 1: Setup Proxy Configuration <---
        proxy_config = None
        if os.getenv("PROXY_HOST"):
            proxy_config = {
                "server": f"http://{os.getenv('PROXY_HOST')}:{os.getenv('PROXY_PORT')}",
                "username": os.getenv("PROXY_USER"),
                "password": os.getenv("PROXY_PASS")
            }
            print(f"Using proxy: {proxy_config['server']}")
        
        browser = p.chromium.launch_persistent_context(
            user_data_dir=os.path.abspath(IG_SESSION_DIR),
            headless=IG_HEADLESS, 
            slow_mo=80,
            args=chromium_args,
            viewport={"width": 1920, "height": 1080},
            proxy=proxy_config
        )

        # ---> THE BYPASS: Inject the session cookie <---
        if os.getenv("IG_SESSION_ID"):
            print("Injecting session cookie to bypass login...")
            browser.add_cookies([{
                "name": "sessionid",
                "value": os.getenv("IG_SESSION_ID"),
                "domain": ".instagram.com",
                "path": "/",
                "secure": True,
                "httpOnly": True,
            }])
        # -----------------------------------------------

        page = browser.new_page()

        print("Blocking images and media...")
        page.route(
            "**/*", 
            lambda route: route.abort() if route.request.resource_type in ["image", "media", "font"] else route.continue_()
        )

        if IG_USE_STEALTH:
            stealth = Stealth()
            stealth.apply_stealth_sync(page)

        ensure_logged_in(page)

        if IG_LOGIN_ONLY:
            print("IG_LOGIN_ONLY=true -> login bootstrap complete. Exiting without scraping.")
            browser.close()
            return

        profile_url = f"https://www.instagram.com/{TARGET_PROFILE}/"

        print("Opening profile:", profile_url)

        page.goto(profile_url)
        page.wait_for_load_state("domcontentloaded")
        time.sleep(5)

        print("Opening tagged section")

        page.locator("a[href*='/tagged/']").click()
        page.wait_for_load_state("domcontentloaded")
        time.sleep(5)

        print("Scrolling tagged posts...")

        for _ in range(20):
            page.mouse.wheel(0, 8000)
            time.sleep(2)

        posts = page.locator("a[href*='/p/']")
        count = posts.count()

        print("Tagged posts found:", count)

        for i in range(count):

            print("Opening tagged post", i + 1)

            posts.nth(i).click()

            page.wait_for_selector("div[role='dialog'] article", timeout=20000)
            time.sleep(2)

            
            try:

                post_url = page.url
                post_id = post_url.split("/p/")[1].split("/")[0]

                comment_row = page.locator("div[role='dialog'] article ul li").first

                row_text = comment_row.inner_text().strip()

                username = row_text.split("\n")[0].strip()

                hashtags_list = re.findall(r"#\w+", row_text)
                hashtags = ",".join(hashtags_list)

                mentions_list = re.findall(r"@\w+", row_text)
                mentions = ",".join(mentions_list)

                caption = "\n".join(row_text.split("\n")[1:])

                caption = re.sub(r"#\w+", "", caption)
                caption = re.sub(r"@\w+", "", caption)

                caption = re.sub(r"\b\d+[dhm]\b", "", caption)

                caption = caption.replace("Follow us on", "")

                caption = re.sub(r"\s+", " ", caption).strip()

                print("Username:", username)
                print("Caption:", caption)
                print("Hashtags:", hashtags)
                print("Mentions:", mentions)

                data = {
                    "post_id": post_id,
                    "url": post_url,
                    "username": username,
                    "caption": caption,
                    "hashtags": hashtags,
                    "mentions": mentions
                    
                }

                save_to_db(data)

                print(" Saved:", post_id)

            except Exception as e:
                print("⚠ Error:", e)
            

            page.keyboard.press("Escape")
            # time.sleep(2)
            time.sleep(3 + random.random())

        browser.close()
        
        
if __name__ == "__main__":
    scrape_tagged_posts()
    