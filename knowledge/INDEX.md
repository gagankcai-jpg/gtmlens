# Knowledge index

Each domain holds `knowledge.md` (facts and dated findings), `rules.md` (confirmed — apply by default) and `hypotheses.md` (unconfirmed — need more data).

- **[content-ops](content-ops/knowledge.md)** — the editorial operation for gtmlens.com: dated audit and refresh cycles from the 2026-07-04 crawl through the 2026-10-19 review, what each cycle published and corrected, and the standing watchlist. See [rules](content-ops/rules.md) for the accuracy gates, prediction discipline and data-propagation order; [hypotheses](content-ops/hypotheses.md) for the category theses under test.
- **[site-design](site-design/knowledge.md)** — how the GTMLens theme and data layer actually behave: market map, vendor data shape, ACF/REST write paths, theme deploy, LiteSpeed. See [rules](site-design/rules.md) for the ACF write rules, enum traps, the repeater endpoint and the CodeMirror theme-editor method; [hypotheses](site-design/hypotheses.md) for the demoted zip-deploy rule and other untested paths.

## Conventions
- Promote a hypothesis to a rule at the third independent confirmation; demote a rule to a hypothesis when contradicted, with a note on the contradicting evidence.
- `knowledge.md` is append-only and dated. `rules.md` is edited in place and should stay short enough to read before starting work.
- Cross-session decisions live in [/decisions](../decisions/), newest-dated file wins unless it names a supersede.
