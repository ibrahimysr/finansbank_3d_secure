<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Başarılı - QNB Finansbank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/css/payment.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://www.qnbfinansbank.com/favicon.ico">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="success-card">
                    <div class="icon-success mb-3">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h2 class="fw-bold text-success mb-2">Ödeme Başarılı!</h2>
                    <div class="text-muted mb-4">Ödemeniz başarıyla gerçekleştirildi.</div>
                    @if(isset($response))
                        <div class="payment-details-box mx-auto mb-4">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th>İşlem No</th>
                                    <td>{{ $response->raw['TransactionId'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Sipariş No</th>
                                    <td>{{ $response->raw['OrderId'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tutar</th>
                                    <td>{{ number_format(($response->raw['PurchAmount'] ?? 0) / 100, 2) }} TL</td>
                                </tr>
                            </table>
                        </div>
                    @endif
                    <a href="{{ route('payment.form') }}" class="btn btn-outline-success px-4 py-2 mt-2"><i class="bi bi-arrow-repeat me-1"></i>Yeni Ödeme</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 