<?php

namespace App\DTOs;

class PaymentResponseDTO
{
    private static array $errorMessages = [
        // Banka Hata Kodları
        '00' => 'İşlem başarıyla tamamlandı.',
        '01' => 'Bankanızı arayın: Kart yetkilendirmesinde sorun var.',
        '02' => 'Bankanızı arayın: Kartınızla ilgili özel durum mevcut.',
        '03' => 'Geçersiz üye işyeri. Lütfen daha sonra tekrar deneyiniz.',
        '04' => 'Kartınız kullanıma kapalı. Lütfen bankanızla iletişime geçiniz.',
        '05' => 'İşlem onaylanmadı. Kartınızla ilgili bir sorun olabilir.',
        '06' => 'Sistemsel bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.',
        '07' => 'Kartınız işleme kapalı. Lütfen bankanızla iletişime geçiniz.',
        '08' => 'Kartınızla ilgili doğrulama gerekiyor. Lütfen bankanızla iletişime geçiniz.',
        '09' => 'Sistemsel bir hata oluştu. Lütfen tekrar deneyiniz.',
        '12' => 'Geçersiz işlem. Lütfen kart bilgilerinizi kontrol ediniz.',
        '13' => 'Geçersiz tutar. Lütfen tutarı kontrol ediniz.',
        '14' => 'Geçersiz kart numarası. Lütfen kontrol ediniz.',
        '15' => 'Bankaya ulaşılamıyor. Lütfen daha sonra tekrar deneyiniz.',
        '16' => 'Asgari ödeme düzenli olarak gerçekleştirilmedi.',
        '22' => 'Şifre deneme sayısı aşıldı. Lütfen bankanızla iletişime geçiniz.',
        '25' => 'Kayıt bulunamadı. Lütfen bilgilerinizi kontrol ediniz.',
        '28' => 'İşlem reddedildi. Lütfen bankanızla iletişime geçiniz.',
        '29' => 'Orijinal işlem bulunamadı.',
        '30' => 'Sistemsel bir hata oluştu. Lütfen tekrar deneyiniz.',
        '33' => 'Kartınızın süresi dolmuş. Lütfen bankanızla iletişime geçiniz.',
        '34' => 'CVV2 kodu hatalı. Lütfen kontrol ediniz.',
        '36' => 'Kartınız kısıtlanmış. Lütfen bankanızla iletişime geçiniz.',
        '38' => 'PIN deneme sayısı aşıldı. Lütfen bankanızla iletişime geçiniz.',
        '41' => 'Kayıp kart. İşlem gerçekleştirilemez.',
        '43' => 'Çalıntı kart. İşlem gerçekleştirilemez.',
        '51' => 'Yetersiz bakiye. Lütfen bakiyenizi kontrol ediniz.',
        '52' => 'Hesap bulunamadı. Lütfen bankanızla iletişime geçiniz.',
        '53' => 'Hesap bulunamadı. Lütfen bankanızla iletişime geçiniz.',
        '54' => 'Kartınızın son kullanma tarihi geçmiş.',
        '55' => 'Hatalı PIN. Lütfen tekrar deneyiniz.',
        '56' => 'Bu kart desteklenmiyor.',
        '57' => 'Bu işlem kartınız için izin verilmeyen bir işlem.',
        '58' => 'Terminal bu işlem için yetkili değil.',
        '61' => 'Para çekme limiti aşıldı.',
        '62' => 'Kısıtlı kart. Lütfen bankanızla iletişime geçiniz.',
        '63' => 'Güvenlik ihlali. İşlem gerçekleştirilemez.',
        '65' => 'Para çekme limiti aşıldı.',
        '75' => 'PIN deneme sayısı aşıldı.',
        '76' => 'Sistemsel bir hata oluştu. Lütfen tekrar deneyiniz.',
        '77' => 'İşlem reddedildi.',
        '78' => 'Güvenli olmayan PIN.',
        '79' => 'ARQC doğrulama hatası.',
        '81' => 'Sistemsel bir hata oluştu. Lütfen tekrar deneyiniz.',
        '91' => 'Banka şu anda işlem yapamıyor. Lütfen daha sonra tekrar deneyiniz.',
        '92' => 'Bankaya ulaşılamıyor.',
        '95' => 'Sistemsel bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.',
        '96' => 'Sistemsel bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.',
        '98' => 'Çift ters işlem tespit edildi.',

        // Merchant/İşyeri Hataları (M kodları)
        'M001' => 'Bonus miktarı sipariş tutarından büyük olamaz.',
        'M002' => 'Para birimi kodu geçersiz.',
        'M003' => 'Para birimi belirtilmemiş.',
        'M004' => 'Geçersiz tutar.',
        'M005' => 'CVV2 kodu girilmemiş.',
        'M006' => 'Son kullanma tarihi hatalı veya eksik.',
        'M007' => 'Hata URL\'i belirtilmemiş.',
        'M008' => 'Kart numarası hatalı veya eksik.',
        'M009' => 'Üye işyeri şifresi hatalı.',
        'M010' => 'Başarılı URL\'i belirtilmemiş.',
        'M030' => 'Hash doğrulama hatası. Lütfen tekrar deneyiniz.',
        'M041' => 'Geçersiz kart numarası.',

        // Validasyon/Doğrulama Hataları (V kodları)
        'V000' => 'İşlem devam ediyor...',
        'V001' => 'Üye işyeri bulunamadı.',
        'V003' => 'Sistem şu anda kapalı.',
        'V007' => 'Terminal aktif değil. Lütfen daha sonra tekrar deneyiniz.',
        'V009' => 'Üye işyeri aktif değil.',
        'V010' => 'Terminal bu işlem için yetkili değil.',
        'V011' => 'Bu işlem tipi için yetki yok.',
        'V013' => 'İşlem bulunamadı.',
        'V014' => 'Gün sonu yapılmadan iade işlemi gerçekleştirilemez.',
        'V015' => 'İade tutarı orijinal işlem tutarından büyük olamaz.',
        'V017' => 'Bu sipariş zaten iptal edildi.',
        'V018' => 'Bu işlem iptal edilemez.',
        'V019' => 'Kısmi iptal yapılamaz.',
        'V020' => 'İşlem henüz tamamlanmadı.',
        'V023' => 'Taksit sayısı belirtilmelidir.',
        'V024' => 'Bu işlem için taksit yapılamaz.',
        'V025' => 'Bu provizyon daha önce kapatılmış.',
        'V026' => 'Provizyon süresi dolmuş.',
        'V029' => 'Sipariş numarası benzersiz olmalıdır.',
        'V034' => '3D Secure doğrulaması başarısız.',
        'V036' => 'Sistemsel bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.',
        'V037' => 'Bu sipariş daha önce iade edilmiş.',
        'V038' => 'Bu işlem için yetkiniz yok.',
        'V044' => 'Ödeme başarısız.',
        'V087' => '3D Secure doğrulaması başarısız olduğu için ödeme reddedildi.',
        'V9196' => 'Bağlantı hatası. Lütfen tekrar deneyiniz.',
        'V9199' => 'Banka bağlantısında hata oluştu. Lütfen tekrar deneyiniz.'
    ];

    public function __construct(
        public bool $success,
        public string $message,
        public ?string $transactionId = null,
        public ?string $orderId = null,
        public ?string $authCode = null,
        public ?string $hostRefNum = null,
        public ?string $procReturnCode = null,
        public ?array $raw = null
    ) {}

    public static function fromArray(array $response): self
    {
        $success = ($response['Response'] ?? '') === 'Approved';
        $procReturnCode = $response['ProcReturnCode'] ?? null;
        $bankInternalCode = $response['BankInternalResponseCode'] ?? null;

        // Önce banka iç hata kodunu kontrol et
        if ($bankInternalCode && isset(self::$errorMessages[$bankInternalCode])) {
            $message = self::$errorMessages[$bankInternalCode];
        }
        // Sonra normal hata kodunu kontrol et
        elseif ($procReturnCode && isset(self::$errorMessages[$procReturnCode])) {
            $message = self::$errorMessages[$procReturnCode];
        }
        // Hiçbiri yoksa varsayılan mesajı kullan
        else {
            $message = $response['ErrMsg'] ?? ($success ? 'İşlem başarılı' : 'İşlem başarısız');
        }
        
        return new self(
            success: $success,
            message: $message,
            transactionId: $response['TransId'] ?? null,
            orderId: $response['OrderId'] ?? null,
            authCode: $response['AuthCode'] ?? null,
            hostRefNum: $response['HostRefNum'] ?? null,
            procReturnCode: $procReturnCode,
            raw: $response
        );
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'transaction_id' => $this->transactionId,
            'order_id' => $this->orderId,
            'auth_code' => $this->authCode,
            'host_ref_num' => $this->hostRefNum,
            'proc_return_code' => $this->procReturnCode,
            'raw' => $this->raw,
        ];
    }
} 