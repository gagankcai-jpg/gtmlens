## Decision: Automate the monthly Stack Movements column on a recurring schedule, and catch up Q3 reporting manually

## Context
A review on 2026-10-19 found the site had published nothing since Sep 1 (48 days). Root cause: the task that produced the August edition (`gtmlens-stack-movements-august`) was created as a ONE-TIME task and auto-disabled after firing. The data routines (`gtmlens-funding-refresh`, `signal-ma-monitor`) are recurring and ran correctly on Sep 1 and Oct 1 — so the data layer kept working while the editorial layer silently stopped. September's edition was never drafted; the quarterly reports (state-of, three market maps) were never refreshed and still carry Q2 2026 labels two quarters later.

## Alternatives considered
1. Keep the column manual and add a calendar reminder — lowest automation risk, but it is exactly what just failed; nothing enforced it.
2. One-time tasks created month by month (the pattern that produced August) — works, but re-creates the same failure the moment a month is missed.
3. A single recurring monthly task, generalised to "the month that just ended", with an idempotency check.

## Reasoning
Option 3. The failure was structural, not editorial: a one-time task cannot survive its own success. A recurring task with the month derived from the run date removes the per-month human step entirely. The idempotency check (query published slugs first, stop if the edition exists) makes a double-fire or a manual catch-up safe, which is what made option 2 fragile. Scheduled for 11:30 on day 1, after the 09:00 funding routine, so it can read that run's proposal file — the same ordering the August run relied on.

## Trade-offs accepted
- The task still cannot publish without `~/.config/gtmlens/wp.json`, so without that credential it degrades to drafting and a human publishes. Accepted: the credential decision is the user's, and drafting is the expensive part.
- It writes no tracker records. M&A and funding records stay human-reviewed (standing rule). Accepted: it means a published column can briefly describe an event the tracker has not yet recorded — the reverse of what happened to post #630.
- Quarterly reports (state-of, market maps) are NOT automated by this decision. They need judgment about category structure, so they stay manual with a watch item. This is the remaining single point of failure.
- Runs only while the desktop app is open; if closed on day 1 it fires on next launch.

## Supersedes
Nothing. Extends the automation set established alongside `gtmlens-funding-refresh`.
