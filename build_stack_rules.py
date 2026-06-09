#!/usr/bin/env python3
"""Regenerate stack-rules.json against the 31 live vendors + 6-tier stage system."""
import json
from pathlib import Path

# ──────────────────────────────────────────────────────────────────────────────
# Vendors — only LIVE vendor slugs, with conservative entry-price floors.
# Prices were pulled from WP API + reconciled against public list pricing
# where available. Estimated values are flagged in pricingNote.
# ──────────────────────────────────────────────────────────────────────────────
VENDORS = {
    # Data & Enrichment
    "clay":      {"name": "Clay", "category": "data-enrichment", "entryPrice": 149, "tier": "$$",
                  "fitTags": ["seed","series-a","series-b","series-c","outbound","hybrid","smb","mid-market"],
                  "pricingNote": "Starter ~$149/mo; scales with credits"},
    "apollo":    {"name": "Apollo.io", "category": "data-enrichment", "entryPrice": 49, "tier": "$",
                  "fitTags": ["pre-seed","seed","outbound","smb","founder-led","1-3"]},
    "zoominfo":  {"name": "ZoomInfo", "category": "data-enrichment", "entryPrice": 1500, "tier": "$$$",
                  "fitTags": ["series-c","enterprise","mid-market","enterprise-icp","10+"],
                  "pricingNote": "Estimated team-plan floor; enterprise contracts $50K-$500K+/yr"},

    # Outbound / Sequencing
    "smartlead": {"name": "Smartlead", "category": "outbound", "entryPrice": 39, "tier": "$$",
                  "fitTags": ["seed","series-a","series-b","outbound","hybrid","1-3","4-10"]},
    "instantly": {"name": "Instantly", "category": "outbound", "entryPrice": 37, "tier": "$",
                  "fitTags": ["pre-seed","seed","outbound","smb","founder-led","1-3"]},
    "lemlist":   {"name": "Lemlist", "category": "outbound", "entryPrice": 39, "tier": "$$",
                  "fitTags": ["seed","series-a","outbound","hybrid","1-3","4-10"]},
    "outreach":  {"name": "Outreach", "category": "outbound", "entryPrice": 100, "tier": "Enterprise",
                  "fitTags": ["series-c","enterprise","mid-market","enterprise-icp","10+"],
                  "pricingNote": "Per-user; enterprise minimums apply"},
    "salesloft": {"name": "Salesloft", "category": "outbound", "entryPrice": 125, "tier": "Enterprise",
                  "fitTags": ["series-c","enterprise","10+"],
                  "pricingNote": "Per-user; enterprise contract minimums"},

    # LinkedIn Automation
    "heyreach":     {"name": "HeyReach", "category": "linkedin-automation", "entryPrice": 79, "tier": "$$",
                     "fitTags": ["seed","series-a","series-b","outbound","hybrid","1-3","4-10"]},
    "expandi":      {"name": "Expandi", "category": "linkedin-automation", "entryPrice": 99, "tier": "$$",
                     "fitTags": ["seed","series-a","outbound","1-3","4-10"]},
    "phantombuster":{"name": "Phantombuster", "category": "linkedin-automation", "entryPrice": 56, "tier": "$",
                     "fitTags": ["pre-seed","seed","outbound","founder-led","1-3"]},

    # CRM
    "hubspot":   {"name": "HubSpot", "category": "crm", "entryPrice": 15, "tier": "$$",
                  "fitTags": ["pre-seed","seed","series-a","series-b","series-c","smb","mid-market"]},
    "attio":     {"name": "Attio", "category": "crm", "entryPrice": 34, "tier": "$",
                  "fitTags": ["seed","series-a","plg","inbound","smb","mid-market"]},
    "pipedrive": {"name": "Pipedrive", "category": "crm", "entryPrice": 24, "tier": "$",
                  "fitTags": ["pre-seed","seed","outbound","smb","founder-led","1-3"]},
    "salesforce":{"name": "Salesforce", "category": "crm", "entryPrice": 165, "tier": "Enterprise",
                  "fitTags": ["series-c","enterprise","enterprise-icp","10+"]},

    # Intent / Signal
    "rb2b":      {"name": "RB2B", "category": "intent-signal", "entryPrice": 0, "tier": "$",
                  "fitTags": ["pre-seed","seed","series-a","series-b","inbound","plg","hybrid","smb","mid-market"],
                  "pricingNote": "Free tier 100 IDs/mo; paid plans from $149/mo"},
    "warmly":    {"name": "Warmly", "category": "intent-signal", "entryPrice": 700, "tier": "$$$",
                  "fitTags": ["series-a","series-b","inbound","hybrid","mid-market"]},
    "factors-ai":{"name": "Factors.ai", "category": "intent-signal", "entryPrice": 399, "tier": "$$",
                  "fitTags": ["series-a","series-b","series-c","hybrid","inbound","mid-market"]},
    "6sense":    {"name": "6sense", "category": "intent-signal", "entryPrice": 5000, "tier": "Enterprise",
                  "fitTags": ["enterprise","enterprise-icp","10+"],
                  "pricingNote": "Enterprise floor; contracts $100K-$500K+/yr"},
    "demandbase":{"name": "Demandbase", "category": "intent-signal", "entryPrice": 5000, "tier": "Enterprise",
                  "fitTags": ["enterprise","enterprise-icp","10+"]},

    # Orchestration / Automation
    "zapier":    {"name": "Zapier", "category": "orchestration", "entryPrice": 20, "tier": "$",
                  "fitTags": ["pre-seed","seed","series-a","founder-led","1-3","4-10"]},
    "n8n":       {"name": "n8n", "category": "orchestration", "entryPrice": 20, "tier": "$$",
                  "fitTags": ["seed","series-a","series-b","series-c","outbound","hybrid","4-10","10+"]},

    # AI SDR
    "11x":       {"name": "11x", "category": "ai-sdr", "entryPrice": 5000, "tier": "Enterprise",
                  "fitTags": ["series-b","series-c","enterprise","outbound","mid-market","enterprise-icp","4-10","10+"]},
    "artisan":   {"name": "Artisan", "category": "ai-sdr", "entryPrice": 6000, "tier": "Enterprise",
                  "fitTags": ["series-b","series-c","enterprise","outbound","mid-market","enterprise-icp","4-10","10+"]},

    # Revenue Intelligence
    "gong":          {"name": "Gong", "category": "revenue-intelligence", "entryPrice": 2000, "tier": "Enterprise",
                      "fitTags": ["series-c","enterprise","mid-market","enterprise-icp","10+"]},
    "chorus":        {"name": "Chorus", "category": "revenue-intelligence", "entryPrice": 2000, "tier": "Enterprise",
                      "fitTags": ["series-c","enterprise","10+"]},
    "hockeystack":   {"name": "HockeyStack", "category": "revenue-intelligence", "entryPrice": 1500, "tier": "$$$",
                      "fitTags": ["series-b","series-c","hybrid","inbound","mid-market"]},
    "spotlight-ai":  {"name": "Spotlight.ai", "category": "revenue-intelligence", "entryPrice": 50, "tier": "$$",
                      "fitTags": ["series-a","series-b","mid-market","4-10","10+"]},

    # Lead Capture
    "tally":     {"name": "Tally", "category": "lead-capture", "entryPrice": 0, "tier": "$",
                  "fitTags": ["pre-seed","seed","series-a","inbound","plg","smb","founder-led","1-3"]},
    "typeform":  {"name": "Typeform", "category": "lead-capture", "entryPrice": 25, "tier": "$$",
                  "fitTags": ["seed","series-a","series-b","inbound","mid-market","4-10"]},

    # Foundation Models — kept in vendors block for quiz/profile cross-link
    "claude-anthropic": {"name": "Claude (Anthropic)", "category": "foundation-models",
                         "entryPrice": 0, "tier": "$$",
                         "fitTags": ["seed","series-a","series-b","series-c","enterprise"],
                         "pricingNote": "API: $3/M input, $15/M output (Sonnet 4.6); usage-based"},
}

# ──────────────────────────────────────────────────────────────────────────────
# Rules — ordered; first match within a category wins.
# Stages: pre-seed | seed | series-a | series-b | series-c | enterprise
# ──────────────────────────────────────────────────────────────────────────────
RULES = [
    # Data & Enrichment
    {"category": "data-enrichment", "if": {"stage": ["pre-seed"]}, "pick": "apollo",
     "_note": "Apollo's data + sequencing in one tool minimizes pre-seed cost"},
    {"category": "data-enrichment", "if": {"stage": ["seed"], "motion": ["outbound","hybrid"]}, "pick": "clay",
     "_note": "Clay's enrichment waterfall justifies cost once outbound volume rises"},
    {"category": "data-enrichment", "if": {"stage": ["series-a","series-b"]}, "pick": "clay",
     "_note": "Clay scales with credits; remains the orchestration layer through Series B"},
    {"category": "data-enrichment", "if": {"stage": ["series-c","enterprise"], "icp": ["enterprise"]}, "pick": "zoominfo",
     "_note": "Enterprise ICP requires ZoomInfo's breadth and compliance data"},
    {"category": "data-enrichment", "if": {"stage": ["series-c","enterprise"]}, "pick": "clay",
     "_note": "Clay still relevant at series-c+ for non-enterprise ICPs"},
    {"category": "data-enrichment", "if": {}, "pick": "apollo", "_note": "Default fallback"},

    # Outbound
    {"category": "outbound", "if": {"motion": ["inbound","plg"], "stage": ["pre-seed","seed"]}, "pick": None,
     "_note": "Pure inbound/PLG at early stage doesn't need a sequencer"},
    {"category": "outbound", "if": {"stage": ["pre-seed"]}, "pick": "instantly",
     "_note": "Instantly: deliverability + unlimited inboxes is best value at pre-seed"},
    {"category": "outbound", "if": {"stage": ["seed"], "team": ["founder-led","1-3"]}, "pick": "smartlead",
     "_note": "Smartlead's agency-grade infra scales with founder/SDR-led outbound"},
    {"category": "outbound", "if": {"stage": ["series-a"], "team": ["1-3","4-10"]}, "pick": "smartlead",
     "_note": "Smartlead remains the sender of choice at Series A"},
    {"category": "outbound", "if": {"stage": ["series-b"]}, "pick": "smartlead",
     "_note": "Smartlead scales through Series B; migrate to Outreach at Series C"},
    {"category": "outbound", "if": {"stage": ["series-c","enterprise"]}, "pick": "outreach",
     "_note": "Outreach for compliant multi-channel sequencing at scale"},
    {"category": "outbound", "if": {}, "pick": "smartlead", "_note": "Default fallback"},

    # LinkedIn Automation
    {"category": "linkedin-automation", "if": {"motion": ["inbound","plg"]}, "pick": None,
     "_note": "Inbound/PLG motions don't need LinkedIn automation"},
    {"category": "linkedin-automation", "if": {"stage": ["pre-seed"]}, "pick": None,
     "_note": "Skip at pre-seed; manual LinkedIn is fine"},
    {"category": "linkedin-automation", "if": {"stage": ["seed","series-a","series-b"]}, "pick": "heyreach",
     "_note": "HeyReach: best price-perf for early teams, multi-account at series-a+"},
    {"category": "linkedin-automation", "if": {"stage": ["series-c","enterprise"]}, "pick": "expandi",
     "_note": "Expandi: more account-level controls suit larger orgs"},

    # CRM
    {"category": "crm", "if": {"stage": ["pre-seed"], "team": ["founder-led"]}, "pick": "pipedrive",
     "_note": "Pipedrive's deal-pipeline focus suits founder-led selling"},
    {"category": "crm", "if": {"motion": ["plg","inbound"], "stage": ["seed","series-a"]}, "pick": "attio",
     "_note": "Attio's relational data model fits PLG/inbound motions"},
    {"category": "crm", "if": {"stage": ["pre-seed","seed","series-a","series-b"]}, "pick": "hubspot",
     "_note": "HubSpot: free CRM tier through Pro covers pre-seed→Series B"},
    {"category": "crm", "if": {"stage": ["series-c","enterprise"], "icp": ["enterprise"]}, "pick": "salesforce",
     "_note": "Salesforce when enterprise ICP forces enterprise CRM"},
    {"category": "crm", "if": {"stage": ["series-c","enterprise"]}, "pick": "hubspot",
     "_note": "HubSpot Enterprise is viable through series-c for non-enterprise ICP"},
    {"category": "crm", "if": {}, "pick": "hubspot", "_note": "Default fallback"},

    # Intent / Signal
    {"category": "intent-signal", "if": {"stage": ["pre-seed"]}, "pick": None,
     "_note": "Skip at pre-seed; not enough traffic to justify"},
    {"category": "intent-signal", "if": {"motion": ["outbound"], "stage": ["seed","series-a","series-b"]}, "pick": "rb2b",
     "_note": "RB2B's free tier covers early traffic; paid plans scale"},
    {"category": "intent-signal", "if": {"motion": ["inbound","plg","hybrid"], "stage": ["seed","series-a"]}, "pick": "warmly",
     "_note": "Warmly: best deanonymization+routing for inbound/PLG at series-a"},
    {"category": "intent-signal", "if": {"stage": ["series-b","series-c"]}, "pick": "factors-ai",
     "_note": "Factors.ai: account-level intent + revenue ops mature at series-b+"},
    {"category": "intent-signal", "if": {"stage": ["enterprise"], "icp": ["enterprise"]}, "pick": "6sense",
     "_note": "6sense for enterprise ICP requires intent-account match coverage"},
    {"category": "intent-signal", "if": {"stage": ["enterprise"]}, "pick": "demandbase",
     "_note": "Demandbase for enterprise tier with non-enterprise ICP"},

    # Orchestration / Automation
    {"category": "orchestration", "if": {"stage": ["pre-seed","seed"], "team": ["founder-led","1-3"]}, "pick": "zapier",
     "_note": "Zapier's no-code wins at small-team early-stage"},
    {"category": "orchestration", "if": {"stage": ["seed","series-a","series-b","series-c","enterprise"]}, "pick": "n8n",
     "_note": "n8n: self-hostable, scales with engineering investment"},
    {"category": "orchestration", "if": {}, "pick": "zapier", "_note": "Default fallback"},

    # AI SDR
    {"category": "ai-sdr", "if": {"motion": ["inbound","plg"]}, "pick": None,
     "_note": "AI SDRs only meaningful for outbound or hybrid"},
    {"category": "ai-sdr", "if": {"stage": ["pre-seed","seed","series-a"]}, "pick": None,
     "_note": "AI SDR economics don't work below Series B"},
    {"category": "ai-sdr", "if": {"stage": ["series-b","series-c"]}, "pick": "11x",
     "_note": "11x's Claude-powered agent handles tier-2 outbound at machine throughput"},
    {"category": "ai-sdr", "if": {"stage": ["enterprise"]}, "pick": "artisan",
     "_note": "Artisan: more enterprise-oriented contract structure at scale"},

    # Revenue Intelligence
    {"category": "revenue-intelligence", "if": {"stage": ["pre-seed","seed"]}, "pick": None,
     "_note": "Not justified below Series A"},
    {"category": "revenue-intelligence", "if": {"motion": ["inbound","plg"], "stage": ["series-a","series-b"]}, "pick": "hockeystack",
     "_note": "HockeyStack: marketing/PLG attribution focus"},
    {"category": "revenue-intelligence", "if": {"stage": ["series-a"], "team": ["4-10","10+"]}, "pick": "spotlight-ai",
     "_note": "Spotlight.ai entry tier viable at Series A for sales coaching"},
    {"category": "revenue-intelligence", "if": {"stage": ["series-b","series-c","enterprise"]}, "pick": "gong",
     "_note": "Gong: industry standard for call intelligence at scale"},

    # Lead Capture
    {"category": "lead-capture", "if": {"motion": ["outbound"], "stage": ["pre-seed","seed"]}, "pick": None,
     "_note": "Outbound-only at early stage doesn't need lead capture"},
    {"category": "lead-capture", "if": {"stage": ["pre-seed","seed"]}, "pick": "tally",
     "_note": "Tally's free tier handles early inbound forms"},
    {"category": "lead-capture", "if": {"stage": ["series-a","series-b","series-c","enterprise"]}, "pick": "typeform",
     "_note": "Typeform's branding + integrations scale with marketing org"},
]

TIER_BUDGETS = {
    "<500":         {"label": "Pre-Seed / Bootstrap", "ceiling": 500},
    "500-2000":     {"label": "Seed",                 "ceiling": 2000},
    "2000-6000":    {"label": "Series A",             "ceiling": 6000},
    "6000-12000":   {"label": "Series B",             "ceiling": 12000},
    "12000-20000":  {"label": "Series C",             "ceiling": 20000},
    "20000+":       {"label": "Enterprise",           "ceiling": 999999},
}

# Add url field for each vendor
for slug, v in VENDORS.items():
    v["url"] = f"/vendors/{slug}/"

OUT = {"vendors": VENDORS, "rules": RULES, "tierBudgets": TIER_BUDGETS}

target = Path("wp-content/themes/gtmlens-child/assets/data/stack-rules.json")
header = "// Pricing as of May 2026 — verify before publishing\n"
target.write_text(header + json.dumps(OUT, indent=2) + "\n")
print(f"Wrote {target} ({target.stat().st_size:,} bytes, {len(VENDORS)} vendors, {len(RULES)} rules)")
