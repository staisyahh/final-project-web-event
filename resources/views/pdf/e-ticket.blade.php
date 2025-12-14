<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #000;
        }
        .header p {
            margin: 5px 0 0;
            color: #777;
        }
        .content {
            margin-bottom: 30px;
        }
        .content h2 {
            font-size: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .event-details, .attendee-details {
            margin-bottom: 20px;
        }
        .event-details p, .attendee-details p {
            margin: 5px 0;
            line-height: 1.6;
        }
        .event-details strong {
            display: inline-block;
            width: 120px;
            color: #555;
        }
        .ticket-info {
            text-align: center;
            margin-top: 30px;
        }
        .ticket-info img {
            max-width: 200px;
            margin-bottom: 10px;
        }
        .ticket-info .ticket-code {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
            font-family: monospace;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>E-Ticket</h1>
            <p>This is your official electronic ticket.</p>
        </div>

        <div class="content">
            <h2>{{ $event->title }}</h2>

            <div class="event-details">
                <p><strong>Organizer:</strong> {{ $event->organizer->name }}</p>
                <p><strong>Date & Time:</strong> {{ $event->jadwal->format('l, d F Y - H:i T') }}</p>
                <p><strong>Location:</strong> {{ $event->location_name }}</p>
                <p><strong>Address:</strong> {{ $event->location_address }}</p>
            </div>

            <div class="attendee-details">
                <p><strong>Ticket For:</strong> {{ $user->name }}</p>
                <p><strong>Ticket Type:</strong> {{ $ticket->name }}</p>
            </div>

            <div class="ticket-info">
                {{-- QR Code will be embedded here --}}
                <img src="{{ $qrCodePath }}" alt="QR Code">
                <p class="ticket-code">{{ $eTicket->ticket_code }}</p>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for registering. Please present this e-ticket at the venue.</p>
            <p>&copy; {{ date('Y') }} VentNice Event. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
