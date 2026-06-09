import requests
from requests.auth import HTTPBasicAuth

AUTH = HTTPBasicAuth('gaganklchawla@gmail.com', 'I8Sx GrmZ Ootb zw1u 3lXL 3xy8')
URL = 'https://gtmlens.com/wp-json/wp/v2/comparison/186'

new_verdict = (
    "n8n wins on cost and flexibility for dev-adjacent GTM teams willing to self-host; "
    "Zapier wins on integration breadth and zero-setup accessibility — but at a price premium "
    "that becomes indefensible above 5,000 tasks per month."
)

new_rule_b = (
    "- Your automation owner is a non-technical operator and self-hosting infrastructure is not viable "
    "— Zapier's zero-setup, cloud-hosted model is the rational default despite its pricing premium.\n"
    "- You need integrations with niche enterprise apps (specific HRIS, finance, or industry-specific "
    "platforms) that are only available in Zapier's 7,000-connector catalog and not in n8n's 400-connector library.\n"
    "- Your task volume is under 5,000 per month and the cost difference between the two platforms is "
    "immaterial relative to the time cost of migration and re-training.\n"
    "- You are in a Zapier-standardized organization where your team's existing Zap library represents "
    "months of institutional knowledge — the switching cost of migrating to n8n exceeds the pricing savings at current scale."
)

# Pull current acf
r = requests.get(URL, params={'context': 'edit', '_fields': 'acf'}, auth=AUTH)
acf = r.json()['acf']
acf['verdict'] = new_verdict
acf['decision_rule_b'] = new_rule_b
acf['vendor_c'] = None  # explicitly clear

payload = {
    'title': 'n8n vs Zapier',
    'slug': 'n8n-vs-zapier',
    'acf': acf,
}

u = requests.post(URL, json=payload, auth=AUTH)
print(u.status_code, u.text[:300] if u.status_code != 200 else 'OK')
