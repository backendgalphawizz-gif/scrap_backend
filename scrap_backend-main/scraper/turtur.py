import os
import pymysql
from datetime import datetime
from dotenv import load_dotenv
from apify_client import ApifyClient

load_dotenv()

DB_CONFIG = {
    "host": os.getenv("DB_HOST"),
    "user": os.getenv("DB_USER"),
    "password": os.getenv("DB_PASSWORD"),
    "database": os.getenv("DB_NAME"),
    "cursorclass": pymysql.cursors.DictCursor
}

APIFY_TOKEN = os.getenv("APIFY_TOKEN")

TARGET_PROFILE = os.getenv("TARGET_PROFILE", "rexarix_official") 

client = ApifyClient(APIFY_TOKEN)

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

def scrape_tagged_profile(profile_username, results_limit=50):
    print(f"🔍 Scraping the 'Tagged' section for profile: @{profile_username}")
    print(f"⏳ Waiting for Apify... (Fetching up to {results_limit} recent tagged posts)")
    
    run_input = {
        "username": [profile_username],
        "resultsLimit": results_limit
    }

    try:
        run = client.actor("apify/instagram-tagged-scraper").call(run_input=run_input)
        
        dataset_items = list(client.dataset(run["defaultDatasetId"]).iterate_items())
        
        if not dataset_items:
            print(f"❌ No tagged posts found for @{profile_username}.")
            return False
            
        print(f"✅ Found {len(dataset_items)} tagged posts! Saving to database...")
        
        for item in dataset_items:
            
            post_url = item.get("url", "")
            post_id = item.get("id", "")
            
            username = item.get("ownerUsername") or item.get("author", {}).get("userName", "Unknown")
            caption = item.get("caption", "")
            
            raw_hashtags = item.get("hashtags", [])
            raw_mentions = item.get("mentions", [])
            
            hashtags = ",".join(raw_hashtags) if isinstance(raw_hashtags, list) else str(raw_hashtags)
            mentions = ",".join(raw_mentions) if isinstance(raw_mentions, list) else str(raw_mentions)

            data = {
                "post_id": post_id,
                "url": post_url,
                "username": username,
                "caption": caption,
                "hashtags": hashtags,
                "mentions": mentions
            }
            
            save_to_db(data)
            print(f"  -> 💾 Saved Tagged Post: {post_url} (by @{username})")

        print("🎉 All tagged posts processed successfully!")
        return True

    except Exception as e:
        print(f"⚠️ Apify Request failed: {e}")
        return False

if __name__ == "__main__":
    scrape_tagged_profile(TARGET_PROFILE, results_limit=50)