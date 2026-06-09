#!/usr/bin/env python3
"""
GTMLens content seeder. Reads content-batch-1.json and stack-recipes-batch.json,
creates missing taxonomy terms, then seeds vendors, comparisons, insights, stacks
via the WP REST API using an Application Password.
"""
import json
import os
import sys
import time
from pathlib import Path

import requests
from requests.auth import HTTPBasicAuth

ROOT = Path("/Users/gaganchawla/Documents/GitHub/GTM Insights")
SITE = "https://gtmlens.com"
API = f"{SITE}/wp-json/wp/v2"
USER = "gaganklchawla@gmail.com"
PW = os.environ.get("WP_APP_PW") or "I8Sx GrmZ Ootb zw1u 3lXL 3xy8"
AUTH = HTTPBasicAuth(USER, PW)


def slog(msg):
    print(msg, flush=True)


def get_or_create_term(taxonomy_route, name, slug=None):
    """Return term ID. Looks up by slug first; creates if missing."""
    s = slug or name.lower().replace(" ", "-").replace("&", "and").replace("/", "-")
    s = "".join(c for c in s if c.isalnum() or c == "-")
    r = requests.get(f"{API}/{taxonomy_route}", params={"slug": s}, auth=AUTH)
    r.raise_for_status()
    existing = r.json()
    if existing:
        return existing[0]["id"]
    r = requests.post(f"{API}/{taxonomy_route}", auth=AUTH, json={"name": name, "slug": s})
    if r.status_code in (200, 201):
        return r.json()["id"]
    # Sometimes returns 400 term_exists — re-fetch
    if "term_exists" in r.text:
        r2 = requests.get(f"{API}/{taxonomy_route}", params={"slug": s}, auth=AUTH)
        if r2.json():
            return r2.json()[0]["id"]
    slog(f"  ! term create failed for {name}: {r.status_code} {r.text[:200]}")
    return None


def find_post_by_slug(post_type_route, slug):
    r = requests.get(f"{API}/{post_type_route}", params={"slug": slug}, auth=AUTH)
    if r.status_code == 200 and r.json():
        return r.json()[0]
    return None


def create_or_update(post_type_route, slug, payload):
    """Create the post; if already exists by slug, update it."""
    existing = find_post_by_slug(post_type_route, slug)
    if existing:
        slog(f"  → updating existing {post_type_route}/{slug} (id={existing['id']})")
        r = requests.post(f"{API}/{post_type_route}/{existing['id']}", auth=AUTH, json=payload)
    else:
        r = requests.post(f"{API}/{post_type_route}", auth=AUTH, json=payload)
    if r.status_code not in (200, 201):
        slog(f"  ! {post_type_route}/{slug} failed: {r.status_code} {r.text[:300]}")
        return None
    return r.json()


def seed_vendors(content):
    slog("\n=== Vendors ===")
    vendor_id_by_slug = {}

    # Pre-fetch existing Clay so comparisons can reference it
    clay = find_post_by_slug("vendor", "clay")
    if clay:
        vendor_id_by_slug["clay"] = clay["id"]

    for v in content["vendors"]:
        slog(f"\n• {v['title']} ({v['slug']})")
        # Resolve vendor_category term IDs
        cat_ids = []
        for cat_slug in v.get("vendor_category_slugs", []):
            tid = get_or_create_term("vendor_category", cat_slug.replace("-", " ").title(), slug=cat_slug)
            if tid:
                cat_ids.append(tid)

        # Resolve capabilities + integrations as flat tax terms
        cap_ids = [get_or_create_term("capabilities", c) for c in v.get("capabilities", [])]
        cap_ids = [x for x in cap_ids if x]
        int_ids = [get_or_create_term("integrations", c) for c in v.get("integrations", [])]
        int_ids = [x for x in int_ids if x]

        payload = {
            "title": v["title"],
            "slug": v["slug"],
            "status": "publish",
            "excerpt": v.get("excerpt", ""),
            "vendor_category": cat_ids,
            "capabilities": cap_ids,
            "integrations": int_ids,
            "acf": v.get("acf", {}),
        }
        result = create_or_update("vendor", v["slug"], payload)
        if result:
            vendor_id_by_slug[v["slug"]] = result["id"]
            slog(f"  ✓ id={result['id']} link={result.get('link')}")

    return vendor_id_by_slug


INSTANTLY_STUB = {
    "title": "Instantly",
    "slug": "instantly",
    "status": "publish",
    "excerpt": "Instantly is a cold email infrastructure platform competing with Smartlead in the AI-native outbound category. Full vendor profile pending.",
    "acf": {
        "vendor_url": "https://instantly.ai",
        "hq": "Austin, TX",
        "founded": "2020",
        "funding_stage": "Bootstrapped",
        "pricing_tier": "$",
        "entry_price": "$37/mo",
        "pricing_page_url": "https://instantly.ai/pricing",
        "target_segment": "SMB and mid-market cold email senders.",
        "last_updated": "20260430",
        "reviewer": "Gagan Chawla",
    },
}


def ensure_instantly_stub(vendor_id_by_slug):
    if "instantly" in vendor_id_by_slug:
        return
    existing = find_post_by_slug("vendor", "instantly")
    if existing:
        vendor_id_by_slug["instantly"] = existing["id"]
        return
    payload = dict(INSTANTLY_STUB)
    cat_id = get_or_create_term("vendor_category", "Outbound", slug="outbound")
    if cat_id:
        payload["vendor_category"] = [cat_id]
    result = create_or_update("vendor", "instantly", payload)
    if result:
        vendor_id_by_slug["instantly"] = result["id"]
        slog(f"  ✓ Instantly stub id={result['id']}")


def seed_comparisons(content, vendor_id_by_slug):
    slog("\n=== Comparisons ===")
    ensure_instantly_stub(vendor_id_by_slug)
    for c in content["comparisons"]:
        slog(f"\n• {c['title']} ({c['slug']})")
        a_id = vendor_id_by_slug.get(c["vendor_a_slug"])
        b_id = vendor_id_by_slug.get(c["vendor_b_slug"])
        if not (a_id and b_id):
            slog(f"  ! missing vendor IDs (a={a_id}, b={b_id}); skipping")
            continue

        acf = dict(c.get("acf", {}))
        acf["vendor_a"] = a_id
        acf["vendor_b"] = b_id

        payload = {
            "title": c["title"],
            "slug": c["slug"],
            "status": "publish",
            "excerpt": c.get("excerpt", ""),
            "acf": acf,
        }
        result = create_or_update("comparison", c["slug"], payload)
        if result:
            slog(f"  ✓ id={result['id']} link={result.get('link')}")


def seed_insights(content):
    slog("\n=== Insights ===")
    for i in content["insights"]:
        slog(f"\n• {i['title'][:70]}... ({i['slug']})")
        # Resolve post categories
        cat_ids = []
        for cat_slug in i.get("categories", []):
            tid = get_or_create_term("categories", cat_slug.replace("-", " ").title(), slug=cat_slug)
            if tid:
                cat_ids.append(tid)

        payload = {
            "title": i["title"],
            "slug": i["slug"],
            "status": "publish",
            "excerpt": i.get("excerpt", ""),
            "content": i.get("content", ""),
            "categories": cat_ids,
            "acf": i.get("acf", {}),
        }
        result = create_or_update("posts", i["slug"], payload)
        if result:
            slog(f"  ✓ id={result['id']} link={result.get('link')}")


CATEGORY_BY_TOOL = {
    "apollo": "data-enrichment", "clay": "data-enrichment", "zoominfo": "data-enrichment",
    "smartlead": "outbound", "instantly": "outbound", "outreach": "outbound", "salesloft": "outbound",
    "rb2b": "intent-signal", "warmly": "intent-signal", "6sense": "intent-signal",
    "hubspot": "crm", "salesforce": "crm", "attio": "crm",
    "11x": "ai-sdr", "artisan": "ai-sdr",
    "n8n": "orchestration",
    "gong": "revenue-intelligence",
}


def ensure_vendor_stub(vendor_id_by_slug, tool_slug):
    """Create a minimal stub vendor if it doesn't exist; return its ID."""
    if tool_slug in vendor_id_by_slug:
        return vendor_id_by_slug[tool_slug]
    existing = find_post_by_slug("vendor", tool_slug)
    if existing:
        vendor_id_by_slug[tool_slug] = existing["id"]
        return existing["id"]
    # Read entry price from stack-rules.json if available
    name = tool_slug.replace("-", " ").title().replace(" Ai", "").replace(" Io", "")
    name = {"11x": "11x", "6sense": "6sense", "rb2b": "RB2B", "n8n": "n8n", "zoominfo": "ZoomInfo"}.get(tool_slug, name)
    cat_slug = CATEGORY_BY_TOOL.get(tool_slug, "data-enrichment")
    cat_id = get_or_create_term("vendor_category", cat_slug.replace("-", " ").title(), slug=cat_slug)
    payload = {
        "title": name,
        "slug": tool_slug,
        "status": "publish",
        "excerpt": f"{name} — full vendor profile coming soon. Stub entry created to support stack recipe linkage.",
        "vendor_category": [cat_id] if cat_id else [],
        "acf": {
            "last_updated": "20260430",
            "reviewer": "Gagan Chawla",
        },
    }
    result = create_or_update("vendor", tool_slug, payload)
    if result:
        vendor_id_by_slug[tool_slug] = result["id"]
        slog(f"  ✓ stub vendor {tool_slug} id={result['id']}")
        return result["id"]
    return None


def seed_stacks(stacks_data, vendor_id_by_slug):
    slog("\n=== Stack Recipes ===")
    # First pass: create all stacks (without migration_path)
    stack_id_by_slug = {}
    for s in stacks_data["stacks"]:
        slog(f"\n• {s['title']} ({s['slug']})")
        # Resolve tool slugs to vendor IDs (creating stubs for missing ones)
        tool_ids = []
        for tool_slug in s["acf"].get("tool_slugs", []):
            vid = ensure_vendor_stub(vendor_id_by_slug, tool_slug)
            if vid:
                tool_ids.append(vid)
            else:
                slog(f"  ! tool {tool_slug} not found and stub failed")

        # Map repeater "content" → "text" subfield
        pct = []
        for entry in s["acf"].get("pros_cons_tradeoffs", []):
            pct.append({
                "type": entry.get("type"),
                "text": entry.get("text") or entry.get("content", ""),
            })

        acf = {
            "budget_tier": s["acf"]["budget_tier"],
            "icp_use_case": s["acf"].get("icp_use_case", ""),
            "tools": tool_ids,
            "monthly_cost_estimate": s["acf"].get("monthly_cost_estimate", 0),
            "pros_cons_tradeoffs": pct,
        }

        payload = {
            "title": s["title"],
            "slug": s["slug"],
            "status": "publish",
            "excerpt": s.get("excerpt", ""),
            "content": s.get("content", ""),
            "acf": acf,
        }
        result = create_or_update("stack", s["slug"], payload)
        if result:
            stack_id_by_slug[s["slug"]] = result["id"]
            slog(f"  ✓ id={result['id']} link={result.get('link')}")

    # Second pass: link migration paths
    slog("\n--- linking migration paths ---")
    for s in stacks_data["stacks"]:
        next_slug = s["acf"].get("migration_path_slug")
        if not next_slug:
            continue
        this_id = stack_id_by_slug.get(s["slug"])
        next_id = stack_id_by_slug.get(next_slug)
        if not (this_id and next_id):
            continue
        r = requests.post(f"{API}/stack/{this_id}", auth=AUTH, json={"acf": {"migration_path": next_id}})
        if r.status_code in (200, 201):
            slog(f"  ✓ {s['slug']} → {next_slug}")
        else:
            slog(f"  ! {s['slug']} migration link failed: {r.status_code}")


def main():
    content = json.load(open(ROOT / "content-batch-1.json"))
    stacks = json.load(open(ROOT / "stack-recipes-batch.json"))

    vendor_id_by_slug = seed_vendors(content)
    seed_comparisons(content, vendor_id_by_slug)
    seed_insights(content)
    seed_stacks(stacks, vendor_id_by_slug)

    slog("\n=== Done ===")


if __name__ == "__main__":
    main()
