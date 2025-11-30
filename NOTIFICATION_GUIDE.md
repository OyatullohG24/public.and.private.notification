# Laravel Reverb Real-time Notification Qo'llanmasi

Bu qo'llanma loyihada qanday qilib **Real-time Notification** tizimini yaratganimizni qadamma-qadam tushuntirib beradi.

---

## 🛠 1-qadam: Event Yaratish (Backend)

Bizga yangi post yaratilganda signal beruvchi "Xabarchi" kerak. Buning uchun Laravel Event ishlatamiz.

**Buyruq:**
```bash
php artisan make:event NewPostEvent
```

**Fayl:** `app/Events/NewPostEvent.php`

Biz bu faylni quyidagicha o'zgartirdik:

```php
class NewPostEvent implements ShouldBroadcastNow // <--- "Now" qo'shdik (darhol yuborish uchun)
{
    public $post; // Post ma'lumotlarini saqlash uchun

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    // Qaysi "kanal" orqali yuborish
    public function broadcastOn(): array
    {
        // 'posts' nomli umumiy (public) kanal ochdik
        return [
            new Channel('posts'),
        ];
    }

    // Event nomi (Frontend'da shu nom bilan kutib olamiz)
    public function broadcastAs(): string
    {
        return 'new-post';
    }

    // Yuboriladigan ma'lumotlar
    public function broadcastWith(): array
    {
        return [
            'id' => $this->post->id,
            'title' => $this->post->title,
            'author' => $this->post->user->name,
        ];
    }
}
```

---

## 🎮 2-qadam: Controllerda Eventni Chaqirish

Post yaratilgandan so'ng darhol xabarni yuborishimiz kerak.

**Fayl:** `app/Http/Controllers/PostController.php`

```php
public function store(Request $request)
{
    // ... validatsiya va saqlash kodlari ...

    $post = Post::create($validated);

    // 🔥 MUHIM: Eventni "broadcast" qilamiz (efirga uzatamiz)
    broadcast(new NewPostEvent($post));

    return redirect()->route('posts.index')...
}
```

---

## ⚙️ 3-qadam: Environment (.env) Sozlamalari

Bu yerda bizda muammo chiqqan edi. Vite frontendda ishlashi uchun o'zgaruvchilar to'g'ri yozilishi shart.

**Fayl:** `.env`

```ini
# Reverb Sozlamalari
REVERB_APP_ID=...
REVERB_APP_KEY=...
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

# 🔥 MUHIM: Vite uchun nusxalar (qo'shtirnoqsiz va $ belgisiz!)
VITE_REVERB_APP_KEY=sizning_app_key
VITE_REVERB_HOST=localhost
VITE_REVERB_PORT=8080
VITE_REVERB_SCHEME=http
```

*Eslatma: Agar bu yerda o'zgarish qilsangiz, `npm run dev` ni qayta ishga tushirish shart!*

---

## 📡 4-qadam: Frontend - Echo Sozlamalari

Laravel Echo - bu frontenddagi "quloq". U serverni eshitib turadi.

**Fayl:** `resources/js/echo.js`

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY, // .env dan oladi
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});
```

---

## 🖥 5-qadam: Frontend - UI va Listener (Blade)

Eng muhim qismi. Bizda "Echo undefined" xatosi chiqqan edi, chunki script Echo yuklanmasdan oldin ishga tushib ketayotgan edi. Biz buni `initEcho` funksiyasi bilan hal qildik.

**Fayl:** `resources/views/dashboard.blade.php` (va posts/index.blade.php)

```html
<!-- 1. App.js ni yuklaymiz -->
@vite(['resources/js/app.js'])

<script>
    // 2. Notification ko'rsatish funksiyasi
    function showNotification(data) {
        // ... toast yaratish kodlari ...
    }

    // 3. 🔥 MUHIM: Echo tayyor bo'lishini kutish funksiyasi
    const initEcho = () => {
        // Agar window.Echo mavjud bo'lsa
        if (window.Echo) {
            // Kanalga ulanamiz
            window.Echo.channel('posts')
                .listen('.new-post', (data) => { // . nuqta bilan boshlanishi kerak!
                    console.log('Yangi post:', data);
                    showNotification(data);
                });
            console.log('Echo listener started');
        } else {
            // Agar yo'q bo'lsa, 100ms kutib qayta tekshiramiz
            console.log('Waiting for Echo...');
            setTimeout(initEcho, 100);
        }
    };

    // Funksiyani ishga tushiramiz
    initEcho();
</script>
```

---

## 🚀 6-qadam: Ishga Tushirish

Tizim ishlashi uchun 3 ta narsa bir vaqtda ishlab turishi kerak:

1.  **Laravel Server:**
    ```bash
    php artisan serve
    ```

2.  **Reverb Server (WebSocket):**
    ```bash
    php artisan reverb:start
    ```

3.  **Vite Server (Frontend):**
    ```bash
    npm run dev
    ```

---

## 🎯 Xulosa: Jarayon qanday kechadi?

1.  Foydalanuvchi post yaratadi → `PostController`
2.  Controller `NewPostEvent` ni chaqiradi → `broadcast()`
3.  Laravel bu xabarni **Reverb Server**ga yuboradi.
4.  Reverb Server bu xabarni barcha ulangan **Brauzerlarga** tarqatadi.
5.  Brauzerdagi `window.Echo` xabarni tutib oladi.
6.  JavaScript `showNotification` funksiyasini ishga tushirib, ekranga chiroyli xabar chiqaradi.

Tamom! Tizim shu tarzda ishlaydi.

---

## 🔒 7-qadam: Private Notification (Shaxsiy Xabar)

Biz endi **Private Channel** ham qo'shdik. Bu faqat ma'lum bir foydalanuvchiga (masalan, ID=2) xabar yuborish uchun ishlatiladi.

### Qanday ishlaydi?

1.  **Event:** `PrivateNotificationEvent` (user ID qabul qiladi)
2.  **Controller:**
    ```php
    // Faqat ID=2 userga yuborish
    broadcast(new PrivateNotificationEvent(2, [
        'title' => 'Shaxsiy Xabar',
        'message' => '...'
    ]));
    ```
3.  **Channels.php:** Ruxsat berish kerak:
    ```php
    Broadcast::channel('user.{id}', function ($user, $id) {
        return (int) $user->id === (int) $id;
    });
    ```
4.  **Frontend:** `window.Echo.private(...)` ishlatiladi:
    ```javascript
    window.Echo.private('user.' + userId)
        .listen('.private-notification', (e) => { ... });
    ```
