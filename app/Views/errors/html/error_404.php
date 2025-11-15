<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #16a34a 0%, #4ade80 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            overflow: hidden;
            position: relative;
        }

        /* Animated background shapes */
        .bg-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 20s infinite ease-in-out;
        }

        .shape:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            top: 60%;
            left: 80%;
            animation-delay: 4s;
        }

        .shape:nth-child(3) {
            top: 40%;
            left: 5%;
            animation-delay: 2s;
        }

        .shape:nth-child(4) {
            top: 80%;
            left: 70%;
            animation-delay: 6s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-50px) rotate(180deg);
            }
        }

        .container {
            max-width: 600px;
            width: 90%;
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem 2rem;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-icon {
            width: 180px;
            height: 180px;
            margin: 0 auto 2rem;
            position: relative;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .error-icon .material-icons {
            font-size: 180px;
            color: #16a34a;
            text-shadow: 0 4px 10px rgba(22, 163, 74, 0.3);
        }

        .error-code {
            font-size: 5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #16a34a 0%, #4ade80 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            line-height: 1;
        }

        h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }

        .message {
            font-size: 1rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .error-detail {
            background: #f8f9fa;
            border-left: 4px solid #16a34a;
            padding: 1rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            text-align: left;
            font-size: 0.9rem;
            color: #555;
            word-wrap: break-word;
        }

        .buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-family: 'Poppins', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #16a34a 0%, #4ade80 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(22, 163, 74, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(22, 163, 74, 0.6);
        }

        .btn-secondary {
            background: white;
            color: #16a34a;
            border: 2px solid #16a34a;
        }

        .btn-secondary:hover {
            background: #16a34a;
            color: white;
            transform: translateY(-2px);
        }

        .suggestions {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e0e0e0;
        }

        .suggestions h3 {
            font-size: 1rem;
            color: #666;
            margin-bottom: 1rem;
        }

        .suggestion-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            text-align: left;
        }

        .suggestion-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #555;
            font-size: 0.9rem;
        }

        .suggestion-item .material-icons {
            font-size: 18px;
            color: #667eea;
        }

        @media (max-width: 768px) {
            .container {
                padding: 2rem 1.5rem;
            }

            .error-code {
                font-size: 4rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .error-icon {
                width: 140px;
                height: 140px;
            }

            .error-icon .material-icons {
                font-size: 140px;
            }

            .buttons {
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
    <div class="bg-shapes">
        <div class="shape">
            <i class="material-icons" style="font-size: 120px;">volunteer_activism</i>
        </div>
        <div class="shape">
            <i class="material-icons" style="font-size: 100px;">favorite</i>
        </div>
        <div class="shape">
            <i class="material-icons" style="font-size: 140px;">people</i>
        </div>
        <div class="shape">
            <i class="material-icons" style="font-size: 90px;">star</i>
        </div>
    </div>

    <div class="container">
        <div class="error-icon">
            <i class="material-icons">search_off</i>
        </div>

        <div class="error-code">404</div>

        <h1>Halaman Tidak Ditemukan</h1>

        <p class="message">
            Maaf, halaman yang Anda cari tidak dapat ditemukan. Halaman mungkin telah dipindahkan atau tidak pernah ada.
        </p>

        <?php if (ENVIRONMENT !== 'production') : ?>
            <div class="error-detail">
                <strong>Detail Error:</strong><br>
                <?= nl2br(esc($message ?? 'Page not found')) ?>
            </div>
        <?php endif; ?>

        <div class="buttons">
            <a href="<?= base_url('/') ?>" class="btn btn-primary">
                <i class="material-icons">home</i>
                Kembali ke Beranda
            </a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="material-icons">arrow_back</i>
                Halaman Sebelumnya
            </a>
        </div>

        <div class="suggestions">
            <h3>Yang bisa Anda lakukan:</h3>
            <div class="suggestion-list">
                <div class="suggestion-item">
                    <i class="material-icons">check_circle</i>
                    <span>Periksa kembali URL yang Anda masukkan</span>
                </div>
                <div class="suggestion-item">
                    <i class="material-icons">check_circle</i>
                    <span>Kembali ke halaman utama dan mulai dari awal</span>
                </div>
                <div class="suggestion-item">
                    <i class="material-icons">check_circle</i>
                    <span>Lihat kampanye donasi yang sedang berlangsung</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>