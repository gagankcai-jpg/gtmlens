# AI GTM Intelligence — Strategic Build Plan

**Site concept:** An independent, analyst-grade intelligence hub for the AI-native GTM category, with an advisory arm for early-stage B2B SaaS founders.
**Platform:** Hostinger + WordPress
**Author:** Gagan
**Prepared:** April 2026

---

## 1. Executive Summary

The AI GTM category exploded in 2025 and went mainstream in early 2026 — LinkedIn listed over 3,000 open GTM Engineering roles in January 2026, Clay raised a $100M Series C at a $3.1B valuation, and the "GTM Engineer" title crossed from niche to job-ladder mainstream at companies like Cursor, Lovable, Webflow, and Ramp.

Yet the content landscape is dominated by **vendor-published content** (Clay, Apollo, SyncGTM, Factors.ai, DevCommX) with built-in bias. Independent analyst-grade coverage is scarce. GTM Partners is the closest analog but focuses on frameworks and training, not tool intelligence. **There is a clear white space for an independent "Gartner-style" intelligence site for AI GTM — and a downstream advisory business attached to it.**

Your edge: analyst discipline from the observability/AI-infra beat, an existing framework library (pm-strategy-kit), and a recognizable adjacency story — the same "telemetry → AI agents → signal-to-action" arc that reshaped SecOps and SRE is now reshaping RevOps. Few in the GTM space can speak that cross-domain.

**Positioning in one line:** *The independent analyst's view of the AI-native GTM stack — intelligence, not affiliate content.*

---

## 2. White-Space Analysis — Where You Win

| Existing Player | What They Do | Where They Fall Short | Your Wedge |
|---|---|---|---|
| **Clay / Apollo / SyncGTM blogs** | Vendor thought leadership, workflow recipes | Product-biased; rank their own tool #1 | Vendor-neutral ranking, battle-card format |
| **GTM Partners** | Framework, training, GTM OS | Not focused on tool intelligence; paid access | Open intel, vendor maps, deeper tool coverage |
| **Claymation newsletter** | GTM engineering recipes | Clay-centric, tactical not strategic | Market structure, competitive dynamics, M&A angle |
| **The GTM Engineer (thegtme.com)** | Substack, Clay-owned | Editorial captive of Clay | Independence as a feature, not a bug |
| **HG Insights / Demandbase blogs** | Enterprise GTM intelligence | Selling to large enterprise only | Serves startups and the GTM Engineer persona |
| **Generic "best tools 2026" listicles** | SEO affiliate content | No depth, no analyst framework | Apply observability-grade rigor — SWOT, TAM, architecture |

**Your three defensible moats:**

1. **Independence.** You do not sell tools. Every other major content source does.
2. **Analyst framework reuse.** Battle cards, SWOTs, TAM/SAM/SOM, and architectural comparisons are already your native format. Nobody in the GTM content space writes at that level.
3. **Cross-domain narrative.** "Agent observability → GTM observability," "Telemetry pipelines → Signal pipelines," "Decision Ops for Revenue" — these arcs are unique to you. They also build a bridge from your existing LinkedIn audience (observability/security) into the GTM audience.

---

## 3. Brand & Positioning

### Name options (pick one that is available on .com / .ai / .io)
- **GTMIntel** — clean, analyst-y, easy to remember
- **The AI GTM Report** — publication framing, signals newsletter intent
- **Signal to Revenue** — evocative, reuses your observability/telemetry language, owns a phrase
- **GTMLens** — analyst + focus metaphor
- **The Harness** — ties to your model-harness-drift work, but may be too niche

**Recommended:** **Signal to Revenue** (or **The AI GTM Report** as the safer B2B-pub play). "Signal to Revenue" is a 2026-native phrase, already used informally in the category, and gives you a podcast/newsletter/report naming system out of the box.

### Tagline options
- "Independent intelligence for the AI-native GTM stack."
- "The analyst's view of AI GTM."
- "Where the GTM stack meets the AI stack."

### Brand voice
- Analyst, not evangelist. Named examples, concrete numbers, architectural diagrams.
- Short, punchy LinkedIn-native posts at the top of the funnel; long, structured reports at the bottom.
- Mirror the tone you already use: numbered sections, specific companies, no fluff.

---

## 4. Site Architecture

A clean, fast, content-led IA. Six top-level nav items. Everything else lives inside them.

```
Home
├─ Market Insights                (the "publication")
│   ├─ Market Maps
│   ├─ Category Deep Dives
│   ├─ Vendor Battle Cards
│   ├─ Funding & M&A Tracker
│   └─ Quarterly "State of AI GTM" reports
├─ Categories & Vendors           (the "directory")
│   ├─ Data & Enrichment          (Clay, Apollo, ZoomInfo, Prospeo, SyncGTM)
│   ├─ Outbound & Sequencing      (Smartlead, Instantly, Outreach, Salesloft, Lemlist)
│   ├─ LinkedIn Automation        (HeyReach, Expandi, Phantombuster)
│   ├─ CRM                        (HubSpot, Salesforce, Attio, Pipedrive)
│   ├─ Intent & Signal            (Warmly, RB2B, Factors.ai, 6sense, Demandbase)
│   ├─ Orchestration / Workflow   (n8n, Make, Zapier, Tray.io)
│   ├─ AI SDR / Agentic Outbound  (11x, Artisan, Jason AI, Regie.ai)
│   ├─ Revenue Intelligence       (Gong, Clari, Chorus, HockeyStack, Spotlight.ai)
│   └─ Lead Capture & Conversion  (HubSpot forms, Tally, Typeform, Default)
├─ Stack Builder                  (the "product")
│   ├─ Pre-Seed / Bootstrapped    (< $500/mo)
│   ├─ Seed Stage                 ($500–$2,000/mo)
│   ├─ Series A                   ($2,000–$10,000/mo)
│   └─ Enterprise                 ($10,000+/mo)
├─ GTM Engineering                (the "education hub")
│   ├─ What is GTM Engineering?
│   ├─ The GTM Engineer's Toolkit
│   ├─ Learning Path              (beginner → advanced)
│   ├─ Playbooks & Workflows
│   ├─ Best Practices
│   └─ Resource Library           (newsletters, communities, courses, podcasts)
├─ Advisory                       (the "agency")
│   ├─ Services
│   ├─ Case Studies / Proof
│   ├─ Process
│   └─ Book a Call
└─ About
    ├─ Manifesto / POV
    ├─ Who We Are
    └─ Press / Media Kit
```

### Key IA decisions explained

- **Market Insights vs. Categories & Vendors split.** Insights is the *flow* — dated, timely, opinion. Categories is the *stock* — evergreen, updated, reference. Google rewards both differently. This structure also lets a reader land from search on "Best outbound sequencing tools" (Categories) or "Why AI SDR 1.0 failed" (Insights) and convert either way.
- **Stack Builder is the lead magnet.** A decision-tree UX ("answer 5 questions, get a recommended stack") converts better than any listicle. Done well, it becomes *the* canonical reference the category links to.
- **Advisory is the conversion page, not the homepage.** The homepage sells the authority; Advisory sells the service. Keep them separate so you can position as analyst-first, consultant-second.
- **GTM Engineering is a standalone hub** because the learner persona is distinct from the buyer persona and has its own search intent.

---

## 5. Content Taxonomy & Data Model

A lightweight custom-post-type structure lets you ship analyst-grade pages without rebuilding templates each time. In WordPress, use **Advanced Custom Fields (ACF)** with a free plugin like **Custom Post Type UI**.

### Custom Post Type: Vendor

Fields on every vendor profile:

| Field | Example |
|---|---|
| Vendor name, logo, URL, HQ, founded, CEO | Clay / New York / 2017 / Kareem Amin |
| Category (taxonomy, multi-select) | Data & Enrichment; Orchestration |
| Funding stage, last round, total raised | Series C, $100M, $3.1B valuation |
| Pricing tier (5-level) | Free / $ / $$ / $$$ / Enterprise |
| Entry price | $149/mo |
| Target segment | Seed → Series B |
| Core capabilities (tag cloud) | Waterfall enrichment, AI agents, HTTP API |
| Integrations (tag cloud) | HubSpot, Salesforce, Smartlead, Slack |
| SWOT block (4 short paragraphs) | — |
| Architecture diagram (image) | — |
| My analyst take (editorial) | — |
| Best fit / Worst fit (two bullets each) | — |
| Alternatives (linked vendors) | — |
| Last updated | April 2026 |

### Custom Post Type: Stack

Each "stack recipe" page:
- Budget tier
- ICP / use case
- Tools (linked to Vendor CPT)
- Monthly cost estimate
- Architecture diagram
- Pros / cons / tradeoffs
- Migration path to the next tier

### Custom Post Type: Insight (standard post works)
- Category
- Publication date
- Hero image
- Related vendors (linked)
- Related category page (linked)

This structure gives you automatic cross-linking: every vendor page shows related insights; every insight shows related vendors; every stack page links to both. That is how you build the internal-link graph that ranks in search.

---

## 6. Content Strategy

### Pillar content (launch with these — non-negotiable for credibility)

1. **The AI GTM Market Map 2026** — a single shareable landscape graphic with all ~120 vendors organized by category, plus a long-form explainer.
2. **The State of GTM Engineering Q2 2026** — quarterly report, 15–20 pages, PDF downloadable behind email.
3. **Stack Builder interactive** — the decision-tree tool, site's crown jewel.
4. **"What is GTM Engineering? (2026 Edition)"** — pillar page targeting the top informational query.
5. **A flagship POV piece** — pick one of your existing threads. *Recommended:* **"The GTM Harness is drifting: how model improvements will collapse 60% of today's GTM tool stack."** Reuses your model-harness-drift thesis, applies it to GTM, gets you immediate LinkedIn virality.

### Content cadence (sustainable, solo-operator)

| Frequency | Asset | Effort |
|---|---|---|
| Weekly | 1 LinkedIn post (link to site) | 1 hr |
| Weekly | 1 short Insight post (600–800 words) | 2 hrs |
| Bi-weekly | 1 Vendor Battle Card | 3 hrs |
| Monthly | 1 long-form analysis (2,000+ words) | 6 hrs |
| Quarterly | 1 State of GTM report | 20 hrs |
| Ongoing | Vendor directory updates | 1 hr/week |

**Total: ~10–12 hrs/week**, which is realistic alongside advisory engagements.

### Content production stack (leveraging your existing Claude workflow)

- **Research:** Claude with web search + past conversation memory (you already have extensive context).
- **Drafting:** Claude Code / Claude artifacts for structured reports.
- **Diagrams:** Claude-generated Mermaid / SVG, or Figma for polished deliverables.
- **Images:** Midjourney or Gamma for hero visuals.
- **Publishing:** WordPress + Yoast SEO.
- **Distribution:** LinkedIn (primary), X (secondary), newsletter (ConvertKit or MailerLite).

---

## 7. Technical Setup — Hostinger + WordPress

### Hostinger plan

**Recommended: Hostinger Cloud Startup or WordPress Pro.**
Avoid the cheapest shared plan — it will hit CPU limits once your vendor directory grows past 100 pages. Cloud Startup gives you ~200K monthly visitors capacity, auto-scaling, built-in staging, object cache, and daily backups. Roughly $8–12/month on a 2-year plan, worth paying for.

If you expect to hit scale fast (over 500K pageviews), look at Cloudways or Kinsta later — but Hostinger is fine for Year 1.

### Theme choice

Pick a **GeneratePress + GenerateBlocks** or **Kadence** setup. These are the two fastest, most analyst-site-friendly themes in 2026. Both:
- Load fast (Core Web Vitals friendly — critical for SEO)
- Work with Gutenberg (avoid Elementor for a content-heavy site; it bloats and slows you down)
- Play nicely with ACF and Custom Post Type UI
- Give you full typography / color control without custom code

**Avoid:** Avada, Divi, heavy multipurpose themes (Litho, Maxiz). They are built for agencies selling to other agencies, not analyst publications. You want the NYT/Stratechery aesthetic, not the enterprise-SaaS-landing-page aesthetic.

**If you want a premium analyst-feel theme out-of-the-box:** look at **Authority Pro (StudioPress)** or **Soledad** — both magazine-grade, fast, SEO-friendly.

### Essential plugin stack (keep this tight — 12 plugins max)

| Plugin | Purpose |
|---|---|
| Yoast SEO or RankMath | On-page SEO |
| Custom Post Type UI | Create Vendor / Stack CPTs |
| Advanced Custom Fields | Structured data fields on vendors |
| GenerateBlocks (or Kadence Blocks) | Advanced layouts in Gutenberg |
| WP Rocket (or LiteSpeed if Hostinger stack supports it) | Caching / speed |
| Cloudflare (as CDN) | Global delivery + DDoS protection |
| Imagify or ShortPixel | Image optimization |
| Wordfence or Solid Security | Security hardening |
| Fluent Forms | Lead capture (better than Contact Form 7, lighter than Gravity) |
| FluentCRM or integrate ConvertKit/MailerLite | Newsletter + sequences |
| Schema Pro (or RankMath Pro) | Review / FAQ / article schema |
| Google Site Kit | Analytics + Search Console in one |

### Performance targets
- Lighthouse score 90+ on mobile.
- LCP under 2.0s.
- Page weight under 1.5 MB on vendor profile pages.

### Analytics
- **Google Analytics 4** via Site Kit (free).
- **Microsoft Clarity** for heatmaps / session recording (free, lightweight).
- **Plausible or Fathom** later if you want privacy-first public-facing numbers.

---

## 8. SEO Strategy

Two intent types, two ranking plays:

### Informational (top of funnel)
Rank for: "what is GTM engineering," "GTM stack for startups," "AI SDR comparison," "Clay alternatives," "[vendor] vs [vendor]."

Approach: pillar pages + vendor comparison pages + definitional content. These drive traffic.

### Transactional (bottom of funnel)
Rank for: "AI GTM consultant," "fractional GTM advisor," "GTM engineering agency," "stealth startup GTM stack."

Approach: Advisory and location-agnostic service pages with strong case studies. These drive leads.

### Quick SEO wins in first 90 days
1. **Do 30 vendor comparison pages** — "X vs Y" queries are very high intent and have low competition in the AI GTM niche.
2. **Internal link aggressively** — every Insight should link to 2–3 vendor pages, every vendor page to 2 insights.
3. **Submit XML sitemap to Google Search Console on Day 1.**
4. **Build backlinks by cross-posting on LinkedIn / Substack with canonical tags**, and pitching guest essays to Factors.ai, Apollo, and Claymation (ironic — they need independent voices).

---

## 9. Advisory Funnel — How the Site Pays

The site is the top of funnel; advisory is the monetization.

### Service offerings (three-tier)

| Tier | Offer | Price range | Target buyer |
|---|---|---|---|
| **GTM Stack Audit** | 2-week engagement, 1 report + 2 calls. "Here's your current stack, what's broken, what to replace." | $3,500–$7,500 | Seed/Series A founder, pre-RevOps hire |
| **GTM Engineering Build** | 6–8 week engagement, build the stack + run one outbound cycle with them | $15,000–$40,000 | Series A/B with 1st SDR hire |
| **Advisory Retainer** | Monthly, fractional GTM advisor, 4–8 hrs/month | $3,000–$7,500/mo | Stealth → Series B CEO |

### Conversion path on the site
1. Visitor lands from SEO or LinkedIn on an Insight or Stack Builder.
2. Inline CTA: "Take the 5-minute GTM Stack Assessment" → email capture + lightweight diagnostic.
3. Automated email sequence (5 emails over 10 days) positioning you as the analyst.
4. Final email: soft pitch for **Stack Audit call**.
5. On-call conversion to Build or Retainer.

The public work **is** the sales pitch. You do not need to "sell." You need to publish, and the right 1% of readers will self-identify.

### Social proof plan for first 6 months
You need 3 named client logos before Advisory conversion rates meaningfully step up. Strategies:
- Turn the stealth startup engagement (the RevOps post context) into a blind case study day 1, named case study when they launch.
- Offer 2 free "pilot" Stack Audits to well-known early-stage founders in your network in exchange for a testimonial and logo rights.
- Host 3 "Office Hours" LinkedIn Lives with notable GTM engineers. Their names on your site = borrowed authority.

---

## 10. 90-Day Build Plan

### Weeks 1–2: Foundation
- Domain + Hostinger setup + SSL.
- WordPress + theme + plugin install.
- Brand identity: logo, color system, typography.
- Publish 1 "Manifesto" post explaining the site's POV.
- Set up analytics, search console, newsletter.
- Deliverable: site is live with a single anchor post.

### Weeks 3–6: Pillar content
- Publish the **AI GTM Market Map 2026** (big splash on LinkedIn).
- Publish **"What is GTM Engineering?"** pillar page (3,000 words).
- Publish **10 vendor profiles** (Clay, Apollo, HubSpot, Smartlead, HeyReach, RB2B, Warmly, Factors.ai, n8n, 11x).
- Publish **4 category overview pages**.
- Ship **Stack Builder v0** (even if it is a basic quiz, not a full tool).
- Deliverable: 15+ pages live, 2 viral LinkedIn moments, 200+ newsletter signups.

### Weeks 7–10: Depth + distribution
- Publish **the flagship POV piece** (harness drift applied to GTM).
- Publish **10 more vendor profiles** + **10 comparison pages**.
- Launch **"State of AI GTM Q2 2026"** gated report.
- Start a **bi-weekly newsletter**.
- Deliverable: 35+ pages, 500+ newsletter signups, 2 inbound advisory inquiries.

### Weeks 11–13: Advisory activation
- Build out Advisory services pages with case studies.
- Host first LinkedIn Live.
- Pitch 5 guest essays.
- Formalize Stack Audit offer + booking page (Calendly or Cal.com).
- Deliverable: first paid Stack Audit closed.

---

## 11. Budget — Year 1

| Item | Cost |
|---|---|
| Hostinger Cloud Startup, 2-year | $240 |
| Domain (.com + .ai) | $100 |
| Theme (GeneratePress Pro or Kadence Pro) | $75 |
| Plugins (RankMath Pro + WP Rocket + Fluent Forms Pro) | $250 |
| Newsletter (ConvertKit/MailerLite, up to 1K) | Free → $30/mo |
| Design tools (Figma free, Canva Pro $13/mo) | $156 |
| Stock imagery / Midjourney | $120 |
| Calendly + Cal.com | Free or $96 |
| Miscellaneous (fonts, icons, backups) | $150 |
| **Total Year 1 infrastructure** | **~$1,200–$1,500** |

Compare to the revenue potential: **one Stack Audit covers it.**

---

## 12. Risks & Mitigations

| Risk | Mitigation |
|---|---|
| **Category moves fast; content ages in months.** | Build the "last updated" field into every Vendor profile; commit to quarterly refresh cycles. |
| **Vendor pushback on critical coverage.** | This is a *feature*, not a bug. Lean into it. Independence is the moat. |
| **Solo-operator content cadence burnout.** | Use Claude heavily for drafting; 10–12 hrs/week is the realistic ceiling. Don't over-commit. |
| **SEO takes 6–12 months to compound.** | Lean on LinkedIn for Year 1 distribution. Your existing audience is the launch channel. |
| **Advisory cannibalization — publishing free what you also sell.** | The public content is the *diagnosis*; the paid work is the *implementation*. Never blur that line. |
| **Perceived conflict of interest if you take vendor money.** | Do not take vendor money. Period. Sponsorships can come later, disclosed. |

---

## 13. What Makes This Different From Every Other GTM Site

Three things, all of which are already true about you and not manufactured for the site:

1. **You came from the observability / AI-infra beat.** Nobody in the GTM analyst space can credibly draw the "telemetry pipelines → signal pipelines → Decision Ops for revenue" line. That cross-domain arc is your unfair advantage, and it will earn inbound from both audiences.
2. **You already produce analyst-grade deliverables.** Battle cards, SWOTs, architectural breakdowns — that is the native format of this site. You do not have to learn a new muscle; you just redirect the muscle you already have.
3. **You are independent by design.** Every major GTM content source sells a product. You sell advice. That is a cleaner business model and a cleaner brand.

---

## 14. First Three Decisions to Make This Week

1. **Pick the brand name and buy the domain.** (Signal to Revenue recommended.)
2. **Commit to the publishing cadence or lower it.** Better to ship 1 post/week forever than 3/week for a month.
3. **Write the Manifesto post first, before any theme or logo work.** It is the document that forces the positioning to cohere, and it is the first thing any visitor should read.

The rest is execution.
