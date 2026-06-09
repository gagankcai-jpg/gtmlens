#!/usr/bin/env python3
"""Sprint 2 seeder — reuses seed.py functions to ingest batch-2 files."""
import json
from pathlib import Path
from seed import seed_vendors, seed_comparisons, seed_insights, slog, find_post_by_slug

ROOT = Path("/Users/gaganchawla/Documents/GitHub/GTM Insights")


def main():
    vendors = json.load(open(ROOT / "content-batch-2-vendors.json"))
    comparisons = json.load(open(ROOT / "content-batch-2-comparisons.json"))
    insights = json.load(open(ROOT / "content-batch-2-insights.json"))

    # Seed vendors first (promotes existing stubs to full profiles)
    vendor_id_by_slug = seed_vendors(vendors)

    # Pre-populate map with all already-existing vendors that comparisons may reference
    for slug in ["clay", "apollo", "smartlead", "hubspot", "claude-anthropic", "instantly",
                 "rb2b", "artisan", "n8n", "salesforce", "zoominfo", "outreach", "6sense",
                 "gong", "11x", "heyreach", "warmly", "factors-ai", "expandi", "salesloft"]:
        if slug not in vendor_id_by_slug:
            existing = find_post_by_slug("vendor", slug)
            if existing:
                vendor_id_by_slug[slug] = existing["id"]

    # For comparisons: any missing vendor_a/vendor_b slug — auto-stub via ensure_vendor_stub
    from seed import ensure_vendor_stub, CATEGORY_BY_TOOL
    # Extend category map for new slugs referenced only by comparisons
    CATEGORY_BY_TOOL.update({
        "expandi": "linkedin-automation",
        "salesloft": "outbound",
        "heyreach": "linkedin-automation",
        "warmly": "intent-signal",
        "factors-ai": "revenue-intelligence",
    })
    for c in comparisons["comparisons"]:
        for s in [c["vendor_a_slug"], c["vendor_b_slug"]]:
            if s not in vendor_id_by_slug:
                ensure_vendor_stub(vendor_id_by_slug, s)

    seed_comparisons(comparisons, vendor_id_by_slug)
    seed_insights(insights)

    slog("\n=== Sprint 2 Done ===")


if __name__ == "__main__":
    main()
