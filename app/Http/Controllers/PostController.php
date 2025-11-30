<?php

namespace App\Http\Controllers;


use App\Events\PrivateNotificationEvent;

use App\Models\Post;
use App\Events\NewPostEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(12);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Sarlavha kiritilishi shart',
            'title.max' => 'Sarlavha juda uzun',
            'content.required' => 'Matn kiritilishi shart',
            'image.image' => 'Faqat rasm yuklash mumkin',
            'image.mimes' => 'Rasm formati: jpeg, png, jpg, gif',
            'image.max' => 'Rasm hajmi 2MB dan oshmasligi kerak',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create($validated);

        // Debug: Log qilish
        Log::info('Post yaratildi', ['post_id' => $post->id, 'title' => $post->title]);

        // 1. Public Notification (Hamma uchun)
        broadcast(new NewPostEvent($post));

        // 2. Private Notification (Faqat ID=2 user uchun)
        // Agar post yaratgan odam ID=2 bo'lmasa, unga xabar yuboramiz
        if (Auth::id() !== 2) {
            broadcast(new PrivateNotificationEvent(2, [
                'title' => 'Yangi Shaxsiy Xabar!',
                'message' => Auth::user()->name . ' yangi post yaratdi: ' . $post->title,
                'post_id' => $post->id
            ]));
            Log::info('Private broadcast user:2 ga yuborildi');
        }

        Log::info('Broadcast yuborildi');

        return redirect()->route('posts.index')->with('success', 'Post muvaffaqiyatli yaratildi!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load('user');
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Check if user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Bu postni tahrirlash huquqingiz yo\'q');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Check if user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Bu postni tahrirlash huquqingiz yo\'q');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Sarlavha kiritilishi shart',
            'title.max' => 'Sarlavha juda uzun',
            'content.required' => 'Matn kiritilishi shart',
            'image.image' => 'Faqat rasm yuklash mumkin',
            'image.mimes' => 'Rasm formati: jpeg, png, jpg, gif',
            'image.max' => 'Rasm hajmi 2MB dan oshmasligi kerak',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);

        return redirect()->route('posts.show', $post)->with('success', 'Post muvaffaqiyatli yangilandi!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Check if user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Bu postni o\'chirish huquqingiz yo\'q');
        }

        // Delete image if exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post muvaffaqiyatli o\'chirildi!');
    }
}
