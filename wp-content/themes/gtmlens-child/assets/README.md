# GTMLens Stack Builder — Assets

This folder contains the three client-side files that power the Stack Builder quiz at `/stack-builder/`.

```
assets/
  data/
    stack-rules.json     Decision tree: vendors, rules, budget tiers
  js/
    stack-quiz.js        Alpine.js v3 component (no build step)
  css/
    stack-quiz.css       Quiz UI styles (inherits child-theme CSS variables)
  README.md              This file
```

---

## JSON schema (`data/stack-rules.json`)

### `vendors` object

Each key is a vendor slug (kebab-case). Fields:

| Field         | Type    | Required | Notes |
|---------------|---------|----------|-------|
| `name`        | string  | yes      | Display name |
| `category`    | string  | yes      | One of the 9 categories (see below) |
| `entryPrice`  | number  | yes      | USD/month. Use `0` for free tiers or when pricing is not published. |
| `tier`        | string  | yes      | `"$"`, `"$$"`, or `"$$$"` |
| `url`         | string  | yes      | Relative path to the vendor profile, e.g. `/vendors/clay/` |
| `fitTags`     | array   | yes      | Quiz values this vendor is a good fit for — informational, not used in rule matching |
| `pricingNote` | string  | no       | Shown as a footnote in the results table when present. Use for estimated prices. |

#### The 9 valid category slugs

```
data-enrichment
outbound
linkedin-automation
crm
intent-signal
orchestration
ai-sdr
revenue-intelligence
lead-capture
```

---

### `rules` array

Rules are evaluated **in order**. The first rule whose conditions match the user's answers wins for that category. Use `"pick": null` to explicitly omit a category for a given selection set.

```json
{
  "category": "data-enrichment",
  "if": {
    "stage":  ["pre-seed", "seed"],
    "motion": ["outbound"],
    "budget": ["<500", "500-2000"]
  },
  "pick": "apollo"
}
```

**`if` conditions:** every key must match one of the listed values. Omit a key to match any value for it. An empty `if: {}` object is a catch-all (used as the final fallback per category).

**Valid answer values:**

| Question | Valid values |
|----------|-------------|
| `stage`  | `pre-seed`, `seed`, `series-a`, `series-a-plus` |
| `motion` | `outbound`, `inbound`, `plg`, `hybrid` |
| `icp`    | `smb`, `mid-market`, `enterprise` |
| `team`   | `founder-led`, `1-3`, `4-10`, `10+` |
| `budget` | `<500`, `500-2000`, `2000-10000`, `10000+` |

**Rule order tip:** put the most specific rules (multiple conditions) first; put the catch-all `if: {}` rule last per category.

---

### `tierBudgets` object

Maps the `budget` answer values to display labels and ceiling amounts. The ceiling is not currently enforced by the JS (rules handle fit), but is available for future budget-overflow warnings.

---

## Adding a new vendor

1. Add an entry to `vendors` in `stack-rules.json` with all required fields.
2. Add one or more rules in the `rules` array (before any existing catch-all for that category).
3. Publish the vendor's profile page at the slug in `url`.
4. Add the `pricingNote` field if the price is estimated.

---

## Adding a new rule path

Insert the rule object **before** the category's existing catch-all rule. Rules are positional — first match wins.

Example: add a rule that picks `clay` for Series A hybrid outbound:

```json
{
  "category": "data-enrichment",
  "if": { "stage": ["series-a"], "motion": ["hybrid"] },
  "pick": "clay"
}
```

---

## Swapping the Fluent Forms endpoint

In `js/stack-quiz.js`, search for `__FORM_ID__` (appears in two places). Replace both with your numeric Fluent Forms form ID.

The submission endpoint is:

```
POST /wp-json/fluentform/v1/forms/{FORM_ID}/submissions
```

Fields sent: `form_id`, `email`, `stack_stage`, `stack_motion`, `stack_icp`, `stack_team`, `stack_budget`, `stack_url`.

Map these fields to your Fluent Form inputs and any MailerLite tags in the Fluent Forms → Integrations panel.

---

## Enqueueing (functions.php)

The parent `functions.php` (or child theme `functions.php`) should already enqueue these files. The pattern is:

```php
// Alpine.js v3 from CDN — must load before stack-quiz.js
wp_enqueue_script(
    'alpinejs',
    'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js',
    [],
    null,
    true  // footer
);

// Expose the JSON URL to JS
wp_add_inline_script('alpinejs', sprintf(
    'window.stackQuizData = %s;',
    wp_json_encode(['rulesUrl' => get_theme_file_uri('assets/data/stack-rules.json')])
), 'before');

wp_enqueue_script(
    'stack-quiz',
    get_theme_file_uri('assets/js/stack-quiz.js'),
    ['alpinejs'],
    '1.0.0',
    true
);

wp_enqueue_style(
    'stack-quiz',
    get_theme_file_uri('assets/css/stack-quiz.css'),
    [],
    '1.0.0'
);
```

Only enqueue on the Stack Builder page template to avoid loading on every page:

```php
if (is_page_template('page-stack-builder.php')) {
    // enqueue calls above
}
```

---

## Deep-link URL format

Shareable URLs encode all five answers as query parameters:

```
/stack-builder/?stage=seed&motion=outbound&icp=smb&team=1-3&budget=500-2000
```

When this URL is loaded, the JS component auto-evaluates the rules and renders results directly, skipping the question flow. This is the format copied by the "Share this stack" button.
