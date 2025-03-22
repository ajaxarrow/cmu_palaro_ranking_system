    
    
    
    <style>
    :root {
            --primary-color: #380505;
            --secondary-color: #fef202;
            --tertiary-color: #ff7605;
            --quaternary-color: #920000;
            --quinary-color: #fff7f7;
            --liner-gradient: linear-gradient(
                to bottom,
                rgba(78, 3, 3, 0.788) 0%,
                rgba(43, 1, 1, 0.986) 80%,
                rgba(43, 1, 1, 0.986) 100%
            );
        }

 /* Loading Screen Styles */
 .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--primary-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.8s cubic-bezier(0.65, 0, 0.35, 1), visibility 0.8s cubic-bezier(0.65, 0, 0.35, 1);
        }

        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            transform: translateY(0);
            transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .loading-screen.hidden .loading-container {
            transform: translateY(-40px);
        }

        .loading-logo {
            margin-bottom: 50px;
            transform-origin: center;
            animation: float 3s infinite ease-in-out;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.3));
        }

        .loading-logo svg {
            width: 120px;
            height: 120px;
            transform-origin: center;
            animation: rotate 15s infinite linear;
        }

        .loading-animation {
            position: relative;
            width: 240px;
            height: 8px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) inset;
        }

        .loading-bar {
            position: absolute;
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, var(--tertiary-color), var(--secondary-color));
            border-radius: 20px;
            transition: width 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 0 10px rgba(255, 118, 5, 0.7);
        }

        .loading-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent 0%,
                rgba(255, 255, 255, 0.4) 50%,
                transparent 100%
            );
            animation: shimmer 1.5s infinite;
        }

        .loading-text {
            font-size: 18px;
            font-weight: 700;
            color: white;
            text-align: center;
            letter-spacing: 2px;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            transform: translateZ(0);
        }

        .loading-percentage {
            font-size: 16px;
            color: var(--secondary-color);
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
            height: 20px;
            margin-bottom: 40px;
        }

        .percentage-number {
            display: inline-block;
            min-width: 40px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .sports-icon {
            display: flex;
            justify-content: center;
            perspective: 800px;
            transform-style: preserve-3d;
        }

        .sports-icon div {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            margin: 0 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translateY(20px);
            animation: icon-float 3s infinite ease-in-out;
            backdrop-filter: blur(4px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2),
                        0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            transition: all 0.3s ease;
        }

        .sports-icon div:hover {
            transform: translateY(10px) scale(1.1);
            background: rgba(255, 255, 255, 0.2);
        }

        .sports-icon div:nth-child(1) {
            animation-delay: 0s;
        }

        .sports-icon div:nth-child(2) {
            animation-delay: 0.3s;
        }

        .sports-icon div:nth-child(3) {
            animation-delay: 0.6s;
        }

        .sports-icon div:nth-child(4) {
            animation-delay: 0.9s;
        }

        .sports-icon svg {
            width: 24px;
            height: 24px;
            fill: white;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.3));
            transition: transform 0.3s ease;
        }

        .sports-icon div:hover svg {
            transform: scale(1.2);
        }

        /* Main Content (hidden initially) */
        .main-content {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 1s ease-in, transform 1s ease-out;
            min-height: 100vh;
            padding: 20px;
        }

        .main-content.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Animations */
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes icon-float {
            0%, 100% {
                transform: translateY(10px);
                opacity: 0.8;
            }
            50% {
                transform: translateY(-5px);
                opacity: 1;
            }
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /* Hidden class */
        .loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        /* Particles background */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: var(--tertiary-color);
            border-radius: 50%;
            opacity: 0.3;
            pointer-events: none;
        }
    </style>
    <!-- Loading Screen -->
    
    <div class="loading-screen" id="loadingScreen">
        <div class="particles" id="particles"></div>
        <div class="loading-container">
            <div class="loading-logo">
            <img style="width: 150px;" src="{{ Vite::asset('resources/images/palaroLogo.png') }}" alt="CMU PALARO Logo">

            </div>
            <div class="loading-animation">
                <div class="loading-bar" id="loadingBar"></div>
            </div>
            <div class="loading-text">LOADING SPORTS TALLY</div>
            <div class="loading-percentage">
                <span id="loadingPercentage" class="percentage-number">0%</span>
            </div>
            
            <div class="sports-icon">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,2C6.48,2,2,6.48,2,12c0,5.52,4.48,10,10,10s10-4.48,10-10C22,6.48,17.52,2,12,2z M16.3,14.29L12,12l-4.3,2.29c-0.58,0.31-1.28,0.09-1.59-0.49c-0.31-0.58-0.09-1.28,0.49-1.59L11.7,9.71c0.18-0.1,0.38-0.15,0.58-0.15c0.2,0,0.4,0.05,0.58,0.15l5.11,2.71c0.58,0.31,0.79,1.01,0.49,1.59C17.57,14.38,16.87,14.6,16.3,14.29z"/>
                    </svg>
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,2C6.48,2,2,6.48,2,12c0,5.52,4.48,10,10,10s10-4.48,10-10C22,6.48,17.52,2,12,2z M12,5c1.93,0,3.5,1.57,3.5,3.5S13.93,12,12,12s-3.5-1.57-3.5-3.5S10.07,5,12,5z M12,19c-2.03,0-4.43-0.82-6.14-2.88C7.55,15.8,9.68,15,12,15s4.45,0.8,6.14,1.12C16.43,18.18,14.03,19,12,19z"/>
                    </svg>
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M14.27,6C13.72,6.95,14.05,8.18,15,8.73c0.95,0.55,2.18,0.22,2.73-0.73c0.55-0.95,0.22-2.18-0.73-2.73 C16.05,4.72,14.82,5.05,14.27,6z M15.84,10.41c0,0-1.63-0.94-2.6-1.5c-2.38-1.38-3.2-4.44-1.82-6.82l-1.73-1 C8.1,3.83,8.6,7.21,10.66,9.4l-5.15,8.92l1.73,1l1.5-2.6l1.73,1l-1.5,2.6l1.73,1l1.5-2.6l1.73,1l-1.5,2.6l1.73,1l5.15-8.92 c1.38,2.38,4.44,3.2,6.82,1.82L22,15.95C19.26,18.53,15.38,18.03,13.18,15L15.84,10.41z M3.16,10.58L5,11.64L6.74,10.5 l-1.82-1.07L3.16,10.58z M2.05,13.41l1.82,1.07L5.68,13.3l-1.82-1.07L2.05,13.41z M0.93,16.25l1.82,1.07l1.82-1.07 l-1.82-1.07L0.93,16.25z"/>
                    </svg>
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,2C6.48,2,2,6.48,2,12c0,5.52,4.48,10,10,10s10-4.48,10-10C22,6.48,17.52,2,12,2z M13,5.3l1.35-0.95 c1.82,0.56,3.37,1.76,4.38,3.34l-0.39,1.34l-1.35,0.46L13,6.7V5.3z M9.65,4.35L11,5.3v1.4L7.01,9.49L5.66,9.03L5.27,7.69 C6.28,6.12,7.83,4.92,9.65,4.35z M7.08,17.11l-1.14,0.1C4.73,15.81,4,13.99,4,12c0-0.12,0.01-0.23,0.02-0.35l1-0.73L6.4,11.4 l1.46,4.34L7.08,17.11z M14.5,19.59C13.71,19.85,12.87,20,12,20s-1.71-0.15-2.5-0.41l-0.69-1.49L9.45,17h5.11l0.64,1.11 L14.5,19.59z M15.2,15H8.8l-2-6h10.4L15.2,15z M16.92,17.11l-0.78-1.36l1.46-4.34l1.39-0.47l1,0.73C19.99,11.77,20,11.88,20,12 c0,1.99-0.73,3.81-1.94,5.21L16.92,17.11z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    