# 📱 Firebase & API Documentation for Mobile App

Ushbu hujjat **Laravel Backend** va **Mobile Ilova** (Flutter/React Native) o'rtasidagi integratsiyani tushuntiradi.

---

## 🌐 Base Configuration

- **Base URL:** `http://sizning-domeningiz.com/api` (Localhost: `http://10.0.2.2:8000/api` Android Emulator uchun)
- **Headers:**
  - `Accept: application/json` (Barcha so'rovlar uchun shart)
  - `Content-Type: application/json`
  - `Authorization: Bearer <token>` (Himoyalangan so'rovlar uchun)

---

## 🔐 Authentication (Auth)

Mobile ilova kirish paytida `fcm_token` ni yuborishi tavsiya etiladi. Shunda backend avtomatik ravishda tokenni saqlab ketadi.

### 1. Login
Foydalanuvchi kirishi va token olishi uchun.

- **Endpoint:** `POST /login`
- **Body:**
```json
{
    "email": "user@example.com",
    "password": "password123",
    "fcm_token": "d3a... (Firebase dan olingan uzun token)"  // IXTIYORIY, lekin tavsiya etiladi
}
```

- **Success Response (200 OK):**
```json
{
    "success": true,
    "message": "Xush kelibsiz!",
    "access_token": "1|AbCdEfGhIjKlMnOpQrStUvWxYz...", // ⚠️ Bu tokenni saqlab qo'ying!
    "token_type": "Bearer",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "fcm_token": "d3a..."
    }
}
```

### 2. Register
Yangi foydalanuvchi ro'yxatdan o'tishi uchun.

- **Endpoint:** `POST /register`
- **Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "fcm_token": "d3a..." // IXTIYORIY
}
```

---

## 🔔 Notification System

### 1. FCM Tokenni Yangilash
Agar user login qilgandan keyin token o'zgarsa (yoki login paytida yuborilmagan bo'lsa), ushbu endpoint orqali yangilash mumkin.

- **Endpoint:** `POST /save-fcm-token`
- **Headers:** `Authorization: Bearer <access_token>`
- **Body:**
```json
{
    "token": "yangi_fcm_token_string..."
}
```

### 2. Notification Payload (Backend -> Mobile)
Backend yangi post yaratilganda Mobile ilovaga quyidagi formatda ma'lumot yuboradi:

**Notification:**
- **Title:** "Yangi Post! 🎉"
- **Body:** "User Name: Post sarlavhasi..."

**Data (Payload):**
Mobile ilova `onMessageOpenedApp` da ushbu ma'lumotlarni oladi:

```json
{
    "type": "new_post",
    "post_id": "15",
    "click_action": "FLUTTER_NOTIFICATION_CLICK"
}
```

**Mobile Logic (Flutter Example):**
```dart
if (message.data['type'] == 'new_post') {
  Navigator.pushNamed(context, '/post_detail', arguments: message.data['post_id']);
}
```

---

## 🧪 Postman Collection (Import qilish uchun)

Quyidagi JSON ni Postman ga import qilib, test qilishingiz mumkin:

```json
{
	"info": {
		"name": "Laravel Firebase API",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
	},
	"item": [
		{
			"name": "Login",
			"request": {
				"method": "POST",
				"header": [
					{ "key": "Accept", "value": "application/json", "type": "text" }
				],
				"body": {
					"mode": "raw",
					"raw": "{\n    \"email\": \"test@example.com\",\n    \"password\": \"password\",\n    \"fcm_token\": \"dummy_token_123\"\n}",
					"options": { "raw": { "language": "json" } }
				},
				"url": { "raw": "{{base_url}}/login", "host": ["{{base_url}}"], "path": ["login"] }
			}
		},
		{
			"name": "Update Token",
			"request": {
				"method": "POST",
				"header": [
					{ "key": "Accept", "value": "application/json", "type": "text" },
					{ "key": "Authorization", "value": "Bearer <YOUR_TOKEN>", "type": "text" }
				],
				"body": {
					"mode": "raw",
					"raw": "{\n    \"token\": \"new_fcm_token_here\"\n}",
					"options": { "raw": { "language": "json" } }
				},
				"url": { "raw": "{{base_url}}/save-fcm-token", "host": ["{{base_url}}"], "path": ["save-fcm-token"] }
			}
		}
	]
}
```
