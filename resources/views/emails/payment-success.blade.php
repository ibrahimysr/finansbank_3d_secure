<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ödeme Onayı</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success-icon {
            color: #28a745;
            font-size: 48px;
            margin-bottom: 10px;
        }
        .details {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        .details td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="success-icon">✓</div>
        <h1>Ödemeniz Başarıyla Alındı</h1>
    </div>

    <div class="details">
        <p>Sayın {{ $payment->card_holder_name }},</p>
        <p>Ödemeniz başarıyla gerçekleştirilmiştir. İşlem detayları aşağıda yer almaktadır:</p>

        <table>
            <tr>
                <td>Sipariş Numarası</td>
                <td>{{ $payment->order_id }}</td>
            </tr>
            <tr>
                <td>İşlem Numarası</td>
                <td>{{ $payment->transaction_id }}</td>
            </tr>
            <tr>
                <td>Ödeme Tutarı</td>
                <td>{{ number_format($payment->amount, 2) }} TL</td>
            </tr>
            <tr>
                <td>Ödeme Tarihi</td>
                <td>{{ $payment->created_at->format('d.m.Y H:i:s') }}</td>
            </tr>
            <tr>
                <td>Kart Bilgisi</td>
                <td>**** **** **** {{ $payment->card_number }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px;">
            Bu e-posta, ödemenizin onayı olarak otomatik olarak gönderilmiştir. 
            Herhangi bir sorunuz olması durumunda bizimle iletişime geçebilirsiniz.
        </p>
    </div>

    <div class="footer">
        <p>Bu e-posta {{ config('app.name') }} tarafından gönderilmiştir.</p>
        <p>© {{ date('Y') }} Tüm hakları saklıdır.</p>
    </div>
</body>
</html> 