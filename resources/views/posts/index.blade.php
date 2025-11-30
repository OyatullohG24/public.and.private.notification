<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barcha Postlar</title>
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
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px 32px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-left h1 {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 4px;
        }

        .header-left p {
            color: #6b7280;
            font-size: 14px;
        }

        .header-right {
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

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .alert {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease;
        }

        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .post-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .post-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .post-content {
            padding: 24px;
        }

        .post-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .post-excerpt {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .post-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .post-author {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .author-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .author-info {
            display: flex;
            flex-direction: column;
        }

        .author-name {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .post-date {
            font-size: 12px;
            color: #9ca3af;
        }

        .empty-state {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 80px 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .empty-icon {
            font-size: 80px;
            margin-bottom: 24px;
        }

        .empty-state h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .empty-state p {
            color: #6b7280;
            margin-bottom: 24px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 32px;
        }

        .pagination a,
        .pagination span {
            padding: 10px 16px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            color: #374151;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: white;
            transform: translateY(-2px);
        }

        .pagination .active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 16px;
            }

            .header-right {
                width: 100%;
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .posts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Notification Toast Container -->
        <div id="notificationContainer"
            style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;"></div>

        <div class="header">
            <div class="header-left">
                <h1>📝 Barcha Postlar</h1>
                <p>{{ $posts->total() }} ta post mavjud</p>
            </div>
            <div class="header-right">
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    ➕ Yangi Post
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    🏠 Dashboard
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if ($posts->count() > 0)
            <div class="posts-grid">
                @foreach ($posts as $post)
                    <div class="post-card" onclick="window.location='{{ route('posts.show', $post) }}'">
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                class="post-image">
                        @else
                            <div class="post-image"></div>
                        @endif

                        <div class="post-content">
                            <h2 class="post-title">{{ $post->title }}</h2>
                            <p class="post-excerpt">{{ Str::limit($post->content, 150) }}</p>

                            <div class="post-meta">
                                <div class="post-author">
                                    <div class="author-avatar">
                                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                    </div>
                                    <div class="author-info">
                                        <span class="author-name">{{ $post->user->name }}</span>
                                        <span class="post-date">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($posts->hasPages())
                <div class="pagination">
                    {{ $posts->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h2>Hali postlar yo'q</h2>
                <p>Birinchi bo'lib post yarating!</p>
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    ➕ Yangi Post Yaratish
                </a>
            </div>
        @endif
    </div>

    @vite(['resources/js/app.js'])

    <script>
        // Notification Toast Styles
        const style = document.createElement('style');
        style.textContent = `
            .notification-toast {
                background: white;
                border-radius: 12px;
                padding: 16px 20px;
                margin-bottom: 12px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                display: flex;
                align-items: center;
                gap: 12px;
                animation: slideInRight 0.3s ease-out;
            }
            
            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(100%);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            .notification-icon {
                font-size: 28px;
            }
            
            .notification-content {
                flex: 1;
            }
            
            .notification-title {
                font-weight: 600;
                color: #1f2937;
                margin-bottom: 4px;
                font-size: 14px;
            }
            
            .notification-message {
                color: #6b7280;
                font-size: 13px;
            }
        `;
        document.head.appendChild(style);

        // Show Notification and Refresh
        function showNotificationAndRefresh(data) {
            const container = document.getElementById('notificationContainer');

            const toast = document.createElement('div');
            toast.className = 'notification-toast';
            toast.innerHTML = `
                <div class="notification-icon">🎉</div>
                <div class="notification-content">
                    <div class="notification-title">Yangi Post Qo'shildi!</div>
                    <div class="notification-message">${data.author}: ${data.title}</div>
                </div>
            `;

            container.appendChild(toast);

            // Refresh page after 2 seconds
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }

        // Wait for Echo to be initialized
        const initEcho = () => {
            if (window.Echo) {
                window.Echo.channel('posts')
                    .listen('.new-post', (data) => {
                        console.log('Yangi post:', data);
                        showNotificationAndRefresh(data);
                    });
                console.log('Echo listener started');
            } else {
                console.log('Waiting for Echo...');
                setTimeout(initEcho, 100);
            }
        };

        initEcho();
    </script>
</body>

</html>
