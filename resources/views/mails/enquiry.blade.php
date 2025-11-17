@if ($details['type'] == 'enquiry')
    <h1>New Enquiry Received</h1>
    <p>Dear Admin,</p>
    <p>You have received a new enquiry from {{ $details['name'] }}.</p>
    <p>Details:</p>
    <ul>
        <li>Name: {{ $details['name'] }}</li>
        <li>Email: {{ $details['email'] }}</li>
        <li>Mobile: {{ $details['mobile'] }}</li>
        <li>Subject: {{ $details['subject'] }}</li>
        <li>Message: {{ $details['message'] }}</li>
        <li>IP Address: {{ $details['ip_address'] }}</li>
    </ul>
    <p>Thank you for your attention.</p>
    <p>Best regards,</p>
    <p>{{ $details['name'] }}</p>
@elseif ($details['type'] == 'acknowledgment')
    <h1>Enquiry Acknowledgment</h1>
    <p>Dear {{ $details['name'] }},</p>
    <p>Thank you for your enquiry. We have received your message and will get back to you shortly.</p>
    <p>Details:</p>
    <ul>
        <li>Name: {{ $details['name'] }}</li>
        <li>Email: {{ $details['email'] }}</li>
        <li>Mobile: {{ $details['mobile'] }}</li>
        <li>Subject: {{ $details['subject'] }}</li>
        <li>Message: {{ $details['message'] }}</li>
    </ul>
    <p>We appreciate your interest in our services.</p>
    <p>Best regards,</p>
    <p>{{ config('app.name') }}</p>
@endif
