document.addEventListener('DOMContentLoaded', function() {
    // Extract dominant color from flag image
    const teamFlag = document.getElementById('teamFlag');
    if (teamFlag) {
        // Wait for image to load
        teamFlag.onload = function() {
            setTimeout(extractDominantColor, 300);
        };

        // If image is already loaded
        if (teamFlag.complete) {
            setTimeout(extractDominantColor, 300);
        }
    }

    function extractDominantColor() {
        try {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.width = teamFlag.naturalWidth || teamFlag.width;
            canvas.height = teamFlag.naturalHeight || teamFlag.height;
            context.drawImage(teamFlag, 0, 0, canvas.width, canvas.height);

            // Sample colors from the image
            const imageData = context.getImageData(0, 0, canvas.width, canvas.height).data;

            // Simple dominant color detection
            let colorCounts = {};
            let maxCount = 0;
            let dominantColor = { r: 0, g: 73, b: 30 }; // Default color

            // Sample every 10th pixel to improve performance
            for (let i = 0; i < imageData.length; i += 40) {
                const r = imageData[i];
                const g = imageData[i + 1];
                const b = imageData[i + 2];
                const a = imageData[i + 3];

                // Skip transparent pixels
                if (a < 125) continue;

                // Skip very light (white) or very dark (black) colors
                const brightness = (r + g + b) / 3;
                if (brightness < 80 || brightness > 100) continue;

                // Create a key for this color (rounded to reduce variations)
                const colorKey = Math.round(r / 10) * 10 + ',' + Math.round(g / 10) * 10 + ',' + Math.round(b / 10) * 10;

                if (!colorCounts[colorKey]) {
                    colorCounts[colorKey] = 0;
                }

                colorCounts[colorKey]++;

                if (colorCounts[colorKey] > maxCount) {
                    maxCount = colorCounts[colorKey];
                    dominantColor = {
                        r: Math.round(r / 10) * 10,
                        g: Math.round(g / 10) * 10,
                        b: Math.round(b / 10) * 10
                    };
                }
            }

            // Calculate a darker version for contrast
            const darkerColor = {
                r: Math.max(0, dominantColor.r - 30),
                g: Math.max(0, dominantColor.g - 30),
                b: Math.max(0, dominantColor.b - 30)
            };

            // Set the CSS variables
            document.documentElement.style.setProperty('--team-color-rgb', `${dominantColor.r}, ${dominantColor.g}, ${dominantColor.b}`);
            document.documentElement.style.setProperty('--team-color-rgb-dark', `${darkerColor.r}, ${darkerColor.g}, ${darkerColor.b}`);

            console.log('Dominant color set:', dominantColor);
        } catch (e) {
            console.error("Error processing flag image:", e);
        }
    }

    // Animate medal counters
    const countElements = document.querySelectorAll('[data-medal-count]');

    countElements.forEach(element => {
        const targetValue = parseInt(element.dataset.medalCount);
        animateCounter(element, targetValue);
    });

    function animateCounter(element, target, decimals = 0) {
        let start = 0;
        const duration = 1800;
        const startTime = performance.now();

        function updateCounter(currentTime) {
            const elapsedTime = currentTime - startTime;
            const progress = Math.min(elapsedTime / duration, 1);

            // Use an easing function for more natural animation
            const easedProgress = 1 - Math.pow(1 - progress, 3); // Cubic ease-out

            const value = start + (target - start) * easedProgress;

            if (decimals > 0) {
                element.textContent = value.toFixed(decimals);
            } else {
                element.textContent = value;
            }

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            }
        }

        requestAnimationFrame(updateCounter);
    }

    // Add 3D effect to logo
    const logoWrapper = document.querySelector('.team-logo-wrapper');
    if (logoWrapper) {
        const logo = logoWrapper.querySelector('.team-logo');
        const glow = logoWrapper.querySelector('.team-glow');

        logoWrapper.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            // Calculate rotation angle based on mouse position
            const xRotation = y / (rect.height / 2) * -10;
            const yRotation = x / (rect.width / 2) * 10;

            // Apply rotation transformation
            this.style.transform = `rotateX(${xRotation}deg) rotateY(${yRotation}deg)`;

            // Move glow based on mouse position
            if (glow) {
                const glowX = x / 4;
                const glowY = y / 4;
                glow.style.transform = `translate(${glowX}px, ${glowY}px)`;
            }
        });

        logoWrapper.addEventListener('mouseleave', function() {
            // Reset transformations
            this.style.transform = 'rotateX(0) rotateY(0)';
            if (glow) {
                glow.style.transform = 'translate(0, 0)';
            }
        });
    }

    // Parallax effect on scroll
    const heroSection = document.querySelector('.team-hero-section');
    const heroBackground = document.querySelector('.hero-bg-image');
    const heroShapes = document.querySelectorAll('.hero-geometric-shape');

    if (heroSection && (heroBackground || heroShapes.length)) {
        window.addEventListener('scroll', function() {
            const scrollPosition = window.scrollY;
            const sectionHeight = heroSection.offsetHeight;

            // Only apply parallax when the section is in view
            if (scrollPosition < sectionHeight) {
                const scrollPercentage = scrollPosition / sectionHeight;

                // Parallax effect for background image
                if (heroBackground) {
                    heroBackground.style.transform = `scale(${1.05 + scrollPercentage * 0.1}) translateY(${scrollPercentage * 50}px)`;
                }

                // Parallax effect for geometric shapes
                heroShapes.forEach((shape, index) => {
                    const direction = index % 2 === 0 ? 1 : -1;
                    const speed = index % 2 === 0 ? 0.3 : 0.2;
                    shape.style.transform = `translate(${direction * scrollPercentage * 50}px, ${scrollPercentage * 70 * speed}px)`;
                });
            }
        });
    }
});
