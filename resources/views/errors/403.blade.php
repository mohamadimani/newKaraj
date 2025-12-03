<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>صفحه 403</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/css/rtl/core.css') }}">
    <!-- Fonts -->

    <style>
        body {
            background: linear-gradient(135deg, #000, #222);
            font-family: yekan;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .container-404 {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 50px 30px;
            border-radius: 25px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            max-width: 700px;
            width: 90%;
            position: relative;
            overflow: hidden;
        }

        /* عدد 404 با افکت طلایی و انیمیشنی */
        .number404 {
            font-size: 12rem;
            font-weight: bold;
            color: #FFD700;
            /* طلایی */
            text-shadow: 0 0 20px #FFD700, 0 0 40px #FFD700;
            animation: glowGold 2s infinite alternate;
        }

        @keyframes glowGold {
            from {
                text-shadow: 0 0 20px #FFD700, 0 0 40px #FFD700;
            }

            to {
                text-shadow: 0 0 40px #FFDD33, 0 0 80px #FFDD33;
            }
        }

        /* پیام خطای */
        .error-text {
            font-size: 1.8rem;
            margin-top: 20px;
            color: #ddd;
            font-family: yekan;
            font-weight: bold;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* لینک بازگشت با استایل مشکی و طلایی */
        .btn-custom {
            margin-top: 30px;
            padding: 14px 35px;
            font-size: 1.4rem;
            border-radius: 40px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #222;
            font-weight: bold;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
            border: none;
            transition: all 0.3s ease;
        }

        /* اثر hover */
        .btn-custom:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            background: linear-gradient(135deg, #FFC107, #FF8C00);
        }

        /* استایل ریسپانسیو */
        @media(max-width: 768px) {
            .number404 {
                font-size: 8rem;
            }
        }
    </style>
</head>

<body>

    <div class="container-404 text-center text-light">
        <div class="number404">403</div>
        <div class="error-text">شما مجاز به دسترسی به این صفحه نیستید</div>
        <a href="/" class="btn btn-custom mt-4">بازگشت به صفحه اصلی</a>
    </div>

</body>

</html>