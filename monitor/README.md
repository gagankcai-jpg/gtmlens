# Monitor — gtmlens-funding-refresh

Monthly Claude routine (`~/.claude/scheduled-tasks/gtmlens-funding-refresh/`, cron `0 9 1 * *`)
that web-researches confirmed funding / M&A / launches for the ~54 tracked GTM vendors and
applies high-confidence updates to the live site, with a changelog + audit trail.

## Modes
- **Apply mode** — when the WP credential below exists AND the `/gtmlens/v1/apply-funding`
  endpoint is live. Writes vendor funding fields + creates a `funding_event` via authenticated
  REST (`update_field` server-side). Every write snapshots prior values to the vendor's
  `_auto_applied_log` meta for rollback.
- **Propose-only mode** — automatic fallback when the credential or endpoint is missing, or a
  finding is single-source / undated / ambiguous. Writes a proposal file, no site changes.

## Confidence gate (auto-apply)
Applies only when: ≥2 independent reputable sources agree on figure + date; the new round date
is newer than the vendor's current `last_round_date`; the vendor maps to a tracked slug.

## Output
- `monitor/proposals/<YYYY-MM-DD>.md` — applied + needs-review sections, with sources.
- `monitor/state.json` — `lastRun` advanced each run.
- Commits + pushes both to `origin`.

## Credential (you create this — never committed)
Create `~/.config/gtmlens/wp.json`:
```json
{ "user": "<wp-username>", "app_password": "<WP Application Password>", "base": "https://gtmlens.com" }
```
Generate the app password in WP Admin → Users → Profile → Application Passwords. The user needs
the `edit_posts` capability (Editor/Admin). Until this file exists, the routine runs propose-only.
