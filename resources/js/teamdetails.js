document.addEventListener('DOMContentLoaded', function() {
    // Extract dominant color from flag image
    const teamFlag = document.getElementById('teamFlag');
    if (teamFlag) {
        // Create virtual canvas to analyze image
        setTimeout(function() {
            try {
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.width = teamFlag.width;
                canvas.height = teamFlag.height;
                context.drawImage(teamFlag, 0, 0, teamFlag.width, teamFlag.height);
                
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
                    
                    // Skip very light or very dark colors
                    const brightness = (r + g + b) / 3;
                    if (brightness < 30 || brightness > 100) continue;
                    
                    // Create a key for this color (rounded to reduce variations)
                    const colorKey = Math.round(r/10)*10 + ',' + Math.round(g/10)*10 + ',' + Math.round(b/10)*10;
                    
                    if (!colorCounts[colorKey]) {
                        colorCounts[colorKey] = 0;
                    }
                    
                    colorCounts[colorKey]++;
                    
                    if (colorCounts[colorKey] > maxCount) {
                        maxCount = colorCounts[colorKey];
                        dominantColor = { 
                            r: Math.round(r/10)*10, 
                            g: Math.round(g/10)*10, 
                            b: Math.round(b/10)*10 
                        };
                    }
                }
                
                // Calculate a darker version for contrast
                const darkerColor = {
                    r: Math.max(0, dominantColor.r - 20),
                    g: Math.max(0, dominantColor.g - 20),
                    b: Math.max(0, dominantColor.b - 20)
                };
                
                // Set the CSS variables
                document.documentElement.style.setProperty('--team-color-rgb', `${dominantColor.r}, ${dominantColor.g}, ${dominantColor.b}`);
                document.documentElement.style.setProperty('--team-color-rgb-dark', `${darkerColor.r}, ${darkerColor.g}, ${darkerColor.b}`);
            } catch (e) {
                console.error("Error processing flag image:", e);
            }
        }, 500);
    }
    
    // Animate progress bars
    setTimeout(function() {
        document.querySelectorAll('.width-animate').forEach(function(bar) {
            bar.style.width = bar.dataset.width;
        });
    }, 300);
    
    // Animate counters
    document.querySelectorAll('[data-medal-count]').forEach(function(element) {
        animateCounter(element, parseInt(element.dataset.medalCount));
    });
    
    document.querySelectorAll('[data-count]').forEach(function(element) {
        animateCounter(element, parseFloat(element.dataset.count), 1);
    });
    
    function animateCounter(element, target, decimals = 0) {
        let start = 0;
        const duration = 1500;
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
                element.textContent = Math.floor(value);
            }
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            }
        }
        
        requestAnimationFrame(updateCounter);
    }
});
