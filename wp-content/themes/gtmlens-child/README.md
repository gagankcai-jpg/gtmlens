# GTMLens Child Theme — Install Guide

Kadence child theme for [gtmlens.com](https://gtmlens.com).

## Requirements

- WordPress 6.x
- PHP 8.1+
- [Kadence theme](https://wordpress.org/themes/kadence/) (free version) installed and active
- [Advanced Custom Fields (ACF)](https://wordpress.org/plugins/advanced-custom-fields/) — free version

## Installation Steps

### 1. Upload the theme

Copy the `gtmlens-child` folder into `/wp-content/themes/` on your server.

In WP Admin: **Appearance → Themes → Activate** "GTMLens Child".

### 2. Flush permalinks

Go to **Settings → Permalinks** and click **Save Changes** (no changes needed — just save).
This registers the CPT rewrite rules for `/vendors/`, `/stack-builder/`, and `/compare/`.

### 3. Import ACF field groups

ACF field groups are committed in `acf-json/`. ACF loads them automatically on activation — no manual import needed.

To verify: **Custom Fields → Field Groups** — you should see four groups:
- Vendor Fields
- Stack Fields
- Comparison Fields
- Insight Meta (E-E-A-T)

If groups are missing, go to **Custom Fields → Tools → Import** and select each JSON file from `acf-json/`.

### 4. Seed taxonomy terms

Go to **Vendors → Vendor Categories** and create the 9 category terms:

| Term | Slug |
|---|---|
| Data & Enrichment | data-enrichment |
| Outbound & Sequencing | outbound |
| LinkedIn Automation | linkedin-automation |
| CRM | crm |
| Intent & Signal | intent-signal |
| Orchestration / Workflow | orchestration |
| AI SDR / Agentic Outbound | ai-sdr |
| Revenue Intelligence | revenue-intelligence |
| Lead Capture & Conversion | lead-capture |

Add editorial descriptions to each term — these appear on the category landing pages.

### 5. Create editorial policy page

In WP Admin: **Pages → Add New**
- Title: `Editorial Policy`
- URL slug: `about/editorial-policy` (or set the parent page to "About")
- Template: **Editorial Policy** (select from Page Attributes)
- Publish

### 6. Stack Builder page

Create a Page with:
- Title: `Stack Builder`
- Slug: `stack-builder`
- Content: `[stack_quiz]`

The shortcode renders the interactive quiz. The quiz JS reads from `assets/js/stack-quiz.js` and uses `assets/data/stack-rules.json` for decision logic.

### 7. Newsletter form

The home page includes `<div id="newsletter-signup"></div>`. Drop a Fluent Forms shortcode inside this div via the home page editor, or use a hook to inject the form.

### 8. Verify schema

After publishing at least one vendor profile, one comparison, and one insight:
1. Use [Google Rich Results Test](https://search.google.com/test/rich-results) on a vendor profile URL → should return **Product + Review**.
2. Test a comparison URL → should return **ItemList**.
3. Test an insight URL → should return **Article**.

## Theme File Map

```
gtmlens-child/
├── style.css                   Child theme header + all custom CSS
├── functions.php               CPTs, taxonomies, ACF paths, schema, shortcode
├── front-page.php              Home page
├── single-vendor.php           Vendor profile
├── single-comparison.php       Vendor-vs-vendor comparison
├── single-stack.php            Stack recipe
├── archive-vendor.php          Vendor directory grid
├── taxonomy-vendor_category.php  Category landing page
├── page-editorial-policy.php   Static editorial policy page
├── acf-json/
│   ├── group_vendor.json
│   ├── group_stack.json
│   ├── group_comparison.json
│   └── group_insight_meta.json
└── assets/
    ├── js/
    │   └── stack-quiz.js       Quiz UI (vanilla JS, no build step)
    └── data/
        └── stack-rules.json    Decision tree + vendor pricing reference
```

## Notes

- `stack-rules.json` pricing is estimated as of April 2026. Verify entry prices against live vendor pricing pages before publishing.
- The quiz JS (`stack-quiz.js`) derives a recommended stack CPT tier from the budget question. Ensure at least one published `stack` CPT post exists per tier (`pre-seed`, `seed`, `series-a`, `enterprise`) for the quiz redirect to work.
- The `last_updated` ACF field on vendor profiles is required. Set it on every profile — it feeds both the on-page display and the Schema.org `datePublished` property.
- No advisory routes, booking links, or Calendly references are present anywhere in v1. Keep it that way until Phase 2.
