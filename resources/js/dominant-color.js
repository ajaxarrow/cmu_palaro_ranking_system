async function getDominantColor(imageUrl) {
    return new Promise((resolve) => {
        const img = new Image();
        img.crossOrigin = "Anonymous";
        
        img.onload = function() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = img.width;
            canvas.height = img.height;
            
            ctx.drawImage(img, 0, 0);
            
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
            const colorMap = {};
            let maxCount = 0;
            let dominantColor = { r: 0, g: 0, b: 0 };
            
            // Sample every 4th pixel for performance
            for (let i = 0; i < imageData.length; i += 16) {
                const r = imageData[i];
                const g = imageData[i + 1];
                const b = imageData[i + 2];
                const rgb = `${r},${g},${b}`;
                
                colorMap[rgb] = (colorMap[rgb] || 0) + 1;
                
                if (colorMap[rgb] > maxCount) {
                    maxCount = colorMap[rgb];
                    dominantColor = { r, g, b };
                }
            }
            
            resolve(dominantColor);
        };
        
        img.onerror = function() {
            // Return a default color if image fails to load
            resolve({ r: 0, g: 73, b: 30 }); // CMU green as default
        };
        
        img.src = imageUrl;
    });
}

export { getDominantColor }; 