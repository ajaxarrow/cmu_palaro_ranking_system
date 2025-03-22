    
    
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Niconne&display=swap');

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
    background: var(--liner-gradient);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
}

.loading-logo {
    margin-bottom: 40px;
    transform-origin: center;
    animation: pulse 1.5s infinite alternate;
}

.loading-logo svg {
    width: 120px;
    height: 120px;
}

.loading-animation {
    position: relative;
    width: 200px;
    height: 6px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 20px;
}

.loading-bar {
    position: absolute;
    width: 0%;
    height: 100%;
    background: var(--tertiary-color);
    border-radius: 10px;
    transition: width 0.3s ease;
}

.loading-text {
    font-size: 16px;
    font-weight: 600;
    color: white;
    text-align: center;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.loading-percentage {
    font-size: 14px;
    color: var(--secondary-color);
    font-weight: 500;
}

.sports-icon {
    display: flex;
    margin-top: 40px;
}

.sports-icon div {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    margin: 0 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0.5;
    transform: translateY(20px);
    animation: icon-bounce 2s infinite;
}

.sports-icon div:nth-child(2) {
    animation-delay: 0.2s;
}

.sports-icon div:nth-child(3) {
    animation-delay: 0.4s;
}

.sports-icon div:nth-child(4) {
    animation-delay: 0.6s;
}

.sports-icon svg {
    width: 20px;
    height: 20px;
    fill: white;
}

/* Main Content (hidden initially) */
.main-content {
    opacity: 0;
    transition: opacity 1s ease-in;
    min-height: 100vh;
    padding: 20px;
}

/* Animations */
@keyframes pulse {
    0% {
        transform: scale(1);
    }
    100% {
        transform: scale(1.1);
    }
}

@keyframes icon-bounce {
    0%, 100% {
        transform: translateY(20px);
        opacity: 0.5;
    }
    50% {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Hidden class */
.hidden {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}
    </style>
    <!-- Loading Screen -->
    
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-logo">
            <img style="width: 150px;" src="{{ Vite::asset('resources/images/palaroLogo.png') }}" alt="CMU PALARO Logo">
        </div>
        <div class="loading-animation">
            <div class="loading-bar" id="loadingBar"></div>
        </div>
        <div class="loading-text">LOADING SPORTS TALLY</div>
        <div class="loading-percentage" id="loadingPercentage">0%</div>
        
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