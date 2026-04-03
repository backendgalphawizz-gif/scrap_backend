import os
import re
import time
import json
import pymysql
from datetime import datetime
from dotenv import load_dotenv
from playwright.sync_api import sync_playwright
from playwright_stealth import Stealth
from pyvirtualdisplay import Display

load_dotenv()

# --- DATABASE CONFIGURATION ---
DB_CONFIG = {
    "host": os.getenv("DB_HOST"),
    "user": os.getenv("DB_USER"),
    "password": os.getenv("DB_PASSWORD"),
    "database": os.getenv("DB_NAME"),
    "cursorclass": pymysql.cursors.DictCursor
}
# ------------------------------

FB_EMAIL = os.getenv("FB_EMAIL")
FB_PASSWORD = os.getenv("FB_PASSWORD")
FB_PROFILE = os.getenv("FB_PROFILE")

SERVER_MODE = (os.getenv("SERVER_MODE") or "false").lower() == "true"
FB_HEADLESS = (os.getenv("FB_HEADLESS") or "false").lower() == "true"

def save_fb_to_db(data):
    """Saves the extracted Facebook post to the MySQL database."""
    try:
        conn = pymysql.connect(**DB_CONFIG)
        with conn.cursor() as cursor:
            sql = """
            INSERT INTO facebook_posts_test
            (post_url, caption, hashtags, mentions, scraped_at)
            VALUES (%s, %s, %s, %s, %s)
            ON DUPLICATE KEY UPDATE scraped_at=VALUES(scraped_at)
            """
            cursor.execute(sql, (
                data["url"],
                data["caption"],
                ",".join(data["hashtags"]),
                ",".join(data["mentions"]),
                datetime.now()
            ))
        conn.commit()
        conn.close()
    except Exception as e:
        print(f"⚠ Database error: {e}")

def ensure_logged_in(page, browser_context):
    print("Checking session...")
    page.goto("https://www.facebook.com/", wait_until="domcontentloaded")
    page.wait_for_timeout(3000)
    
    if page.locator("input[name='email']").is_visible():
        print("Not logged in (fb_session missing or expired). Typing credentials...")
        page.fill("input[name='email']", FB_EMAIL)
        page.fill("input[name='pass']", FB_PASSWORD)
        page.keyboard.press("Enter")
        print("Waiting for login (20s)...")
        page.wait_for_timeout(20000)
    else:
        print("Session active! Successfully logged in.")

def scrape_fb_profile():
    if SERVER_MODE:
        print("Starting virtual display for server mode...")
        display = Display(visible=0, size=(1920, 1080))
        display.start()

    with sync_playwright() as p:
        
        # Add User-Agent to ensure Facebook serves the Desktop site to the server
        chromium_args = [
            "--window-size=1920,1080", 
            "--disable-notifications"
        ]
        
        if SERVER_MODE:
            chromium_args.extend([
                "--no-sandbox",
                "--disable-dev-shm-usage",
            ])

        proxy_config = None
        if os.getenv("PROXY_HOST"):
            proxy_config = {
                "server": f"http://{os.getenv('PROXY_HOST')}:{os.getenv('PROXY_PORT')}",
                "username": os.getenv("PROXY_USER"),
                "password": os.getenv("PROXY_PASS")
            }
            print(f"Using proxy: {proxy_config['server']}")

        # Using persistent context to load the authenticated fb_session folder
        browser = p.chromium.launch_persistent_context(
            user_data_dir="./fb_session",
            headless=FB_HEADLESS,
            args=chromium_args,
            viewport={"width": 1920, "height": 1080},
            user_agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
            proxy=proxy_config  
        )

        page = browser.pages[0]
        
        print("Blocking images and media...")
        page.route(
            "**/*", 
            # Removed 'font' from the block list so Facebook's UI renders properly
            lambda route: route.abort() if route.request.resource_type in ["image", "media"] else route.continue_()
        )

        Stealth().apply_stealth_sync(page)
        
        # 1. Log in using the fb_session folder
        ensure_logged_in(page, browser)

        # 2. Navigate to the actual profile page
        print(f"Opening Profile: {FB_PROFILE}")
        page.goto(FB_PROFILE, wait_until="domcontentloaded")
        print("Waiting for posts to load (10s)...")
        page.wait_for_timeout(10000)
        
        # Take a debug screenshot just in case it hits 0 posts again
        page.screenshot(path="debug_profile.png")
        print("📸 Saved debug screenshot to debug_profile.png")

        # 3. Kill Popups and Cookie Banners
        print("Scanning for and dismissing blocking popups...")
        page.evaluate("""
            () => {
                const buttons = Array.from(document.querySelectorAll('div[role="button"], span'));
                for (let btn of buttons) {
                    let txt = (btn.innerText || "").toLowerCase();
                    if (txt.includes('allow all cookies') || txt.includes('decline optional') || txt.includes('allow essential')) {
                        try { btn.click(); } catch(e) {}
                    }
                }
                const closeBtns = document.querySelectorAll('div[aria-label="Close"], div[aria-label="Cerrar"]');
                closeBtns.forEach(btn => { try { btn.click(); } catch(e) {} });
            }
        """)
        page.wait_for_timeout(2000)

        all_posts = []
        seen_keys = set()

        for scroll in range(10):
            print(f"Scraping Scroll {scroll + 1}...")
            
            # Safely click "See more"
            page.evaluate("""
                () => {
                    const buttons = Array.from(document.querySelectorAll('div[role="button"]'));
                    buttons.forEach(btn => {
                        let text = btn.innerText.trim().toLowerCase();
                        if (text === 'see more' || text === 'see translation') {
                            try { btn.click(); } catch(e) {}
                        }
                    });
                }
            """)
            page.wait_for_timeout(1500) 

            # Extract Data
            posts_data = page.evaluate("""
                () => {
                    const data = [];
                    const articles = document.querySelectorAll('div[role="article"], div[aria-posinset]');
                    
                    articles.forEach(article => {
                        const links = Array.from(article.querySelectorAll('a'));
                        let postUrl = "N/A"; 
                        
                        for (let a of links) {
                            let href = a.href || "";
                            if ((href.includes('/posts/') || href.includes('/photos/') || href.includes('/video/') || href.includes('fbid=')) 
                                && !href.includes('/hashtag/') 
                                && !href.includes('/user/')) {
                                postUrl = href.split('__cft__')[0].split('&set=')[0]; 
                                if (postUrl.endsWith('?')) postUrl = postUrl.slice(0, -1);
                                break; 
                            }
                        }

                        let content = "";
                        const messageNode = article.querySelector('[data-ad-preview="message"]');
                        
                        if (messageNode) {
                            content = messageNode.innerText;
                        } else {
                            const autoNodes = Array.from(article.querySelectorAll('[dir="auto"]'));
                            let maxText = "";
                            for (let node of autoNodes) {
                                let txt = node.innerText.trim();
                                let lowerTxt = txt.toLowerCase();
                                const stopWords = ['like', 'comment', 'share', 'send', 'write a public comment', 'just now'];
                                if (stopWords.includes(lowerTxt)) continue;
                                if (txt.length > maxText.length) maxText = txt;
                            }
                            content = maxText;
                        }

                        content = content.trim();
                        if (content.length > 15) {
                            data.push({ text: content, url: postUrl });
                        }
                    });
                    return data;
                }
            """)

            for p_data in posts_data:
                post_url = p_data['url']
                raw_text = p_data['text']
                
                dedup_key = post_url if post_url != "N/A" else raw_text[:30]
                if dedup_key in seen_keys:
                    continue
                seen_keys.add(dedup_key)

                # --- THE MENTION FILTER & REGEX EXTRACTOR ---
                hashtags = list(set(re.findall(r"#\w+", raw_text)))
                mentions = list(set(re.findall(r"@\w+", raw_text)))
                
                # Catch the hidden Rexarix tags on Facebook
                lower_text = raw_text.lower()
                if "rexarix" in lower_text:
                    mentions.append("@Rexarix")
                
                mentions = list(set(mentions)) # Remove duplicates
                # --------------------------------------------
                
                safe_db_url = post_url
                if safe_db_url == "N/A":
                    safe_db_url = f"N/A_fallback_{abs(hash(dedup_key))}"
                
                post_entry = {
                    "url": safe_db_url,
                    "caption": raw_text.replace('\n', ' '), 
                    "hashtags": hashtags,
                    "mentions": mentions
                }
                all_posts.append(post_entry)

                print(f"\n--- Post {len(all_posts)} ---")
                print(f"URL: {post_entry['url']}")
                print(f"Caption: {post_entry['caption'][:150]}...") 
                print(f"Hashtags: {hashtags}")
                print(f"Mentions: {mentions}")
                
                save_fb_to_db(post_entry)
                print(" Saved to Database!")

            page.mouse.wheel(0, 2000)
            page.wait_for_timeout(4000)

        with open("fb_results.json", "w", encoding="utf-8") as f:
            json.dump(all_posts, f, indent=4, ensure_ascii=False)

        print(f"\nFinished! Scraped {len(all_posts)} posts.")
        browser.close()

if __name__ == "__main__":
    scrape_fb_profile()