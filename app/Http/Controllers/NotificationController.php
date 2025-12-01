<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Firebase Notification Controller
 * 
 * Bu controller Firebase Cloud Messaging bilan ishlaydi:
 * 1. FCM tokenlarni saqlash
 * 2. Test notification yuborish
 */
class NotificationController extends Controller
{
    /**
     * FCM tokenni bazaga saqlash
     * 
     * Frontend dan kelgan FCM tokenni joriy userning bazasiga saqlaymiz.
     * Bu token orqali keyinchalik notification yuboramiz.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveFcmToken(Request $request)
    {
        // 1. Fayl mavjudligini tekshirish
        $path = storage_path('app/firebase.json');
        if (!file_exists($path)) {
            \Illuminate\Support\Facades\Log::error('Firebase JSON fayli topilmadi: ' . $path);
            return response()->json(['success' => false, 'message' => 'Server xatosi: firebase.json topilmadi'], 500);
        }

        // 2. JSON validatsiyasini tekshirish
        $content = file_get_contents($path);
        $json = json_decode($content);
        if ($json === null) {
            \Illuminate\Support\Facades\Log::error('Firebase JSON formati noto\'g\'ri');
            return response()->json(['success' => false, 'message' => 'Server xatosi: firebase.json formati noto\'g\'ri'], 500);
        }

        \Illuminate\Support\Facades\Log::info('FCM Token saqlash so\'rovi keldi', ['user_id' => auth()->id()]);

        // Tokenni validatsiya qilamiz
        $request->validate([
            'token' => 'required|string'
        ]);

        // Joriy userning fcm_token ustuniga saqlaymiz
        auth()->user()->update([
            'fcm_token' => $request->token
        ]);

        return response()->json([
            'success' => true,
            'message' => 'FCM token muvaffaqiyatli saqlandi'
        ]);
    }

    /**
     * Test notification yuborish
     * 
     * Barcha userlarga (fcm_token bor bo'lganlarga) test notification yuboradi.
     * Bu funksiya test qilish uchun ishlatiladi.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendTestNotification(Request $request)
    {
        // Title va body ni validatsiya qilamiz
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        // Barcha userlarning FCM tokenlarini olamiz (null bo'lmaganlarini)
        $tokens = User::whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();

        // Agar hech qanday token bo'lmasa
        if (empty($tokens)) {
            return response()->json([
                'success' => false,
                'message' => 'Hech qanday FCM token topilmadi. Avval brauzerda notification ruxsatini bering.'
            ], 404);
        }

        try {
            // Firebase Messaging servisini olamiz
            $messaging = app('firebase.messaging');

            // Notification xabarini yaratamiz
            $message = CloudMessage::new()
                ->withNotification(
                    Notification::create($request->title, $request->body)
                )
                ->withData([
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'test'
                ]);

            // Hamma tokenlarga yuborish (Multicast)
            $result = $messaging->sendMulticast($message, $tokens);

            return response()->json([
                'success' => true,
                'message' => 'Notification yuborildi',
                'sent_count' => count($tokens),
                'success_count' => $result->successes()->count(),
                'failure_count' => $result->failures()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xatolik: ' . $e->getMessage()
            ], 500);
        }
    }
}
