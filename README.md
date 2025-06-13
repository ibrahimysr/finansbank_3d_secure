# Finansbank 3D Secure Ödeme Sistemi Entegrasyonu

Bu proje, Finansbank'ın test ortamı üzerinden 3D Secure (3D_PAY) ödeme modunu kullanarak, Laravel ile nesne tabanlı bir ödeme sistemi entegrasyonu içerir.

## Kurulum

1. Projeyi klonlayın:
```bash
git clone [repo-url]
cd 3d_payment
```

2. Composer bağımlılıklarını yükleyin:
```bash
composer install
```

3. `.env` dosyasını oluşturun:
```bash
cp .env.example .env
```

4. `.env` dosyasını düzenleyin ve aşağıdaki Finansbank ayarlarını ekleyin:
```env
# Finansbank API Ayarları
FINANS_TEST_MODE=true
FINANS_MERCHANT_ID=your_merchant_id
FINANS_MERCHANT_TERMINAL=your_terminal_id
FINANS_MERCHANT_NAME=your_merchant_name
FINANS_MERCHANT_PASSWORD=your_merchant_password
FINANS_STORE_KEY=your_store_key
FINANS_API_USER=your_api_user
FINANS_API_PASSWORD=your_api_password
FINANS_3D_URL=https://vpos.qnbfinansbank.com/Gateway/Default.aspx
FINANS_API_URL=https://vpos.qnbfinansbank.com/Gateway/Default.aspx
FINANS_SUCCESS_URL=/payment/success
FINANS_FAILURE_URL=/payment/failure
```

5. Uygulama anahtarını oluşturun:
```bash
php artisan key:generate
```


## Test Kartları

Finansbank test ortamında kullanabileceğiniz test kartları:

- Kart No: 4022780198283155
- Son Kullanma: 01/50
- CVV: (Boş bırakılabilir)

Alternatif Test Kartı:
- Kart No: 9792091234123455
- Son Kullanma: 12/20
- CVV: 123

## Özellikler

- 3D Secure ödeme entegrasyonu
- Güvenli kart bilgisi işleme
- Detaylı hata yönetimi ve loglama
- Responsive ödeme formu
- Başarılı/başarısız ödeme sayfaları

## Güvenlik

- Kart bilgileri sunucuda saklanmaz
- CSRF koruması
- Input validasyonu
- SSL zorunluluğu

## Kullanım

1. Ödeme formuna erişin: `/payment`
2. Kart bilgilerini girin
3. 3D Secure doğrulamasını tamamlayın
4. Ödeme sonucunu görüntüleyin

## Hata Ayıklama

Hata durumunda `storage/logs/laravel.log` dosyasını kontrol edin. Tüm ödeme işlemleri ve hatalar burada loglanır.

