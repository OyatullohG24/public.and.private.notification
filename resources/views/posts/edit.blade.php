<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Postni Tahrirlash</title>
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
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px 32px;
            margin-bottom: 24px;
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

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 4px;
        }

        .header p {
            color: #6b7280;
            font-size: 14px;
        }

        .form-card {
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

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        textarea {
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
        textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        textarea {
            min-height: 200px;
            resize: vertical;
        }

        .current-image {
            margin-bottom: 16px;
            border-radius: 12px;
            overflow: hidden;
        }

        .current-image img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
        }

        .current-image-label {
            display: block;
            margin-bottom: 8px;
            color: #6b7280;
            font-size: 13px;
        }

        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-upload input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 40px 20px;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            background: #f9fafb;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-label:hover {
            border-color: #667eea;
            background: #f3f4f6;
        }

        .file-upload-icon {
            font-size: 32px;
        }

        .file-upload-text {
            text-align: center;
        }

        .file-upload-text strong {
            display: block;
            color: #374151;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .file-upload-text span {
            color: #6b7280;
            font-size: 13px;
        }

        .image-preview {
            margin-top: 16px;
            display: none;
            position: relative;
        }

        .image-preview img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 12px;
        }

        .remove-image {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .remove-image:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        .error-message {
            color: #ef4444;
            font-size: 13px;
            margin-top: 6px;
        }

        .input-error {
            border-color: #ef4444 !important;
            background: #fef2f2 !important;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex: 1;
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

        @media (max-width: 768px) {
            .form-card {
                padding: 24px;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>✏️ Postni Tahrirlash</h1>
            <p>Postingizni yangilang</p>
        </div>

        <div class="form-card">
            <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data"
                id="postForm">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Sarlavha *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}"
                        placeholder="Post sarlavhasini kiriting" class="@error('title') input-error @enderror">
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content">Matn *</label>
                    <textarea id="content" name="content" placeholder="Post matnini kiriting..."
                        class="@error('content') input-error @enderror">{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Rasm</label>

                    @if ($post->image)
                        <span class="current-image-label">Hozirgi rasm:</span>
                        <div class="current-image">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                        </div>
                    @endif

                    <div class="file-upload">
                        <input type="file" id="image" name="image" accept="image/*"
                            onchange="previewImage(event)">
                        <label for="image" class="file-upload-label" id="uploadLabel">
                            <div class="file-upload-icon">🖼️</div>
                            <div class="file-upload-text">
                                <strong>{{ $post->image ? 'Rasmni almashtirish' : 'Rasm yuklash' }}</strong>
                                <span>Yoki bu yerga sudrab tashlang</span>
                            </div>
                        </label>
                    </div>
                    <div class="image-preview" id="imagePreview">
                        <img src="" alt="Preview" id="previewImg">
                        <button type="button" class="remove-image" onclick="removeImage()">✕</button>
                    </div>
                    @error('image')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        ✅ Yangilash
                    </button>
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">
                        ❌ Bekor qilish
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                    document.getElementById('uploadLabel').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('uploadLabel').style.display = 'flex';
        }
    </script>
</body>

</html>
