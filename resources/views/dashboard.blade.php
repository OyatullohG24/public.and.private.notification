<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            max-width: 1200px;
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
            align-items: center;
            gap: 16px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: #374151;
            font-size: 16px;
        }

        .user-email {
            color: #6b7280;
            font-size: 13px;
        }

        .btn-logout {
            padding: 10px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-logout:active {
            transform: translateY(0);
        }

        .content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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

        .welcome-section {
            text-align: center;
            padding: 60px 20px;
        }

        .welcome-icon {
            font-size: 80px;
            margin-bottom: 24px;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .welcome-section h2 {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .welcome-section p {
            font-size: 16px;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-top: 40px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 28px;
            border-radius: 16px;
            color: white;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
        }

        .stat-icon {
            font-size: 36px;
            margin-bottom: 12px;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
        }

        .logout-form {
            display: inline;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .header-right {
                flex-direction: column;
                width: 100%;
            }

            .user-info {
                text-align: center;
            }

            .btn-logout {
                width: 100%;
            }

            .stats-grid {
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
                <h1>Dashboard</h1>
                <p>Boshqaruv paneli</p>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-email">{{ Auth::user()->email }}</div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout">Chiqish</button>
                </form>
            </div>
        </div>

        <div class="content">
            <div class="welcome-section">
                <div class="welcome-icon">🎉</div>
                <h2>Xush kelibsiz, {{ Auth::user()->name }}!</h2>
                <p>Siz muvaffaqiyatli tizimga kirdingiz. Bu sizning shaxsiy boshqaruv panelingiz. Bu yerda siz
                    o'zingizning ma'lumotlaringizni boshqarishingiz mumkin.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card" onclick="window.location='{{ route('posts.index') }}'" style="cursor: pointer;">
                    <div class="stat-icon">📝</div>
                    <div class="stat-label">Mening Postlarim</div>
                    <div class="stat-value">{{ Auth::user()->posts()->count() }}</div>
                </div>

                <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="stat-icon">📧</div>
                    <div class="stat-label">Email</div>
                    <div class="stat-value">Tasdiqlangan</div>
                </div>

                <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="stat-icon">🔐</div>
                    <div class="stat-label">Xavfsizlik</div>
                    <div class="stat-value">Himoyalangan</div>
                </div>
            </div>
        </div>
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
                animation: slideIn 0.3s ease-out;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .notification-toast:hover {
                transform: translateX(-5px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            }
            
            @keyframes slideIn {
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
                flex-shrink: 0;
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
            
            .notification-close {
                background: none;
                border: none;
                color: #9ca3af;
                font-size: 20px;
                cursor: pointer;
                padding: 0;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                transition: all 0.2s ease;
            }
            
            .notification-close:hover {
                background: #f3f4f6;
                color: #374151;
            }
        `;
        document.head.appendChild(style);

        // Show Notification Function
        function showNotification(data) {
            const container = document.getElementById('notificationContainer');

            const toast = document.createElement('div');
            toast.className = 'notification-toast';
            toast.innerHTML = `
                <div class="notification-icon">🎉</div>
                <div class="notification-content">
                    <div class="notification-title">Yangi Post!</div>
                    <div class="notification-message">${data.author}: ${data.title}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">×</button>
            `;

            toast.onclick = function() {
                window.location.href = '/posts/' + data.id;
            };

            container.appendChild(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.style.animation = 'slideIn 0.3s ease-out reverse';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Wait for Echo to be initialized
        const initEcho = () => {
            if (window.Echo) {
                window.Echo.channel('posts')
                    .listen('.new-post', (data) => {
                        console.log('Yangi post:', data);
                        showNotification(data);
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
