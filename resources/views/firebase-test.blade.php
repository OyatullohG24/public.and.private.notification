<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>🔥 Firebase Notification Test</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 700px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            text-align: center;
            font-size: 32px;
        }

        .subtitle {
            text-align: center;
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .status {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 500;
        }

        .status.success {
            background: #d1fae5;
            color: #065f46;
        }

        .status.error {
            background: #fee2e2;
            color: #991b1b;
        }

        .status.info {
            background: #dbeafe;
            color: #1e40af;
        }

        .step {
            margin-bottom: 15px;
        }

        .step-title {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
            font-weight: 500;
        }

        button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        #log {
            background: #f9fafb;
            padding: 20px;
            border-radius: 12px;
            max-height: 350px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin-top: 25px;
            border: 1px solid #e5e7eb;
        }

        .log-entry {
            margin-bottom: 8px;
            padding: 8px 12px;
            border-left: 3px solid #667eea;
            background: white;
            border-radius: 4px;
        }

        .log-entry.error {
            border-left-color: #ef4444;
        }

        .log-entry.success {
            border-left-color: #10b981;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Toast Notification Styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .toast {
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 15px;
            animation: slideIn 0.3s ease-out;
            min-width: 300px;
            border-left: 4px solid #10b981;
        }

        .toast-content h4 {
            margin: 0 0 5px 0;
            color: #1f2937;
            font-size: 15px;
        }

        .toast-content p {
            margin: 0;
            color: #6b7280;
            font-size: 13px;
        }

        .toast-icon {
            font-size: 24px;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>
</head>

<body>
    <div class="toast-container" id="toastContainer"></div>

    <div class="container">
        <h1>🔥 Firebase Notification Test</h1>
        <p class="subtitle">Qadamma-qadam test qilish uchun quyidagi tugmalarni ketma-ket bosing</p>

        <div id="status" class="status info">
            ✨ Tayyor. Test boshlash uchun birinchi tugmani bosing.
        </div>

        <div class="step">
            <div class="step-title">1-qadam: Brauzerdan ruxsat olish</div>
            <button onclick="testPermission()">
                <span>🔔</span> Notification Ruxsatini Tekshirish
            </button>
        </div>

        <div class="step">
            <div class="step-title">2-qadam: Firebase tokenni olish</div>
            <button onclick="testToken()">
                <span>🔑</span> FCM Token Olish
            </button>
        </div>

        <div class="step">
            <div class="step-title">3-qadam: Tokenni serverga saqlash</div>
            <button onclick="testSaveToken()">
                <span>💾</span> Token ni Bazaga Saqlash
            </button>
        </div>

        <div class="step">
            <div class="step-title">4-qadam: Test notification yuborish</div>
            <button onclick="testSendNotification()">
                <span>🚀</span> Test Notification Yuborish
            </button>
        </div>

        <div id="log"></div>

        <a href="{{ route('dashboard') }}" class="back-link">← Dashboard ga qaytish</a>
    </div>

    <script type="module">
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import {
            getMessaging,
            getToken,
            onMessage
        } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";

        // ⚠️ MUHIM: Bu yerga o'zingizning Firebase config kiriting!
        // Firebase Console -> Project settings -> General -> Your apps
        const firebaseConfig = {
            apiKey: "AIzaSyDSDKeigNRxbvUeQ6pKkIC1CeliQy8YGAA",
            authDomain: "laravel-notification-8e93f.firebaseapp.com",
            projectId: "laravel-notification-8e93f",
            storageBucket: "laravel-notification-8e93f.firebasestorage.app",
            messagingSenderId: "761721644146",
            appId: "1:761721644146:web:fd2924d9beffccbda0ceeb"
        };

        // ⚠️ VAPID KEY: Firebase Console -> Project settings -> Cloud Messaging -> Web Push certificates
        const VAPID_KEY = "BNU61NIRX-Bgexjdcb0p3ic1kSLpU9Aq7V7BS0bzudNErI5G268wPYEG22NEvQS9j9BvTW3Xx64zN5rpqNaOYOw";

        let app, messaging;
        let currentToken = null;

        // Log funksiyasi
        function log(message, type = 'info') {
            const logDiv = document.getElementById('log');
            const entry = document.createElement('div');
            entry.className = `log-entry ${type}`;
            entry.textContent = `[${new Date().toLocaleTimeString()}] ${message}`;
            logDiv.appendChild(entry);
            logDiv.scrollTop = logDiv.scrollHeight;
        }

        // Status o'zgartirish
        function setStatus(message, type) {
            const statusDiv = document.getElementById('status');
            statusDiv.textContent = message;
            statusDiv.className = `status ${type}`;
        }

        // 1. Notification ruxsatini tekshirish
        window.testPermission = async function() {
            log('🔔 Notification ruxsatini tekshirish...');

            if (!('Notification' in window)) {
                log('❌ Bu brauzer notification qo\'llab-quvvatlamaydi!', 'error');
                setStatus('❌ Brauzer notification qo\'llab-quvvatlamaydi', 'error');
                return;
            }

            const permission = await Notification.requestPermission();

            if (permission === 'granted') {
                log('✅ Notification ruxsati berildi!', 'success');
                setStatus('✅ Ruxsat berildi. Keyingi qadamga o\'ting.', 'success');
            } else {
                log('❌ Notification ruxsati rad etildi', 'error');
                setStatus('❌ Ruxsat rad etildi. Brauzer sozlamalaridan ruxsat bering.', 'error');
            }
        };

        // 2. FCM Token olish
        window.testToken = async function() {
            try {
                log('🔥 Firebase ni ishga tushirish...');

                if (!app) {
                    app = initializeApp(firebaseConfig);
                    messaging = getMessaging(app);
                    log('✅ Firebase ishga tushdi', 'success');
                }

                log('⚙️ Service Worker ni ro\'yxatdan o\'tkazish...');
                const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
                log('✅ Service Worker ro\'yxatdan o\'tdi', 'success');

                log('🔑 FCM Token olish...');
                currentToken = await getToken(messaging, {
                    vapidKey: VAPID_KEY,
                    serviceWorkerRegistration: registration
                });

                if (currentToken) {
                    log('✅ FCM Token olindi!', 'success');
                    log(`Token: ${currentToken.substring(0, 60)}...`);
                    setStatus('✅ Token olindi. Keyingi qadamga o\'ting.', 'success');

                    // Foreground notification listener ni shu yerda ulaymiz
                    onMessage(messaging, (payload) => {
                        log('📬 Foreground notification keldi!', 'success');
                        log(`Title: ${payload.notification.title}`);
                        log(`Body: ${payload.notification.body}`);

                        // Toast chiqarish
                        showToast(payload.notification.title, payload.notification.body);
                    });
                    log('👂 Notification tinglash boshlandi', 'info');

                } else {
                    log('❌ Token olinmadi. Ruxsat berganingizni tekshiring.', 'error');
                    setStatus('❌ Token olinmadi', 'error');
                }
            } catch (error) {
                log(`❌ Xatolik: ${error.message}`, 'error');
                setStatus(`❌ Xatolik: ${error.message}`, 'error');
                console.error('Firebase error:', error);
            }
        };

        // 3. Tokenni serverga saqlash
        window.testSaveToken = async function() {
            if (!currentToken) {
                log('❌ Avval token oling (2-qadam)', 'error');
                setStatus('❌ Avval token oling', 'error');
                return;
            }

            try {
                log('💾 Tokenni serverga yuborish...');

                const response = await fetch('/save-fcm-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json' // JSON kutayotganimizni aytamiz
                    },
                    body: JSON.stringify({
                        token: currentToken
                    })
                });

                // Agar response OK bo'lmasa
                if (!response.ok) {
                    const text = await response.text();
                    log(`❌ Server xatosi (${response.status}): ${text.substring(0, 100)}...`, 'error');
                    setStatus(`❌ Server xatosi: ${response.status}`, 'error');
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    log('✅ Token bazaga saqlandi!', 'success');
                    setStatus('✅ Token saqlandi. Endi test notification yuboring.', 'success');
                } else {
                    log('❌ Token saqlanmadi', 'error');
                    setStatus('❌ Token saqlanmadi', 'error');
                }
            } catch (error) {
                log(`❌ Server xatosi: ${error.message}`, 'error');
                setStatus(`❌ Server xatosi`, 'error');
            }
        };

        // 4. Test notification yuborish
        window.testSendNotification = async function() {
            try {
                log('🚀 Test notification yuborish...');

                const response = await fetch('/send-test-notification', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        title: 'Test Xabar 🎉',
                        body: 'Bu Firebase test notification! Agar bu xabarni ko\'rsangiz, hammasi ishlayapti!'
                    })
                });

                const data = await response.json();

                if (data.success) {
                    log(`✅ Notification yuborildi! (${data.success_count}/${data.sent_count} muvaffaqiyatli)`,
                        'success');
                    setStatus(`🎉 Muvaffaqiyat! ${data.success_count} ta notification yuborildi.`, 'success');
                    log('📱 Brauzer notification kelishi kerak. Tekshiring!', 'success');
                } else {
                    log(`❌ ${data.message}`, 'error');
                    setStatus(`❌ ${data.message}`, 'error');
                }
            } catch (error) {
                log(`❌ Xatolik: ${error.message}`, 'error');
                setStatus(`❌ Xatolik`, 'error');
            }
        };

        // Toast chiqarish funksiyasi
        function showToast(title, body) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.innerHTML = `
                <div class="toast-icon">🔔</div>
                <div class="toast-content">
                    <h4>${title}</h4>
                    <p>${body}</p>
                </div>
            `;
            container.appendChild(toast);

            // 5 sekunddan keyin o'chirish
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-in forwards';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        log('✨ Test sahifasi tayyor. Yuqoridagi tugmalarni 1 dan 4 gacha ketma-ket bosing.');
    </script>
</body>

</html>
