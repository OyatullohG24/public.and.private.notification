// ============================================
// Firebase Cloud Messaging Service Worker
// ============================================
// Bu fayl brauzer yopiq bo'lganda ham notification qabul qilish uchun kerak.
// Service Worker background da ishlaydi va push notification'larni ko'rsatadi.

// Firebase SDK'larni yuklash (compat versiyasi - Service Worker uchun)
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

// ⚠️ MUHIM: Bu yerga o'zingizning Firebase config ma'lumotlarini kiriting!
// Firebase Console -> Project settings -> General -> Your apps -> Web app
const firebaseConfig = {
    apiKey: "AIzaSyDSDKeigNRxbvUeQ6pKkIC1CeliQy8YGAA",
    authDomain: "laravel-notification-8e93f.firebaseapp.com",
    projectId: "laravel-notification-8e93f",
    storageBucket: "laravel-notification-8e93f.firebasestorage.app",
    messagingSenderId: "761721644146",
    appId: "1:761721644146:web:fd2924d9beffccbda0ceeb"
};

// Firebase ni ishga tushirish
firebase.initializeApp(firebaseConfig);

// Firebase Messaging servisini olish
const messaging = firebase.messaging();

// ============================================
// Background Notification Handler
// ============================================
// Bu funksiya brauzer yopiq yoki background da bo'lganda ishlaydi
messaging.onBackgroundMessage((payload) => {
    console.log('[Service Worker] Background message received:', payload);

    // Notification title va options
    const notificationTitle = payload.notification?.title || 'Yangi Xabar';
    const notificationOptions = {
        body: payload.notification?.body || 'Sizga yangi xabar keldi',
        icon: '/favicon.ico',  // Notification icon
        badge: '/favicon.ico', // Badge icon (Android)
        data: payload.data,    // Qo'shimcha ma'lumotlar
        tag: 'firebase-notification', // Bir xil tag'li notificationlar birlashadi
        requireInteraction: false // Avtomatik yopiladi
    };

    // Notification ko'rsatish
    return self.registration.showNotification(notificationTitle, notificationOptions);
});

// ============================================
// Notification Click Handler
// ============================================
// Foydalanuvchi notification'ga bosganda nima qilish kerak
self.addEventListener('notificationclick', (event) => {
    console.log('[Service Worker] Notification clicked:', event);

    // Notification'ni yopish
    event.notification.close();

    // Agar post_id bo'lsa, o'sha postga o'tish
    if (event.notification.data && event.notification.data.post_id) {
        const postUrl = '/posts/' + event.notification.data.post_id;

        // Brauzer oynasini ochish yoki mavjud oynaga o'tish
        event.waitUntil(
            clients.matchAll({ type: 'window', includeUncontrolled: true })
                .then((clientList) => {
                    // Agar ochiq oyna bo'lsa, unga o'tish
                    for (let client of clientList) {
                        if (client.url.includes(postUrl) && 'focus' in client) {
                            return client.focus();
                        }
                    }
                    // Aks holda yangi oyna ochish
                    if (clients.openWindow) {
                        return clients.openWindow(postUrl);
                    }
                })
        );
    }
});

console.log('[Service Worker] Firebase messaging service worker loaded');
