<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>

<body style="margin:0;padding:0;background:#f7f4fb;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <div
        style="max-width:640px;margin:40px auto;background:#ffffff;border:1px solid rgba(17,17,17,0.08);border-radius:24px;overflow:hidden;">
        <div style="background:linear-gradient(135deg,#CB148B,#620A88);padding:24px 28px;color:#ffffff;">
            <h1 style="margin:0;font-size:24px;font-weight:700;">Order Confirmation</h1>
            <p style="margin:8px 0 0;opacity:0.9;">Your child shop order has been placed successfully.</p>
        </div>

        <div style="padding:28px;">
            <p style="margin:0 0 16px;font-size:15px;line-height:1.8;">
                Hello,
            </p>

            <p style="margin:0 0 18px;font-size:15px;line-height:1.8;color:#4b5563;">
                A new shop order has been placed from the child account. The order details are below.
            </p>

            <div style="background:#faf7fc;border:1px solid rgba(17,17,17,0.06);border-radius:18px;padding:18px;">
                <p style="margin:0 0 8px;font-size:14px;"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Total:</strong> {{ number_format($order->total, 2) }}
                </p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Payment Method:</strong>
                    {{ ucfirst($order->payment_method) }}</p>
                <p style="margin:0 0 8px;font-size:14px;"><strong>Shipping Status:</strong>
                    {{ ucfirst($order->shipping_status) }}</p>
                <p style="margin:0;font-size:14px;"><strong>Recipient:</strong> {{ $order->shipping_recipient_name }}
                </p>
            </div>

            <div style="margin-top:18px;">
                <p style="margin:0 0 12px;font-size:15px;font-weight:700;">Items</p>
                @foreach($order->items as $item)
                    <div style="padding:10px 0;border-bottom:1px solid rgba(17,17,17,0.06);font-size:14px;">
                        {{ $item->product_name }} × {{ $item->quantity }} = {{ number_format($item->line_total, 2) }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>