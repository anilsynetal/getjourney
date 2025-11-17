@if ($details['type'] == 'booking-accepted')
    <h1>Booking Accepted</h1>
    <p>Dear {{ $details['name'] }},</p>
    <p>Your booking has been accepted.</p>
    <p>Details:</p>
    <ul>
        <li>Booking ID: #{{ $details['booking_id'] }}</li>
        <li>Date: {{ $details['date'] }}</li>
        <li>Time: {{ $details['time'] }}</li>
        <li>Doctor Name: {{ $details['doctor_name'] }}</li>
    </ul>
@elseif ($details['type'] == 'booking-rejected')
    <h1>Booking Rejected</h1>
    <p>Dear {{ $details['name'] }},</p>
    <p>Your booking has been rejected.</p>
    <p>Details:</p>
    <ul>
        <li>Booking ID: #{{ $details['booking_id'] }}</li>
        <li>Date: {{ $details['date'] }}</li>
        <li>Time: {{ $details['time'] }}</li>
        <li>Doctor Name: {{ $details['doctor_name'] }}</li>
    </ul>
@endif
<p>Thank you for choosing our service.</p>
<p>Best regards,</p>
<p>{{ config('app.name') }}</p>
