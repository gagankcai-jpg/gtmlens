/**
 * GTMLens Stack Builder — Alpine.js v3 component
 * No build step required. Enqueue Alpine.js v3 from CDN before this file.
 *
 * Usage: <div x-data="stackQuiz()" x-init="init()">...</div>
 * Or via shortcode [stack_quiz] which outputs the wrapper div.
 *
 * Deep-link: /stack-finder/?stage=seed&motion=outbound&icp=smb&team=1-3&budget=500-2000
 * Email capture posts to /wp-json/fluentform/v1/forms/__FORM_ID__/submissions
 * Replace __FORM_ID__ with your actual Fluent Forms form ID.
 */

function stackQuiz() {
  return {
    // ── State ──────────────────────────────────────────────────────────────
    step: 0,          // 0-4 = questions, 5 = results
    answers: {
      stage: null,
      motion: null,
      icp: null,
      team: null,
      budget: null,
    },
    stack: [],         // [{category, vendor, price, url, name, tier}]
    totalCost: 0,
    rulesData: null,
    loading: true,
    error: null,
    shareUrl: '',
    emailForm: { value: '', submitted: false, error: null, submitting: false },
    copied: false,

    // ── Questions definition ───────────────────────────────────────────────
    questions: [
      {
        key: 'stage',
        label: 'What is your company stage?',
        hint: 'This shapes which vendor categories make sense for you.',
        options: [
          { value: 'pre-seed', label: 'Pre-Seed / Bootstrap', desc: 'Pre-product or early revenue, tight budget' },
          { value: 'seed', label: 'Seed', desc: 'Proving GTM motion, first hires' },
          { value: 'series-a', label: 'Series A', desc: '$1M–$5M ARR, repeatable outbound motion' },
          { value: 'series-b', label: 'Series B', desc: '$5M–$15M ARR, AI-SDR layer comes online' },
          { value: 'series-c', label: 'Series C', desc: '$15M–$50M ARR, moving up-market' },
          { value: 'enterprise', label: 'Enterprise', desc: 'Late stage / public, enterprise-grade infra' },
        ],
      },
      {
        key: 'motion',
        label: 'What is your primary GTM motion?',
        hint: 'Some tool categories only make sense for certain motions.',
        options: [
          { value: 'outbound', label: 'Outbound', desc: 'Cold email, cold calling, LinkedIn prospecting' },
          { value: 'inbound', label: 'Inbound', desc: 'SEO, content, paid — leads come to you' },
          { value: 'plg', label: 'Product-Led Growth', desc: 'Free trial / freemium drives pipeline' },
          { value: 'hybrid', label: 'Hybrid', desc: 'Mix of outbound and inbound/PLG' },
        ],
      },
      {
        key: 'icp',
        label: 'What is your target ICP size?',
        hint: 'This affects which enrichment and intent data sources are worth the cost.',
        options: [
          { value: 'smb', label: 'SMB', desc: '1–200 employees' },
          { value: 'mid-market', label: 'Mid-Market', desc: '200–2,000 employees' },
          { value: 'enterprise', label: 'Enterprise', desc: '2,000+ employees' },
        ],
      },
      {
        key: 'team',
        label: 'How big is your GTM team?',
        hint: 'Seat-based tools scale cost quickly. This narrows sensible options.',
        options: [
          { value: 'founder-led', label: 'Founder-Led', desc: 'Just the founders selling' },
          { value: '1-3', label: '1–3 reps', desc: 'Small team, lots of manual work' },
          { value: '4-10', label: '4–10 reps', desc: 'Process and tooling starting to matter' },
          { value: '10+', label: '10+ reps', desc: 'Tooling ROI is material' },
        ],
      },
      {
        key: 'budget',
        label: 'What is your monthly GTM tool budget?',
        hint: 'Total spend across the recommended stack will be checked against this ceiling.',
        options: [
          { value: '<500', label: 'Under $500/mo', desc: 'Bootstrap / extreme constraint' },
          { value: '500-2000', label: '$500–$2,000/mo', desc: 'Seed-stage typical' },
          { value: '2000-6000', label: '$2,000–$6,000/mo', desc: 'Series A range' },
          { value: '6000-12000', label: '$6,000–$12,000/mo', desc: 'Series B range' },
          { value: '12000-20000', label: '$12,000–$20,000/mo', desc: 'Series C range' },
          { value: '20000+', label: '$20,000+/mo', desc: 'Late stage / enterprise' },
        ],
      },
    ],

    // ── Init ──────────────────────────────────────────────────────────────
    async init() {
      try {
        // Resolve JSON path — look for wp_localized data or fall back to relative path
        const jsonPath =
          (window.stackQuizData && window.stackQuizData.rulesUrl) ||
          '/wp-content/themes/gtmlens-child/assets/data/stack-rules.json';

        const res = await fetch(jsonPath);
        if (!res.ok) throw new Error(`Failed to load rules: ${res.status}`);

        // Strip JS-style comments before parsing (the JSON file uses // comments for readability)
        const raw = await res.text();
        const stripped = raw.replace(/\/\/[^\n]*/g, '').replace(/\/\*[\s\S]*?\*\//g, '');
        this.rulesData = JSON.parse(stripped);
      } catch (e) {
        this.error = 'Could not load stack rules. Please refresh the page.';
        console.error('[StackQuiz]', e);
        this.loading = false;
        return;
      }

      // Hydrate from URL query string (deep-link support)
      const params = new URLSearchParams(window.location.search);
      const keys = ['stage', 'motion', 'icp', 'team', 'budget'];
      let hydrated = 0;
      keys.forEach((k) => {
        const v = params.get(k);
        if (v) {
          this.answers[k] = v;
          hydrated++;
        }
      });

      this.loading = false;

      // If all 5 answers are present in URL, jump straight to results
      if (hydrated === 5) {
        this.evaluate();
      }
    },

    // ── Navigation ────────────────────────────────────────────────────────
    get currentQuestion() {
      return this.questions[this.step] || null;
    },

    get progressPercent() {
      return this.step >= 5 ? 100 : Math.round((this.step / this.questions.length) * 100);
    },

    select(value) {
      if (!this.currentQuestion) return;
      this.answers[this.currentQuestion.key] = value;
    },

    canAdvance() {
      if (this.step >= 5) return false;
      return !!this.answers[this.currentQuestion.key];
    },

    advance() {
      if (!this.canAdvance()) return;
      if (this.step < this.questions.length - 1) {
        this.step++;
      } else {
        this.evaluate();
      }
    },

    back() {
      if (this.step > 0 && this.step < 5) {
        this.step--;
      }
    },

    restart() {
      this.step = 0;
      this.answers = { stage: null, motion: null, icp: null, team: null, budget: null };
      this.stack = [];
      this.totalCost = 0;
      this.shareUrl = '';
      this.emailForm = { value: '', submitted: false, error: null, submitting: false };
      // Clear query string without reload
      history.replaceState({}, '', window.location.pathname);
    },

    // ── Rule evaluation ───────────────────────────────────────────────────
    evaluate() {
      const { vendors, rules } = this.rulesData;
      const a = this.answers;
      const categories = [
        'data-enrichment',
        'outbound',
        'linkedin-automation',
        'crm',
        'intent-signal',
        'orchestration',
        'ai-sdr',
        'revenue-intelligence',
        'lead-capture',
      ];

      const resultStack = [];
      let total = 0;

      categories.forEach((cat) => {
        const catRules = rules.filter((r) => r.category === cat);
        let picked = undefined;

        for (const rule of catRules) {
          if (this._ruleMatches(rule, a)) {
            picked = rule.pick; // null means explicitly omit
            break;
          }
        }

        // undefined = no rule matched at all → skip category
        if (picked === undefined || picked === null) return;

        const v = vendors[picked];
        if (!v) return;

        const price = v.entryPrice || 0;
        resultStack.push({
          category: cat,
          categoryLabel: this._catLabel(cat),
          vendorSlug: picked,
          name: v.name,
          price,
          tier: v.tier,
          url: v.url,
          pricingNote: v.pricingNote || null,
        });
        total += price;
      });

      this.stack = resultStack;
      this.totalCost = total;
      this.step = 5;

      // Build and push shareable URL
      const params = new URLSearchParams({
        stage: a.stage,
        motion: a.motion,
        icp: a.icp,
        team: a.team,
        budget: a.budget,
      });
      const url = `${window.location.origin}/stack-finder/?${params.toString()}`;
      this.shareUrl = url;
      history.replaceState({}, '', `?${params.toString()}`);
    },

    _ruleMatches(rule, answers) {
      const cond = rule.if;
      if (!cond || Object.keys(cond).length === 0) return true; // catch-all

      for (const [key, allowed] of Object.entries(cond)) {
        if (!Array.isArray(allowed)) continue;
        if (!allowed.includes(answers[key])) return false;
      }
      return true;
    },

    _catLabel(slug) {
      const labels = {
        'data-enrichment': 'Data Enrichment',
        'outbound': 'Outbound Sequencing',
        'linkedin-automation': 'LinkedIn Automation',
        'crm': 'CRM',
        'intent-signal': 'Intent Signal',
        'orchestration': 'Orchestration / Automation',
        'ai-sdr': 'AI SDR',
        'revenue-intelligence': 'Revenue Intelligence',
        'lead-capture': 'Lead Capture',
      };
      return labels[slug] || slug;
    },

    // ── Share ─────────────────────────────────────────────────────────────
    async copyShareUrl() {
      // Try modern clipboard API first
      if (navigator.clipboard && window.isSecureContext) {
        try {
          await navigator.clipboard.writeText(this.shareUrl);
          this.copied = true;
          setTimeout(() => { this.copied = false; }, 2500);
          return;
        } catch (e) {
          console.warn('[StackQuiz] clipboard.writeText failed, falling back:', e);
        }
      }
      // Fallback: select the visible input so the user can Cmd/Ctrl+C
      const input = document.getElementById('gl-quiz-share-url');
      if (input) {
        input.focus();
        input.select();
        try {
          document.execCommand('copy');
          this.copied = true;
          setTimeout(() => { this.copied = false; }, 2500);
        } catch {
          // Last resort: leave it selected; the user can Cmd/Ctrl+C themselves
        }
      }
    },

    // ── Email capture ──────────────────────────────────────────────────────
    validateEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },

    async submitEmail() {
      if (!this.validateEmail(this.emailForm.value)) {
        this.emailForm.error = 'Please enter a valid email address.';
        return;
      }
      this.emailForm.error = null;
      this.emailForm.submitting = true;

      const payload = {
        email: this.emailForm.value,
        stage: this.answers.stage || '',
        motion: this.answers.motion || '',
        icp: this.answers.icp || '',
        team: this.answers.team || '',
        budget: this.answers.budget || '',
        stack_url: this.shareUrl,
      };

      try {
        const res = await fetch('/wp-json/gtmlens/v1/quiz-subscribe', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload),
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
          throw new Error(data && data.message ? data.message : `HTTP ${res.status}`);
        }
        this.emailForm.submitted = true;
      } catch (e) {
        this.emailForm.error = (e && e.message) || 'Submission failed. Please try again or email info@gtmlens.com.';
        console.error('[StackQuiz] Email submit failed:', e);
      } finally {
        this.emailForm.submitting = false;
      }
    },
  };
}
