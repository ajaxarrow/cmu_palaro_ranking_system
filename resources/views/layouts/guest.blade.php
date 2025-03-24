<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'CMU Palaro Ranking System') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Phosphor Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/phosphor-icons/2.0.2/css/phosphor.min.css">

    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --primary-color: #00491e;
                --secondary-color: #02681e;
                --accent-color: #ffc601;
                --highlight-color: #ffc601;
            }

            body {
                font-family: 'Figtree', sans-serif;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                margin: 0;
                padding: 0;
                height: 100vh;
                overflow: hidden;
                position: relative;
            }

            body::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
                z-index: 0;
            }

            .login-container {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                position: relative;
                z-index: 1;
                padding: 20px;
            }

            .logo-container {
                margin-bottom: 30px;
                perspective: 1000px;
            }

            .logo-container a {
                display: flex;
                align-items: center;
                gap: 12px;
                text-decoration: none;
                transform-style: preserve-3d;
                transition: transform 0.6s ease-out, color 0.3s ease;
                padding: 10px 20px;
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border-radius: 50px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .logo-container:hover a {
                transform: scale(1.05);
            }

            .logo-icon {
                color: var(--accent-color);
                font-size: 32px;
                filter: drop-shadow(0 0 10px rgba(255, 198, 1, 0.5));
                transition: transform 0.5s ease;
            }

            .logo-container:hover .logo-icon {
                transform: rotate(10deg);
            }

            .logo-text {
                color: white;
                font-size: 20px;
                font-weight: 600;
                letter-spacing: 0.5px;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }

            .card {
                width: 100%;
                max-width: 420px;
                background: rgba(255, 255, 255, 0.95);
                border-radius: 16px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2), 0 5px 15px rgba(0, 0, 0, 0.05);
                backdrop-filter: blur(5px);
                overflow: hidden;
                transform: translateY(0);
                transition: transform 0.4s ease, box-shadow 0.4s ease;
                border-top: 5px solid var(--accent-color);
                padding: 2rem;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 15px 20px rgba(0, 0, 0, 0.1);
            }

            /* Form elements styling */
            input[type="text"],
            input[type="email"],
            input[type="password"] {
                width: 100%;
                padding: 12px 16px;
                border-radius: 8px;
                border: 2px solid #e5e7eb;
                transition: all 0.3s;
                font-size: 16px;
                margin-bottom: 16px;
                background-color: #f9fafb;
            }

            input[type="text"]:focus,
            input[type="email"]:focus,
            input[type="password"]:focus {
                border-color: var(--secondary-color);
                box-shadow: 0 0 0 3px rgba(2, 104, 30, 0.15);
                outline: none;
            }

            label {
                font-weight: 500;
                display: block;
                margin-bottom: 8px;
                color: #374151;
            }

            button[type="submit"] {
                width: 100%;
                padding: 12px;
                background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
                color: white;
                border: none;
                border-radius: 8px;
                font-weight: 600;
                font-size: 16px;
                cursor: pointer;
                transition: all 0.3s;
                position: relative;
                overflow: hidden;
                margin-top: 10px;
            }

            button[type="submit"]::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: left 0.7s;
            }

            button[type="submit"]:hover {
                box-shadow: 0 5px 15px rgba(0, 73, 30, 0.4);
                transform: translateY(-2px);
            }

            button[type="submit"]:hover::before {
                left: 100%;
            }

            .checkbox-container {
                display: flex;
                align-items: center;
                margin-bottom: 16px;
            }

            .checkbox-container input[type="checkbox"] {
                margin-right: 8px;
                accent-color: var(--secondary-color);
            }

            .forgot-password {
                color: var(--secondary-color);
                text-decoration: none;
                font-size: 14px;
                transition: all 0.3s;
                display: inline-block;
                margin-top: 8px;
            }

            .forgot-password:hover {
                color: var(--primary-color);
                transform: translateX(3px);
            }

            .register-link {
                text-align: center;
                margin-top: 20px;
                font-size: 14px;
                color: #6b7280;
            }

            .register-link a {
                color: var(--secondary-color);
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s;
            }

            .register-link a:hover {
                color: var(--primary-color);
                text-decoration: underline;
            }

            /* Animated background shapes */
            .shape {
                position: absolute;
                background-color: var(--accent-color);
                opacity: 0.1;
                border-radius: 50%;
                z-index: 0;
            }

            .shape-1 {
                width: 300px;
                height: 300px;
                top: -150px;
                right: -100px;
                animation: float 20s ease-in-out infinite;
            }

            .shape-2 {
                width: 200px;
                height: 200px;
                bottom: -100px;
                left: -50px;
                animation: float 15s ease-in-out infinite reverse;
            }

            .shape-3 {
                width: 150px;
                height: 150px;
                bottom: 50%;
                right: 10%;
                animation: float 25s ease-in-out infinite 5s;
            }

            @keyframes float {
                0% { transform: translate(0, 0) rotate(0deg); }
                25% { transform: translate(20px, -20px) rotate(5deg); }
                50% { transform: translate(0, 10px) rotate(0deg); }
                75% { transform: translate(-20px, -10px) rotate(-5deg); }
                100% { transform: translate(0, 0) rotate(0deg); }
            }

            /* For Phosphor icons */
            .fs-24 {
                font-size: 24px;
            }

            .fs-18 {
                font-size: 18px;
            }

            /* Responsive adjustments */
            @media (max-width: 640px) {
                .card {
                    padding: 1.5rem;
                }

                .logo-container a {
                    padding: 8px 16px;
                }

                .logo-icon {
                    font-size: 28px;
                }

                .logo-text {
                    font-size: 18px;
                }
            }
        </style>
    </head>
    <body>
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>

        <div class="login-container">
            <div class="logo-container">
                <a href="/" class="d-flex align-items-center gap-1">
                    <i class="ph-duotone ph-ranking logo-icon"></i>
                    <span class="logo-text">
                        Ranking System
                    </span>
                </a>
            </div>

            <div class="card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
