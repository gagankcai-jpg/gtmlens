#!/usr/bin/env python3
"""Rebalance stacks: fix Series A, add Series B + Series C."""
import requests
from requests.auth import HTTPBasicAuth

AUTH = HTTPBasicAuth('gaganklchawla@gmail.com', 'I8Sx GrmZ Ootb zw1u 3lXL 3xy8')
BASE = 'https://gtmlens.com/wp-json/wp/v2'

# Vendor IDs
V = {
    'clay': 63, 'smartlead': 67, 'apollo': 72, 'hubspot': 74, 'rb2b': 83,
    'artisan': 85, 'n8n': 86, 'salesforce': 88, 'zoominfo': 89, 'outreach': 90,
    '6sense': 91, 'gong': 92, '11x': 93, 'heyreach': 98, 'warmly': 100,
    'factors-ai': 101, 'attio': 144, 'hockeystack': 152, 'demandbase': 155,
}

STACK_IDS = {
    'pre-seed-bootstrap-stack': 82,
    'seed-stack': 84,
    'series-a-stack': 87,
    'enterprise-stack': 94,
}


def normalize_acf(acf):
    """Coerce relationship/post-object fields to ID arrays/scalars for re-POST."""
    out = dict(acf or {})
    if isinstance(out.get('tools'), list):
        out['tools'] = [t.get('ID') if isinstance(t, dict) else t for t in out['tools']]
    mp = out.get('migration_path_to_next_tier')
    if isinstance(mp, dict):
        out['migration_path_to_next_tier'] = mp.get('ID') or mp.get('id')
    elif isinstance(mp, list) and mp:
        first = mp[0]
        out['migration_path_to_next_tier'] = first.get('ID') if isinstance(first, dict) else first
    return out


def get_acf(post_id):
    r = requests.get(f'{BASE}/stack/{post_id}?context=edit', auth=AUTH)
    r.raise_for_status()
    return normalize_acf(r.json().get('acf') or {})


def update_stack(post_id, patch):
    acf = get_acf(post_id)
    acf.update(patch)
    r = requests.post(f'{BASE}/stack/{post_id}', auth=AUTH, json={'acf': acf})
    if r.status_code >= 400:
        print('  ✗', r.status_code, r.text[:400])
        r.raise_for_status()
    print(f'  ✔ updated {post_id}')


def create_stack(slug, title, acf, content=''):
    r = requests.post(f'{BASE}/stack', auth=AUTH, json={
        'slug': slug, 'title': title, 'status': 'publish',
        'content': content, 'acf': acf,
    })
    if r.status_code >= 400:
        print('  ✗', r.status_code, r.text[:400])
        r.raise_for_status()
    pid = r.json()['id']
    print(f'  ✔ created {slug} (id={pid})')
    return pid


# ---------- 1) Fix Series A ----------
# Drop Artisan ($6K — misplaced for Series A budget). Add HeyReach + Factors.ai + Warmly + Apollo
# Tools: Clay, Smartlead, HubSpot, RB2B, Apollo, HeyReach, Factors.ai, Warmly, n8n
print('1) Fixing Series A composition...')
update_stack(STACK_IDS['series-a-stack'], {
    'budget_tier': 'series-a',
    'tools': [V['clay'], V['smartlead'], V['hubspot'], V['rb2b'],
              V['apollo'], V['heyreach'], V['factors-ai'], V['warmly'], V['n8n']],
    'monthly_cost_estimate': '4000',
    'icp_use_case': 'Series A B2B SaaS scaling from $1M to $5M ARR. 2–4 SDRs, AE-led close motion, single ICP, multi-channel outbound (email + LinkedIn) with deanonymization on inbound, plus a revenue-intelligence layer for pipeline forecasting.',
})

# ---------- 2) Create Series B ----------
print('\n2) Creating Series B stack...')
sb_id = create_stack(
    slug='series-b-stack',
    title='Series B Stack',
    acf={
        'budget_tier': 'series-b',
        'tools': [V['clay'], V['smartlead'], V['hubspot'], V['rb2b'],
                  V['apollo'], V['heyreach'], V['factors-ai'],
                  V['11x'], V['n8n']],
        'monthly_cost_estimate': '10000',
        'icp_use_case': 'Series B B2B SaaS scaling from $5M to $15M ARR. 5–10 SDRs plus an AI-SDR layer (Claude-powered) running tier-2 accounts, mid-market ICP expansion, dedicated revenue ops. CRM still HubSpot Pro; orchestration handled in n8n.',
    },
    content='<p>The Series B stack adds the AI-SDR layer that pre-Series-A teams cannot economically justify. The same Clay + Smartlead + HubSpot core scales up; 11x or Artisan handles tier-2 outbound at machine throughput while human SDRs move up to tier-1 named accounts. Factors.ai becomes load-bearing as the revenue ops function matures.</p>',
)

# ---------- 3) Create Series C ----------
print('\n3) Creating Series C stack...')
sc_id = create_stack(
    slug='series-c-stack',
    title='Series C Stack',
    acf={
        'budget_tier': 'series-c',
        'tools': [V['hubspot'], V['outreach'], V['clay'], V['zoominfo'],
                  V['gong'], V['factors-ai'], V['11x'],
                  V['rb2b'], V['n8n']],
        'monthly_cost_estimate': '17000',
        'icp_use_case': 'Series C B2B SaaS at $15M–$50M ARR moving up-market. 10–25 SDRs, multiple AEs per segment, enterprise pilot motion alongside mid-market self-serve. Sequencer migrates from Smartlead to Outreach; data layer adds ZoomInfo for enterprise contact coverage; Gong for call-intelligence-driven coaching.',
    },
    content='<p>Series C is the migration tier. The HubSpot/Smartlead/Apollo seed core gives way to enterprise-grade infrastructure: Outreach for compliant multi-channel sequencing, ZoomInfo for fortune-1000 contact data, Gong for call intelligence and revenue-ops feedback loops. Clay stays as the orchestration glue — it handles the long tail of enrichment and routing logic that the heavyweight platforms don\'t cover well.</p>',
)

# ---------- 4) Wire migration paths ----------
print('\n4) Wiring migration_path_to_next_tier...')
update_stack(STACK_IDS['series-a-stack'], {'migration_path_to_next_tier': sb_id})
update_stack(sb_id, {'migration_path_to_next_tier': sc_id})
update_stack(sc_id, {'migration_path_to_next_tier': STACK_IDS['enterprise-stack']})

print('\nDone.')
