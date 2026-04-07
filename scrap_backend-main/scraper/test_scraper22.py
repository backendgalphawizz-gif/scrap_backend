import re
import time
import os
import pymysql
import random
from datetime import datetime
from dotenv import load_dotenv
from playwright.sync_api import sync_playwright, TimeoutError as PlaywrightTimeoutError
from playwright_stealth import Stealth

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


def _print_page_debug(page, label):
    try:
        title = page.title()
    except Exception:
        title = "<unavailable>"

    try:
        body_text = page.locator("body").inner_text(timeout=5000)
        body_preview = re.sub(r"\s+", " ", body_text).strip()[:600]
    except Exception:
        body_preview = "<unavailable>"

    try:
        html_snippet = page.content()[:2000]
    except Exception:
        html_snippet = "<unavailable>"

    print(f"[{label}] url: {page.url}")
    print(f"[{label}] title: {title}")
    print(f"[{label}] body preview: {body_preview}")
    print(f"[{label}] html snippet: {html_snippet}")

    # Save screenshot and full HTML for inspection
    try:
        screenshot_path = "/tmp/ig_screenshot.png"
        page.screenshot(path=screenshot_path, full_page=True)
        print(f"[{label}] screenshot saved to {screenshot_path}")
    except Exception as exc:
        print(f"[{label}] screenshot failed: {exc}")

    try:
        html_path = "/tmp/ig_debug.html"
        with open(html_path, "w", encoding="utf-8") as fh:
            fh.write(page.content())
        print(f"[{label}] HTML saved to {html_path}")
    except Exception as exc:
        print(f"[{label}] HTML dump failed: {exc}")


def _click_first_visible(page, selectors):
    for selector in selectors:
        locator = page.locator(selector).first
        try:
            if locator.count() > 0 and locator.is_visible(timeout=1500):
                locator.click()
                return True
        except Exception:
            continue
    return False


def _dismiss_login_overlays(page):
    overlay_selectors = [
        "button:has-text('Allow all cookies')",
        "button:has-text('Accept all')",
        "button:has-text('Only allow essential cookies')",
        "button:has-text('Decline optional cookies')",
        "button:has-text('Not now')",
        "button:has-text('Not Now')",
    ]

    while _click_first_visible(page, overlay_selectors):
        time.sleep(1)


def _wait_for_login_form(page, timeout=30000):
    username_selectors = [
        "input[name='username']",
        "input[aria-label='Phone number, username, or email']",
        "input[type='text']",
    ]
    password_selectors = [
        "input[name='password']",
        "input[type='password']",
    ]

    deadline = time.time() + (timeout / 1000)
    last_error = None

    while time.time() < deadline:
        _dismiss_login_overlays(page)

        for username_selector in username_selectors:
            username = page.locator(username_selector).first
            try:
                if username.count() == 0 or not username.is_visible(timeout=1000):
                    continue
            except Exception as exc:
                last_error = exc
                continue

            for password_selector in password_selectors:
                password = page.locator(password_selector).first
                try:
                    if password.count() > 0 and password.is_visible(timeout=1000):
                        return username, password
                except Exception as exc:
                    last_error = exc

        current_url = page.url.lower()
        if any(token in current_url for token in ["challenge", "checkpoint", "two_factor"]):
            break

        time.sleep(1)

    _print_page_debug(page, "login-form-timeout")
    raise PlaywrightTimeoutError(
        f"Instagram login form not available within {timeout}ms. Last error: {last_error}"
    )


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

    page.goto("https://www.instagram.com/accounts/login/",
               wait_until="networkidle", timeout=60000)
    time.sleep(3)
    _dismiss_login_overlays(page)

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

            username, password = _wait_for_login_form(page, timeout=30000)

            username.click()
            username.fill("")
            username.type(IG_EMAIL, delay=120)

            password.click()
            password.fill("")
            password.type(IG_PASSWORD, delay=120)

            password.press("Enter")

            print("Submitted login form")

            time.sleep(8)

    except Exception as e:
        print("Login interaction error:", e)
        _print_page_debug(page, "login-interaction-error")

    time.sleep(8)
    _dismiss_login_overlays(page)

    current_url = page.url.lower()

    if "challenge" in current_url or "checkpoint" in current_url or "two_factor" in current_url:
        _wait_for_manual_challenge_resolution(page)
        return

    if "login" in current_url:
        raise Exception("Login failed or Instagram challenge triggered")

    print(" Login successful")



def scrape_tagged_posts():

    with sync_playwright() as p:

        # Core flags required for Chromium to render properly on Linux servers.
        # --disable-gpu and --no-sandbox are mandatory on headless/containerised hosts;
        # omitting them causes blank pages even when the process starts successfully.
        chromium_args = [
            "--no-sandbox",
            "--disable-setuid-sandbox",
            "--disable-gpu",
            "--disable-dev-shm-usage",
            "--disable-software-rasterizer",
            "--disable-extensions",
            "--disable-background-networking",
            "--no-first-run",
            "--no-default-browser-check",
        ]

        # Only add --start-maximized on non-headless (it's ignored in headless anyway)
        if not IG_HEADLESS:
            chromium_args.append("--start-maximized")

        browser = p.chromium.launch_persistent_context(
            user_data_dir=os.path.abspath(IG_SESSION_DIR),
            headless=IG_HEADLESS,
            slow_mo=80,
            args=chromium_args,
            viewport={"width": 1280, "height": 900} if IG_HEADLESS else None
        )

        page = browser.new_page()

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
    