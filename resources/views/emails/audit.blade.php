<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body { margin: 0; padding: 0; background: #f1f4f9; font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
  .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 40px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #d97706, #f59e0b); padding: 40px 36px; text-align: center; }
  .header-icon { font-size: 3rem; display: block; margin-bottom: 12px; }
  .header h1 { color: #fff; margin: 0; font-size: 1.5rem; font-weight: 700; letter-spacing: -0.5px; }
  .header p { color: rgba(255,255,255,0.75); margin: 6px 0 0; font-size: 14px; }
  .body { padding: 36px; }
  .field { margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; }
  .field:last-child { border-bottom: none; margin-bottom: 0; }
  .field-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 5px; }
  .field-value { font-size: 15px; color: #1e293b; line-height: 1.6; }
  .message-box { background: #f8fafc; border-radius: 12px; padding: 20px; border-left: 4px solid #d97706; }
  .chip { display: inline-block; background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 100px; font-size: 12px; font-weight: 600; margin: 0 6px 6px 0; }
  .cta { text-align: center; padding: 28px 36px; background: #f8fafc; border-top: 1px solid #e8ecf0; }
  .btn { display: inline-block; background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 14px; text-decoration: none; }
  .footer { text-align: center; padding: 20px; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <span class="header-icon">🔍</span>
    <h1>New Free Audit Request</h1>
    <p>Someone wants a free website audit</p>
  </div>
  <div class="body">
    <div class="field">
      <div class="field-label">From</div>
      <div class="field-value" style="font-weight:700;font-size:1.1rem">{{ $lead->name }}</div>
    </div>
    <div class="field">
      <div class="field-label">Email</div>
      <div class="field-value"><a href="mailto:{{ $lead->email }}" style="color:#d97706;font-weight:600">{{ $lead->email }}</a></div>
    </div>
    @if($lead->phone)
    <div class="field">
      <div class="field-label">Phone</div>
      <div class="field-value"><a href="tel:{{ $lead->phone }}" style="color:#d97706">{{ $lead->phone }}</a></div>
    </div>
    @endif
    @if($lead->website_url)
    <div class="field">
      <div class="field-label">Website to Audit</div>
      <div class="field-value"><a href="{{ $lead->website_url }}" target="_blank" style="color:#d97706;font-weight:600">{{ $lead->website_url }}</a></div>
    </div>
    @endif
    @if($lead->business_type)
    <div class="field">
      <div class="field-label">Business Type</div>
      <div class="field-value">{{ $lead->business_type }}</div>
    </div>
    @endif
    @if($lead->budget_range)
    <div class="field">
      <div class="field-label">Budget Range</div>
      <div class="field-value">{{ $lead->budget_range }}</div>
    </div>
    @endif
    @if($lead->services_needed && count((array) $lead->services_needed))
    <div class="field">
      <div class="field-label">Services Needed</div>
      <div class="field-value">
        @foreach((array) $lead->services_needed as $service)
        <span class="chip">{{ $service }}</span>
        @endforeach
      </div>
    </div>
    @endif
    @if($lead->goals)
    <div class="field">
      <div class="field-label">Goals & Focus Areas</div>
      <div class="message-box">{{ $lead->goals }}</div>
    </div>
    @endif
    <div class="field">
      <div class="field-label">Received</div>
      <div class="field-value">{{ $lead->created_at->format('F d, Y \a\t H:i') }}</div>
    </div>
  </div>
  <div class="cta">
    <p style="color:#64748b;font-size:14px;margin-bottom:16px">Review and respond to this request from your admin panel</p>
    <a href="{{ url('/admin/audit-leads') }}" class="btn">View in Admin Panel →</a>
  </div>
  <div class="footer">This email was sent from your SK Artistic website's Free Audit form.</div>
</div>
</body>
</html>
