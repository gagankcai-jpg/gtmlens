## Decision: Stack pages show "Last updated" from the WordPress modified date, not a new ACF field

## Context
The full-site review found the 6 stack pages carry cost estimates but no freshness signal. Vendors and comparisons use an ACF `last_updated` field, rendered by `gtmlens_last_updated()`. The stack field group has no such field.

## Alternatives considered
1. Add a `last_updated` ACF field to the stack group and set it on each stack. Rejected for now: it needs a field-group change in both acf-json and the DB. It also requires choosing a date for six pages nobody has re-verified, and rule 10 forbids stamping a page that was not changed.
2. Leave stacks unstamped. Rejected: these pages carry the most price-sensitive content on the site, and readers get no signal that the estimates date from April–July.
3. Render `get_the_modified_date()` in single-stack.php under the ICP line, using the same `gl-last-updated` markup.
4. Show the publish date. Rejected: it understates edits made since publication.

## Reasoning
Option 3 is honest by construction: it reports when the content last changed and cannot be stamped forward without an edit. It needed one template change and no data migration. It also shows readers, today, that the stack costs date from Apr 30 to Jul 4, 2026.

## Trade-offs accepted
- A non-editorial save (a typo fix, a re-save) moves the date forward without a cost re-check. Accepted: rare on stacks, and it is still a real edit date.
- Stacks render freshness differently from vendors and comparisons (post meta vs ACF). If stacks get a review workflow, switch to option 1 and supersede this decision.

## Supersedes
None.
