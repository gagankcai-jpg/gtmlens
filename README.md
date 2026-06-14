# GTMLens — AI-Native GTM Intelligence Hub

**Live site:** [gtmlens.com](https://gtmlens.com)

An independent analyst publication tracking the AI-native go-to-market tooling landscape — vendor profiles, head-to-head comparisons, funding intelligence, and a live market map. No affiliate links, no vendor bias.

---

## What It Is

GTMLens covers the emerging category of AI-native GTM tooling: AI SDRs, data enrichment platforms, outbound sequencers, revenue intelligence tools, and the foundation models powering them. The site serves GTM engineers, RevOps leaders, and B2B SaaS founders who need independent, structured analysis rather than vendor-published content.

**Positioning:** *The independent analyst's view of the AI-native GTM stack — intelligence, not affiliate content.*

---

## Site Architecture

| Layer | Tech |
|---|---|
| Platform | WordPress 6.x on Hostinger |
| Theme | Custom Kadence child theme (`gtmlens-child`) |
| Custom fields | Advanced Custom Fields (ACF) — field groups in `acf-json/` |
| Data model | 4 custom post types + 1 taxonomy |
| Automation | WP-Cron jobs (funding scanner, vendor change detector) |
| Schema | JSON-LD (Product, ItemList, Article, Organization) via Rank Math |

---

## Data Model

### Custom Post Types

| CPT | Slug | Description |
|---|---|---|
| Vendor | `vendor` | Full profiles — SWOT, analyst take, funding history, pricing tier |
| Comparison | `comparison` | Head-to-head battle cards with decision rules and buyer fit |
| Stack Recipe | `stack` | Curated tool combinations for specific GTM motions |
| Funding Event | `funding_event` | Individual funding rounds (auto-ingested via cron) |

### Taxonomy
- **Vendor Category** (`vendor_category`) — 11 categories: AI SDR, Outbound & Sequencing, Data & Enrichment, Data Activation, CRM, Intent & Signal, Orchestration, Revenue Intelligence, Lead Capture, LinkedIn Automation, Foundation Models

### ACF Field Groups (`acf-json/`)
- `group_vendor.json` — analyst_take, swot_*, entry_price, best_fit, last_updated, funding fields
- `group_comparison.json` — vendor_a/b (post_object), decision_rule_a/b (WYSIWYG), last_updated
- `group_stack.json` — stack tier, tool list, use-case description
- `group_funding_event.json` — amount, stage, valuation, date, source URL
- `group_insight_meta.json` — E-E-A-T fields (author, review date, methodology)

---

## Features

### Live Market Map
Interactive vendor map organized by category. Vendors with funding rounds in the last 30 days get a momentum halo. Rendered via `[market_map]` shortcode in `inc/market-map.php`.

### Funding Tracker (`/funding-tracker/`)
Full dashboard including:
- **Pace insight** — current-quarter capital vs. prior quarter run rate
- **Sparkline bars** — per-quarter capital raised (log-scaled)
- **Recent rounds** — top 5 rounds in last 90 days, sorted by date
- **Stage mix** — Series A/B/C/D breakdown by capital (ex-Foundation Models)
- **Capital card** — total tracked + GTM-only (ex-FM) sub-line
- Powered by `gtmlens_get_funding_events()` in `functions.php`

### Vendor Profiles
Each vendor gets: analyst take, SWOT (4 fields), entry price, best-fit profile, last funding round details, and cross-linked comparisons. Schema.org `Product + Review` JSON-LD auto-generated.

### Comparisons (`/compare/`)
Side-by-side battle cards with structured decision rules (WYSIWYG), verdict badges, and `ItemList` schema. Current comparisons include Claude vs. GPT-4o, Decagon vs. Crescendo, and others.

### Auto-Ingest Cron (`inc/cron-jobs.php`)
- **Funding scanner** — scans 3 RSS feeds weekly, pattern-matches vendor names, creates `funding_event` drafts for review
- **Vendor change detector** — normalizes and SHA-256 hashes vendor landing pages, flags material changes; uses `_url_hash_v2` baseline to prevent false positives from dynamic script/ad content

### SEO automation (`functions.php`)
- **301 redirect map** (`gtmlens_301_redirects` on `template_redirect`) — resolves stale vendor-slug aliases and removed category/comparison URLs flagged as 404s in Google Search Console (e.g. `/vendors/anthropic/` → `/vendors/claude-anthropic/`)
- **Organization schema alignment** (`gtmlens_align_org_schema` on `rank_math/json_ld`) — single canonical Organization node with the correct name + logo
- **`funding_event` noindex** — individual round CPT entries are kept out of the index; the funding tracker is the canonical surface

### Homepage Intelligence Layer
- Hero capital stat (last 90 days, GTM rounds only, ex-Foundation Models)
- Pulse strip — 5 most recent funding events with links
- Featured insight rotation (auto-selects newest post)
- Comparison cards with `last_updated` dates
- Category tiles with vendor counts (primary-category deduped)

---

## Repo Structure

```
gtmlens/
├── wp-content/
│   └── themes/
│       └── gtmlens-child/          # WordPress child theme (Kadence)
│           ├── style.css           # All custom CSS
│           ├── functions.php       # CPTs, taxonomies, schema, shortcodes, cron helpers
│           ├── front-page.php      # Homepage
│           ├── single-vendor.php   # Vendor profile template
│           ├── single-comparison.php
│           ├── archive-vendor.php  # Vendor directory grid
│           ├── taxonomy-vendor_category.php
│           ├── page-funding-tracker.php
│           ├── inc/
│           │   ├── market-map.php  # [market_map] shortcode
│           │   ├── cron-jobs.php   # Auto-ingest + change scanner
│           │   └── ai-attribution-chip.php
│           └── acf-json/           # ACF field group definitions (auto-loaded)
├── scripts/                        # Data seeding + maintenance scripts
│   ├── seed.py                     # Bulk vendor/comparison seeder via WP REST API
│   ├── seed2.py
│   ├── seed_gtmeng_pages.py        # GTM engineering pillar pages
│   ├── build_stack_rules.py        # Generates stack-rules.json decision tree
│   └── update_stacks.py
├── data/                           # Content batch JSON files (seed data)
│   ├── content-batch-1.json        # Initial 20 vendor profiles
│   ├── content-batch-2-*.json      # Comparisons, insights, playbooks
│   └── ...
├── content-drafts/                 # Research drafts for tier-1 vendor deep-dives
├── decisions/                      # Architecture decision records
├── knowledge/                      # Running knowledge base
└── gtm-intelligence-site-plan.md  # Original strategic build plan
```

---

## Key Design Decisions

**No build step.** All PHP + vanilla JS. The stack-quiz is 329 lines of vanilla JS + a JSON rules file — no React, no bundler, no CI pipeline needed. Deploys via WP Theme File Editor or FTP.

**ACF over Gutenberg blocks.** Vendor profiles are too structured for free-form blocks. ACF gives typed fields, REST-readable data, and a clean PHP API. Field group schemas are version-controlled in `acf-json/` so they're portable across WP installs.

**FM exclusion in capital stats.** Foundation model rounds (Anthropic $965B, OpenAI $852B) dwarf GTM-tool rounds by 1000×. All capital metrics that inform GTM market sizing exclude the Foundation Models category.

**Hash v2 baseline for vendor change scanner.** The v1 hash used a weak regex that left dynamic ad/script content in the normalized text, causing 48/53 vendors to trigger as "material changes" every week. v2 strips all scripts, styles, and digits before hashing, reducing false positives to near-zero.

---

## Running the Seed Scripts

Requires: Python 3.10+, `requests` library, a WP Application Password.

```bash
pip install requests
export WP_URL="https://gtmlens.com"
export WP_USER="your-wp-username"
export WP_APP_PASSWORD="xxxx xxxx xxxx xxxx xxxx xxxx"

python scripts/seed.py           # Seed initial vendor batch
python scripts/seed2.py          # Seed comparisons + insights
```

The scripts use the WP REST API. ACF fields require the ACF REST extension or the metabox form path (see `scripts/` inline comments).

---

## Data Coverage (as of June 2026)

| Metric | Count |
|---|---|
| Tracked vendors | 54 |
| Comparisons | 5+ |
| Funding events | 30+ |
| Insights / deep dives | 3+ |
| Categories | 11 |
| Capital tracked (last 90d, ex-FM) | ~$1.2B |

---

## License

Theme code: MIT  
Content (vendor profiles, comparisons, analyst takes): © GTMLens / Gagan Chawla — not licensed for redistribution
