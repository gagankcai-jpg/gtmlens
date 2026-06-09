"""Day 1 seed: GTM Engineering page tree."""
import os, sys, requests
from requests.auth import HTTPBasicAuth

SITE = "https://gtmlens.com"
API = f"{SITE}/wp-json/wp/v2"
USER = "gaganklchawla@gmail.com"
PW = os.environ.get("WP_APP_PW") or "I8Sx GrmZ Ootb zw1u 3lXL 3xy8"
AUTH = HTTPBasicAuth(USER, PW)

def upsert_page(slug, title, content, parent=0, template=""):
    r = requests.get(f"{API}/pages", params={"slug": slug, "status": "publish,draft"}, auth=AUTH)
    r.raise_for_status()
    existing = r.json()
    payload = {
        "title": title, "slug": slug, "content": content,
        "status": "publish", "parent": parent, "template": template,
    }
    if existing:
        pid = existing[0]["id"]
        rr = requests.post(f"{API}/pages/{pid}", auth=AUTH, json=payload)
    else:
        rr = requests.post(f"{API}/pages", auth=AUTH, json=payload)
    rr.raise_for_status()
    p = rr.json()
    print(f"  {p['id']:>5}  {p['slug']:<35} -> {p['link']}")
    return p["id"]

# Parent
parent_id = upsert_page(
    "gtm-engineering",
    "GTM Engineering",
    "",  # template owns the rendering
    template="page-gtm-engineering.php",
)

# Children — placeholders for the long-form pages
PLACEHOLDER = (
    "<p><em>This page is being written. Check back soon — or "
    "<a href=\"/about/contact/\">drop a note</a> if there's a specific question "
    "you want answered first.</em></p>"
)

upsert_page("what-is", "What Is GTM Engineering?", PLACEHOLDER, parent=parent_id)
upsert_page("toolkit", "The GTM Engineer's Toolkit", PLACEHOLDER, parent=parent_id)
upsert_page("learning-path", "Learning Path: Becoming a GTM Engineer", PLACEHOLDER, parent=parent_id)
upsert_page("resources", "GTM Engineering Resources", PLACEHOLDER, parent=parent_id)

# Index pages — templates own the rendering
upsert_page("playbooks", "Playbooks", "", parent=parent_id, template="page-gtm-engineering-playbooks.php")
upsert_page("best-practices", "Best Practices", "", parent=parent_id, template="page-gtm-engineering-best-practices.php")

print("Done.")
