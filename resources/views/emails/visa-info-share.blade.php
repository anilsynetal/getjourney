<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Visa Information Shared</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .header {
            background: linear-gradient(135deg, #003366 0%, #004d99 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            background-color: white;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #eee;
            border-top: none;
        }

        .info-block {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f5f5f5;
            border-left: 4px solid #d32f2f;
            border-radius: 4px;
        }

        .info-block strong {
            color: #003366;
            display: block;
            margin-bottom: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #d32f2f;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }} – Visa Information</h1>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $name }}</strong>,</p>

            <p>Thank you for requesting visa information from {{ config('app.name') }}. We have shared the detailed visa
                information for your selected country below.</p>

            <div class="info-block">
                <strong>Visa Information Details:</strong>
                <p>
                    @if ($visa_category ?? false)
                        <strong>Visa Category:</strong> {{ $visa_category }}<br>
                    @endif
                    @if ($city ?? false)
                        <strong>City:</strong> {{ $city }}<br>
                    @endif
                </p>
            </div>

            <div class="info-block">
                <strong>Recipient Information:</strong>
                <p><strong>Name:</strong> {{ $name }}<br>
                    <strong>Email:</strong> {{ $email }}<br>
                    <strong>Contact Number:</strong> {{ $contact }}<br>
                    <strong>Service Charges:</strong> {{ $service_charges }}
                </p>
            </div>

            @if ($additional_info)
                <div class="info-block">
                    <strong>Additional Information:</strong>
                    <p>{{ $additional_info }}</p>
                </div>
            @endif

            <p><strong>Important Notes:</strong></p>
            <ul>
                <li>Please review the complete visa information carefully</li>
                <li>For document requirements, refer to the mandatory documents section</li>
                <li>Processing times may vary based on your specific circumstances</li>
                <li>Contact us for any clarifications or additional assistance</li>
            </ul>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ route('website.visa-information') }}" class="button">View Full Visa Information</a>
            </p>

            <p>If you have any questions or need further assistance, please don't hesitate to contact us.</p>

            <p>Best regards,<br>
                <strong>{{ config('app.name') }} Team</strong>
            </p>

            <div class="footer">
                <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                    This email is automatically generated. Please do not reply directly to this email.</p>
            </div>
        </div>
    </div>
</body>

</html>
