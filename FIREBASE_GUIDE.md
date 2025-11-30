# Firebase Cloud Messaging (FCM) Qo'llanmasi

Bu qo'llanma Laravel loyihangizga **Firebase Push Notification** tizimini ulash bo'yicha qadamma-qadam yo'riqnoma. Bu orqali siz brauzer yopiq bo'lganda ham yoki mobil ilovaga xabar yubora olasiz.

---

## 🔥 1-qadam: Firebase Console Sozlamalari

1.  [Firebase Console](https://console.firebase.google.com/) ga kiring.
2.  **Add project** tugmasini bosib, yangi loyiha yarating (masalan: `Laravel-Notification`).
3.  Loyiha yaratilgach, **Project settings** (⚙️ tishli g'ildirak) -> **Service accounts** bo'limiga o'ting.
4.  **Generate new private key** tugmasini bosing.
5.  Yuklangan `.json` faylni loyihangizning `storage/app/firebase.json` joyiga qo'ying.

---

## 📦 2-qadam: Laravelga Paket O'rnatish

Bizga Google Firebase bilan ishlash uchun paket kerak. Eng mashhuri: `kreait/laravel-firebase`.

```bash
composer require kreait/laravel-firebase
```

Keyin konfiguratsiya faylini chiqaramiz:
```bash
php artisan vendor:publish --provider="Kreait\Laravel\Firebase\ServiceProvider" --tag=config
```

`.env` faylga yo'lni ko'rsatamiz:
```ini
FIREBASE_CREDENTIALS=storage/app/firebase.json
```

---

## 📱 3-qadam: Frontend (Web Push) Sozlamalari

Web brauzerda push notification olish uchun `firebase-messaging-sw.js` (Service Worker) kerak.

### 3.1. Firebase SDK ulash
`resources/views/layouts/app.blade.php` (yoki `dashboard.blade.php`) ga qo'shamiz:

```html
<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
  import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";

  const firebaseConfig = {
    apiKey: "SIZNING_API_KEY",
    authDomain: "SIZNING_PROJECT.firebaseapp.com",
    projectId: "SIZNING_PROJECT_ID",
    storageBucket: "SIZNING_PROJECT.appspot.com",
    messagingSenderId: "SIZNING_SENDER_ID",
    appId: "SIZNING_APP_ID"
  };

  const app = initializeApp(firebaseConfig);
  const messaging = getMessaging(app);

  // Ruxsat so'rash va Token olish
  function requestPermission() {
    Notification.requestPermission().then((permission) => {
      if (permission === 'granted') {
        getToken(messaging, { vapidKey: 'SIZNING_VAPID_KEY' }).then((currentToken) => {
          if (currentToken) {
            console.log('FCM Token:', currentToken);
            // 🔥 Bu tokenni serverga yuborib, userga saqlash kerak!
            saveTokenToServer(currentToken);
          }
        });
      }
    });
  }
  
  requestPermission();
</script>
```

*Eslatma: `firebaseConfig` ma'lumotlarini Firebase Console -> Project settings -> General -> Your apps bo'limidan olasiz.*

---

## 💾 4-qadam: Tokenni Saqlash (Backend)

Foydalanuvchining qurilma tokenini (FCM Token) bazada saqlashimiz kerak.

1.  `users` jadvaliga `fcm_token` ustunini qo'shing:
    ```bash
    php artisan make:migration add_fcm_token_to_users_table
    ```
    ```php
    $table->string('fcm_token')->nullable();
    ```

2.  Tokenni saqlash uchun API yarating:
    ```php
    // Route
    Route::post('/save-fcm-token', [UserController::class, 'saveToken']);

    // Controller
    public function saveToken(Request $request) {
        auth()->user()->update(['fcm_token' => $request->token]);
        return response()->json(['success' => true]);
    }
    ```

---

## 🚀 5-qadam: Xabar Yuborish (Backend)

Endi post yaratilganda Firebase orqali xabar yuboramiz.

**Fayl:** `app/Http/Controllers/PostController.php`

```php
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

public function store(Request $request)
{
    // ... post yaratish ...

    $post = Post::create($validated);

    // 1. Userlarning tokenlarini olamiz
    $tokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

    if (!empty($tokens)) {
        $messaging = app('firebase.messaging');
        
        $message = CloudMessage::new()
            ->withNotification(Notification::create('Yangi Post!', $post->title))
            ->withData(['post_id' => $post->id]); // Qo'shimcha ma'lumot

        // Hamma tokenlarga yuborish (Multicast)
        $messaging->sendMulticast($message, $tokens);
    }

    return redirect()->...
}
```

---

## 🧪 6-qadam: Test Qilish

1.  Saytga kiring, brauzer "Notification ruxsat berish"ni so'raydi -> "Allow" bosing.
2.  Console'da `FCM Token: ...` chiqqanini tekshiring.
3.  Bazada userning `fcm_token` ustuni to'ldirilganini tekshiring.
4.  Yangi post yarating.
5.  Desktopda o'ng burchakda Windows/MacOS tizim xabarnomasi chiqishi kerak! 🎉

---

## 📱 Mobile Ilova Uchun (Flutter/React Native)

Mobile ilovada jarayon juda o'xshash:
1.  Ilova ochilganda FCM token olinadi.
2.  Token API orqali serverga yuborilib, bazaga saqlanadi.
3.  Server xabar yuborganda, telefon o'zi xabarni ko'rsatadi (Ilova yopiq bo'lsa ham).

Tamom! Endi sizda ham WebSocket (jonli chat uchun), ham Firebase (oflayn xabarlar uchun) bor.
