<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Başarısız - QNB Finansbank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/css/payment.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://www.qnbfinansbank.com/favicon.ico">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="fail-card">
                    <div class="icon-fail mb-3">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <h2 class="fw-bold text-danger mb-2">Ödeme Başarısız</h2>
                    <div class="text-muted mb-3">{{ $error ?? 'Ödeme işlemi başarısız oldu.' }}</div>
                    @if(isset($details))
                        <div class="payment-details-box mx-auto mb-4">
                            <table class="table table-borderless mb-0">
                                @if(isset($details['OrderId']))
                                <tr>
                                    <th>Sipariş No</th>
                                    <td>{{ $details['OrderId'] }}</td>
                                </tr>
                                @endif
                                @if(isset($details['PurchAmount']))
                                <tr>
                                    <th>Tutar</th>
                                    <td>{{ number_format($details['PurchAmount'] / 100, 2) }} TL</td>
                                </tr>
                                @endif
                                @if(isset($details['TransactionDate']))
                                <tr>
                                    <th>İşlem Tarihi</th>
                                    <td>{{ $details['TransactionDate'] }}</td>
                                </tr>
                                @endif
                                @if(isset($details['CardMask']))
                                <tr>
                                    <th>Kart Numarası</th>
                                    <td>{{ $details['CardMask'] }}</td>
                                </tr>
                                @endif
                                @if(isset($details['ErrMsg']) && $details['ErrMsg'])
                                <tr>
                                    <th>Hata Mesajı</th>
                                    <td>{{ $details['ErrMsg'] }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="alert alert-info mt-3 text-start">
                            <h6 class="alert-heading">Ne Yapmalıyım?</h6>
                            <ul class="mb-0">
                                <li>Kart bilgilerinizi ve bakiyenizi kontrol edin.</li>
                                <li>Farklı bir kart ile tekrar deneyin.</li>
                                <li>Sorun devam ederse bankanız ile iletişime geçin.</li>
                            </ul>
                        </div>
                    @endif
                    <a href="{{ route('payment.form') }}" class="btn btn-outline-danger px-4 py-2 mt-2"><i class="bi bi-arrow-repeat me-1"></i>Tekrar Dene</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 