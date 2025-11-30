# Mobile API va Real-time Notification Qo'llanmasi

Bu qo'llanma Laravel loyihangizni Mobile ilovalar (Flutter, React Native, iOS, Android) bilan ishlashga tayyorlash uchun mo'ljallangan.

---

## 🔐 1-qadam: API Authentication (Sanctum)

Mobile ilovalar cookie emas, **Token** orqali ishlaydi. Laravel Sanctum buning uchun eng yaxshi yechim.

### 1.1. Sanctum o'rnatish (Agar o'rnatilmagan bo'lsa)
```bash
php artisan install:api
```
Bu buyruq `routes/api.php` faylini va kerakli migrationlarni yaratadi.

### 1.2. User Modelini sozlash
`User.php` modelida `HasApiTokens` traiti borligiga ishonch hosil qiling:

```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```

---

## 🔌 2-qadam: API Login va Token olish

Mobile ilova birinchi navbatda login qilib, token olishi kerak.

**Fayl:** `app/Http/Controllers/Api/AuthController.php` (Yaratish kerak)

```php
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Login xato'], 401);
    }

    // 🔥 Token yaratish
    $token = $user->createToken('mobile-app')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
}
```

---

## 📡 3-qadam: Broadcasting Auth (API uchun)

Mobile ilova WebSocketga ulanayotganda ham shu tokenni ishlatishi kerak.

**Fayl:** `routes/channels.php`

Hozir bizda `web` middleware ishlatilyapti. API uchun `auth:sanctum` kerak.

```php
// routes/channels.php
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}, ['guards' => ['sanctum']]); // <--- MUHIM: guards qo'shildi
```

**Fayl:** `bootstrap/app.php` (Laravel 11)
Agar kerak bo'lsa, broadcasting route'larini API uchun sozlash kerak bo'lishi mumkin.

---

## 📱 4-qadam: Mobile Ilovada Sozlash (Konsept)

Mobile ilovada (masalan Flutter) quyidagi kutubxonalar ishlatiladi:
- **Http Client:** (Dio yoki Http) API requestlar uchun.
- **WebSocket Client:** (Laravel Echo yoki Pusher Client).

### 4.1. Ulanish (Flutter misolida)

```dart
// 1. Login qilib token olamiz
String token = "...login_dan_kelgan_token...";

// 2. Echo sozlash
PusherClient pusher = PusherClient(
  "REVERB_APP_KEY",
  PusherOptions(
    host: "sizning-ip-adresingiz",
    port: 8080,
    encrypted: false,
    auth: PusherAuth(
      "http://sizning-ip-adresingiz/api/broadcasting/auth",
      headers: {
        'Authorization': 'Bearer $token', // <--- Token shu yerda ketadi
        'Accept': 'application/json',
      },
    ),
  ),
);

// 3. Kanalga ulanish
Channel channel = pusher.subscribe("private-user.2");

channel.bind("private-notification", (event) {
  print("Xabar keldi: " + event.data);
});
```

---

## 🧪 5-qadam: Test Qilish (Postman)

Hali mobile ilova yo'q bo'lsa, **Postman** orqali test qilamiz.

### 5.1. Login va Token olish
1. **Method:** POST
2. **URL:** `http://127.0.0.1:8000/api/login`
3. **Body:**
   - email: `test@example.com`
   - password: `password`
4. **Javob:** `token` ni nusxalab oling.

### 5.2. WebSocket Test (Postman WebSocket)
Postman v10+ da WebSocket test qilish imkoniyati bor.

1. **New** -> **WebSocket Request** ni tanlang.
2. **URL:** `ws://127.0.0.1:8080/app/yt24pq2pffb9gouvenfr?protocol=7&client=js&version=8.4.0-rc2&flash=false`
   - `yt24pq2pffb9gouvenfr` - bu sizning `REVERB_APP_KEY`.
3. **Connect** ni bosing.

### 5.3. Private Kanalga Ulanish (Auth)
Bu qism biroz murakkab, chunki WebSocket ulanishda to'g'ridan-to'g'ri header berib bo'lmaydi. Odatda mobile ilova buni avtomatik qiladi (HTTP request orqali auth qilib, keyin ulanadi).

Oddiy test uchun **Public Channel** (`posts`) ni test qilish osonroq:
1. Postmanda WebSocketga ulaning.
2. Quyidagi JSON ni yuboring (Subscribe qilish uchun):
```json
{
    "event": "pusher:subscribe",
    "data": {
        "channel": "posts"
    }
}
```
3. Endi saytda post yarating.
4. Postmanda xabar kelishini ko'rasiz! 🎉

---

## 🚀 Xulosa: Ketma-ketlik

1.  `php artisan install:api` (Sanctum o'rnatish).
2.  `Api/AuthController` yaratish (Login va Token berish uchun).
3.  `routes/api.php` da login route ochish.
4.  `routes/channels.php` da `['guards' => ['sanctum']]` qo'shish.
5.  Mobile ilovada `Authorization: Bearer {token}` headeri bilan ishlash.

Hozir amaliyotda qo'llashni boshlaymizmi?
