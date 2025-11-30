<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $post->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
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
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            color: #374151;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .back-button:hover {
            transform: translateX(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .post-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .post-header {
            position: relative;
            height: 400px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
        }

        .post-header img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .post-header-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 40px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        }

        .post-title {
            font-size: 36px;
            font-weight: 800;
            color: white;
            margin-bottom: 16px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .post-meta {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .author-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .author-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 20px;
            border: 3px solid white;
        }

        .author-details {
            display: flex;
            flex-direction: column;
        }

        .author-name {
            font-size: 16px;
            font-weight: 700;
            color: white;
        }

        .post-date {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
        }

        .post-content {
            padding: 48px 40px;
        }

        .post-text {
            font-size: 17px;
            line-height: 1.8;
            color: #374151;
            white-space: pre-wrap;
        }

        .post-actions {
            padding: 24px 40px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
        }

        .alert {
            margin: 20px 0;
            padding: 16px 20px;
            border-radius: 12px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        @media (max-width: 768px) {
            .post-header {
                height: 250px;
            }

            .post-title {
                font-size: 24px;
            }

            .post-content {
                padding: 32px 24px;
            }

            .post-actions {
                padding: 20px 24px;
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('posts.index') }}" class="back-button">
            ← Orqaga
        </a>

        @if (session('success'))
            <div class="alert alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="post-container">
            <div class="post-header">
                @if ($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                @endif
                <div class="post-header-overlay">
                    <h1 class="post-title">{{ $post->title }}</h1>
                    <div class="post-meta">
                        <div class="author-info">
                            <div class="author-avatar">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                            <div class="author-details">
                                <span class="author-name">{{ $post->user->name }}</span>
                                <span class="post-date">{{ $post->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="post-content">
                <p class="post-text">{{ $post->content }}</p>
            </div>

            @if (Auth::id() === $post->user_id)
                <div class="post-actions">
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">
                        ✏️ Tahrirlash
                    </a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST"
                        onsubmit="return confirm('Rostdan ham bu postni o\'chirmoqchimisiz?')" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            🗑️ O'chirish
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</body>

</html>
