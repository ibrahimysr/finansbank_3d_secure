<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Güvenli Ödeme - QNB Finansbank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/css/payment.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://www.qnbfinansbank.com/favicon.ico">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Ödeme Formu</h4>
                        @if($testMode)
                            <div class="alert alert-info mt-2">
                                <strong>Test Modu Aktif</strong>
                                <p class="mb-0">Test kartı ve müşteri bilgileri otomatik olarak doldurulacaktır.</p>
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('payment.initiate') }}" id="payment-form">
                            @csrf
                            
                            <div class="form-group mb-3">
                                <label for="amount">Tutar (TL)</label>
                                <input type="number" 
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" 
                                       name="amount" 
                                       value="{{ old('amount') }}" 
                                       step="0.01" 
                                       min="1" 
                                       required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
                                @enderror
                            </div>

                            @if(!$testMode)
                                <div class="form-group mb-3">
                                    <label for="card_number">Kart Numarası</label>
                                    <input type="text" 
                                           class="form-control @error('card_number') is-invalid @enderror" 
                                           id="card_number" 
                                           name="card_number" 
                                           value="{{ old('card_number') }}" 
                                           placeholder="1234 5678 9012 3456" 
                                           required>
                                    @error('card_number')
                                        <div class="invalid-feedback">{{ $errors->first('card_number') }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="expiry">Son Kullanma Tarihi</label>
                                            <input type="text" 
                                                   class="form-control @error('expiry') is-invalid @enderror" 
                                                   id="expiry" 
                                                   name="expiry" 
                                                   value="{{ old('expiry') }}" 
                                                   placeholder="MM/YY" 
                                                   required>
                                            @error('expiry')
                                                <div class="invalid-feedback">{{ $errors->first('expiry') }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="cvv">CVV</label>
                                            <input type="text" 
                                                   class="form-control @error('cvv') is-invalid @enderror" 
                                                   id="cvv" 
                                                   name="cvv" 
                                                   value="{{ old('cvv') }}" 
                                                   placeholder="123" 
                                                   required>
                                            @error('cvv')
                                                <div class="invalid-feedback">{{ $errors->first('cvv') }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="cardholder_name">Kart Üzerindeki İsim</label>
                                    <input type="text" 
                                           class="form-control @error('cardholder_name') is-invalid @enderror" 
                                           id="cardholder_name" 
                                           name="cardholder_name" 
                                           value="{{ old('cardholder_name') }}" 
                                           required>
                                    @error('cardholder_name')
                                        <div class="invalid-feedback">{{ $errors->first('cardholder_name') }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email">E-posta</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="phone">Telefon</label>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="form-group mb-3">
                                <label for="installment_count">Taksit Sayısı</label>
                                <select class="form-control" id="installment_count" name="installment_count">
                                    <option value="0">Tek Çekim</option>
                                    <option value="2">2 Taksit</option>
                                    <option value="3">3 Taksit</option>
                                    <option value="6">6 Taksit</option>
                                    <option value="9">9 Taksit</option>
                                    <option value="12">12 Taksit</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Ödemeyi Tamamla</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/imask/7.0.5/imask.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kart numarası formatı
            const cardNumber = document.getElementById('card_number');
            if (cardNumber) {
                cardNumber.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    let formattedValue = '';
                    for (let i = 0; i < value.length; i++) {
                        if (i > 0 && i % 4 === 0) {
                            formattedValue += ' ';
                        }
                        formattedValue += value[i];
                    }
                    e.target.value = formattedValue;
                });
            }

            // Son kullanma tarihi formatı
            const expiry = document.getElementById('expiry');
            if (expiry) {
                expiry.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    e.target.value = value;
                });
            }

            // CVV sadece rakam
            const cvv = document.getElementById('cvv');
            if (cvv) {
                cvv.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/\D/g, '');
                });
            }
        });
    </script>
</body>
</html> 