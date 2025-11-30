<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ro'yxatdan o'tish - Register</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 450px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo h1 {
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .logo p {
            color: #6b7280;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            background: #f9fafb;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #f093fb;
            background: white;
            box-shadow: 0 0 0 4px rgba(240, 147, 251, 0.1);
        }

        .error-message {
            color: #ef4444;
            font-size: 13px;
            margin-top: 6px;
            display: none;
        }

        .error-message.show {
            display: block;
            animation: shake 0.3s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .input-error {
            border-color: #ef4444 !important;
            background: #fef2f2 !important;
        }

        .password-strength {
            margin-top: 8px;
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
            display: none;
        }

        .password-strength.show {
            display: block;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak {
            width: 33%;
            background: #ef4444;
        }

        .strength-medium {
            width: 66%;
            background: #f59e0b;
        }

        .strength-strong {
            width: 100%;
            background: #10b981;
        }

        .password-hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
            display: none;
        }

        .password-hint.show {
            display: block;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 24px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(240, 147, 251, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-submit .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .btn-submit.loading .btn-text {
            display: none;
        }

        .btn-submit.loading .spinner {
            display: block;
        }

        .divider {
            text-align: center;
            margin: 24px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e5e7eb;
        }

        .divider span {
            background: rgba(255, 255, 255, 0.95);
            padding: 0 16px;
            color: #6b7280;
            font-size: 14px;
            position: relative;
        }

        .login-link {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        .login-link a {
            color: #f093fb;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #f5576c;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
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

        .alert.show {
            display: block;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="auth-card">
            <div class="logo">
                <h1>Akkaunt yaratish</h1>
                <p>Boshlash uchun ma'lumotlaringizni kiriting</p>
            </div>

            <div id="alert" class="alert"></div>

            <form id="registerForm">
                @csrf
                <div class="form-group">
                    <label for="name">Ism</label>
                    <input type="text" id="name" name="name" placeholder="Ismingiz">
                    <div class="error-message" id="name-error"></div>
                </div>

                <div class="form-group">
                    <label for="email">Email manzil</label>
                    <input type="email" id="email" name="email" placeholder="example@email.com">
                    <div class="error-message" id="email-error"></div>
                </div>

                <div class="form-group">
                    <label for="password">Parol</label>
                    <input type="password" id="password" name="password" placeholder="••••••••">
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="password-hint" id="passwordHint"></div>
                    <div class="error-message" id="password-error"></div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Parolni tasdiqlash</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="••••••••">
                    <div class="error-message" id="password_confirmation-error"></div>
                </div>

                <button type="submit" class="btn-submit">
                    <span class="btn-text">Ro'yxatdan o'tish</span>
                    <div class="spinner"></div>
                </button>
            </form>

            <div class="divider">
                <span>yoki</span>
            </div>

            <div class="login-link">
                Akkauntingiz bormi? <a href="{{ route('login') }}">Kirish</a>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('registerForm');
        const submitBtn = form.querySelector('.btn-submit');
        const alert = document.getElementById('alert');
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');
        const passwordHint = document.getElementById('passwordHint');

        // Password strength checker
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = document.querySelector('.password-strength');

            if (password.length === 0) {
                strength.classList.remove('show');
                passwordHint.classList.remove('show');
                return;
            }

            strength.classList.add('show');
            passwordHint.classList.add('show');

            let score = 0;
            if (password.length >= 6) score++;
            if (password.length >= 10) score++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
            if (/\d/.test(password)) score++;
            if (/[^a-zA-Z\d]/.test(password)) score++;

            strengthBar.className = 'password-strength-bar';
            if (score <= 2) {
                strengthBar.classList.add('strength-weak');
                passwordHint.textContent = 'Zaif parol';
                passwordHint.style.color = '#ef4444';
            } else if (score <= 4) {
                strengthBar.classList.add('strength-medium');
                passwordHint.textContent = 'O\'rtacha parol';
                passwordHint.style.color = '#f59e0b';
            } else {
                strengthBar.classList.add('strength-strong');
                passwordHint.textContent = 'Kuchli parol';
                passwordHint.style.color = '#10b981';
            }
        });

        // Clear error on input
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('input-error');
                const errorEl = document.getElementById(this.name + '-error');
                if (errorEl) {
                    errorEl.classList.remove('show');
                }
                alert.classList.remove('show');
            });
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => el.classList.remove('show'));
            document.querySelectorAll('input').forEach(el => el.classList.remove('input-error'));
            alert.classList.remove('show');

            // Show loading
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            const formData = new FormData(form);

            try {
                const response = await fetch('{{ route('register') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert.className = 'alert alert-success show';
                    alert.textContent = data.message;
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 500);
                } else {
                    if (data.errors) {
                        // Show field errors
                        Object.keys(data.errors).forEach(key => {
                            const input = document.getElementById(key);
                            const errorEl = document.getElementById(key + '-error');
                            if (input && errorEl) {
                                input.classList.add('input-error');
                                errorEl.textContent = data.errors[key][0];
                                errorEl.classList.add('show');
                            }
                        });
                    } else if (data.message) {
                        // Show general error
                        alert.className = 'alert alert-error show';
                        alert.textContent = data.message;
                    }
                }
            } catch (error) {
                alert.className = 'alert alert-error show';
                alert.textContent = 'Xatolik yuz berdi. Iltimos, qaytadan urinib ko\'ring.';
            } finally {
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            }
        });
    </script>
</body>

</html>
