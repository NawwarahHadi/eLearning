<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $payment->order_id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; background: #fff; }
        .receipt { max-width: 700px; margin: 30px auto; padding: 40px; border: 1px solid #e0e0e0; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
        .company-name { font-size: 22px; font-weight: bold; color: #1a56db; }
        .company-sub { font-size: 12px; color: #666; margin-top: 3px; }
        .receipt-title { text-align: right; }
        .receipt-title h2 { font-size: 20px; color: #333; font-weight: bold; }
        .receipt-title p { font-size: 12px; color: #666; margin-top: 3px; }

        .status-banner { padding: 12px 20px; border-radius: 6px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
        .status-paid { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }
        .status-pending { background: #fffbeb; border: 1px solid #fcd34d; color: #92400e; }
        .status-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
        .dot-paid { background: #22c55e; }
        .dot-pending { background: #f59e0b; }

        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 20px 0; }

        .two-col { display: flex; gap: 40px; margin-bottom: 20px; }
        .col { flex: 1; }
        .label { font-size: 11px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
        .value { font-size: 13px; color: #111; font-weight: 600; }

        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        thead th { background: #f9fafb; padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
        tbody td { padding: 10px 12px; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
        tfoot td { padding: 10px 12px; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background: #f0f9ff; border-top: 2px solid #bae6fd; }
        .total-amount { color: #1a56db; font-size: 15px; }

        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
        .footer-note { font-size: 11px; color: #9ca3af; }
        .powered { font-size: 11px; color: #9ca3af; }

        .print-btn { text-align: center; margin: 20px 0; }
        .print-btn button { background: #1a56db; color: white; border: none; padding: 10px 30px; border-radius: 6px; cursor: pointer; font-size: 14px; margin: 0 5px; }
        .print-btn .btn-outline { background: white; color: #1a56db; border: 1px solid #1a56db; }

        @media print {
            .print-btn { display: none; }
            body { margin: 0; }
            .receipt { border: none; margin: 0; }
        }
    </style>
</head>
<body>

<div class="print-btn">
    <button onclick="window.print()">🖨️ Print Receipt</button>
    <button class="btn-outline" onclick="window.history.back()">← Back</button>
</div>

<div class="receipt">

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="company-name">Al-Amin Tuition Centre</div>
            <div class="company-sub">Online Tuition Management System</div>
            <div class="company-sub">Email: alamin@tuition.com</div>
        </div>
        <div class="receipt-title">
            <h2>PAYMENT RECEIPT</h2>
            <p>Order ID: <strong>{{ $payment->order_id }}</strong></p>
            <p>Date: {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y') }}</p>
        </div>
    </div>

    {{-- Status Banner --}}
    @if($payment->status === 'paid')
    <div class="status-banner status-paid">
        <span class="status-dot dot-paid"></span>
        <strong>Payment Successful</strong> —
        Paid on {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y, h:i A') }}
    </div>
    @else
    <div class="status-banner status-pending">
        <span class="status-dot dot-pending"></span>
        <strong>Payment Pending</strong> — Not yet completed
    </div>
    @endif

    <hr class="divider">

    {{-- Billing Info --}}
    <div class="two-col">
        <div class="col">
            <div class="label">Bill To</div>
            <div class="value">{{ $payment->student->name ?? 'N/A' }}</div>
            <div style="color:#666; font-size:12px; margin-top:3px;">{{ $payment->student->email ?? '' }}</div>
        </div>
        <div class="col">
            <div class="label">Subject</div>
            <div class="value">{{ $payment->enrollment->class->subject->name ?? 'N/A' }}</div>
            <div style="color:#666; font-size:12px; margin-top:3px;">
                Class Code: {{ $payment->enrollment->class->category_code ?? '' }}
            </div>
        </div>
        <div class="col">
            <div class="label">Tutor</div>
            <div class="value">{{ $payment->enrollment->tutor->name ?? 'N/A' }}</div>
            <div style="color:#666; font-size:12px; margin-top:3px;">Payment via FPX</div>
        </div>
    </div>

    <hr class="divider">

    {{-- Session Breakdown --}}
    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Time</th>
                <th class="text-center">Duration</th>
                <th class="text-right">Fee (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($breakdown as $slot)
            <tr>
                <td>{{ $slot['day'] }}</td>
                <td>{{ $slot['start_time'] }} – {{ $slot['end_time'] }}</td>
                <td class="text-center">{{ $slot['hours'] }} hour(s)</td>
                <td class="text-right">RM {{ number_format($slot['fee'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2"><strong>Total</strong></td>
                <td class="text-center"><strong>{{ $totalHours }} hour(s)</strong></td>
                <td class="text-right total-amount">RM {{ number_format($payment->amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <hr class="divider">

    {{-- Transaction Details --}}
    <div class="two-col">
        <div class="col">
            <div class="label">Bill Code</div>
            <div class="value">{{ $payment->bill_code ?? '—' }}</div>
        </div>
        <div class="col">
            <div class="label">Order Reference</div>
            <div class="value">{{ $payment->order_id }}</div>
        </div>
        <div class="col">
            <div class="label">Payment Method</div>
            <div class="value">FPX Online Banking</div>
        </div>
    </div>

    <hr class="divider">

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-note">
            Thank you for your payment to Al-Amin Tuition Centre.<br>
            For enquiries, please contact us with your Order ID.
        </div>
        <div class="powered">
            Powered by ToyyibPay
        </div>
    </div>

</div>

</body>
</html>
