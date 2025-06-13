<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'QNB 3D Payment') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 2rem;
        }
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'QNB 3D Payment') }}
            </a>
        </div>
    </nav>

    <main class="py-4 mt-5">
        @yield('content')
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kart numarası formatı
            const cardInput = document.getElementById('card_number');
            if (cardInput) {
                cardInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.replace(/(.{4})/g, '$1 ').trim();
                    e.target.value = value;
                });
            }

            // Son kullanma tarihi formatı
            const expiryInput = document.getElementById('expiry');
            if (expiryInput) {
                expiryInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 2) {
                        value = value.substring(0, 2) + value.substring(2);
                    }
                    if (value.length > 4) {
                        value = value.substring(0, 4);
                    }
                    e.target.value = value;
                });
            }

            // CVV sadece rakam
            const cvvInput = document.getElementById('cvv');
            if (cvvInput) {
                cvvInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/\D/g, '');
                });
            }

            // Tutar formatı
            const amountInput = document.getElementById('amount');
            if (amountInput) {
                amountInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^\d.]/g, '');
                    // Sadece bir tane nokta olabilir
                    let parts = value.split('.');
                    if (parts.length > 2) {
                        value = parts[0] + '.' + parts.slice(1).join('');
                    }
                    // En fazla 2 ondalık basamak
                    if (parts.length === 2 && parts[1].length > 2) {
                        value = parts[0] + '.' + parts[1].substring(0, 2);
                    }
                    e.target.value = value;
                });
            }
        });
    </script>
</body>
</html> 