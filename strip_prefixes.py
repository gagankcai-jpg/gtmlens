import re, json, requests
from requests.auth import HTTPBasicAuth

AUTH = HTTPBasicAuth('gaganklchawla@gmail.com', 'I8Sx GrmZ Ootb zw1u 3lXL 3xy8')
BASE = 'https://gtmlens.com/wp-json/wp/v2/comparison'

def strip(content):
    # Remove leading "<p><strong>Choose X if:</strong> " (only the very first occurrence)
    return re.sub(r'^<p><strong>Choose [^<]+ if:</strong>\s*', '<p>', content, count=1)

for slug in ['clay-vs-apollo', 'smartlead-vs-instantly']:
    r = requests.get(BASE, params={'slug': slug, 'context': 'edit', '_fields': 'id,acf'}, auth=AUTH)
    item = r.json()[0]
    pid = item['id']
    acf = dict(item['acf'])
    acf['decision_rule_a'] = strip(acf['decision_rule_a'])
    acf['decision_rule_b'] = strip(acf['decision_rule_b'])
    a = acf['decision_rule_a']; b = acf['decision_rule_b']
    # Coerce vendor objects to IDs for required fields
    for k in ('vendor_a','vendor_b','vendor_c'):
        v = acf.get(k)
        if isinstance(v, dict):
            acf[k] = v.get('ID') or v.get('id')
    payload = {'acf': acf}
    u = requests.post(f'{BASE}/{pid}', json=payload, auth=AUTH)
    print(slug, u.status_code, u.text[:200] if u.status_code != 200 else 'OK')
    print('  rule_a starts:', a[:80])
    print('  rule_b starts:', b[:80])
