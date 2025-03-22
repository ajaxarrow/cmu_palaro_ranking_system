 /**
  * SportsTallyLoader - A modern, reusable loading screen for Sports Tally System
 * Handles asset loading and shows a professional loading animation with particle effects
 */
class SportsTallyLoader {
    constructor(options = {}) {
        this.minLoadTime = options.minLoadTime || 2000; // Minimum time to show loader (ms)
        this.assetSelector = options.assetSelector || 'img, video, audio, script, link[rel="stylesheet"]';
        this.onComplete = options.onComplete || function(){};
        
        this.loadingScreen = document.getElementById('loadingScreen');
        this.loadingBar = document.getElementById('loadingBar');
        this.loadingPercentage = document.getElementById('loadingPercentage');
        this.mainContent = document.getElementById('mainContent');
        this.particlesContainer = document.getElementById('particles');
        
        this.assetsLoaded = 0;
        this.assetsTotal = 0;
        this.startTime = Date.now();
        this.currentProgress = 0;
        this.targetProgress = 0;
        
        this.init();
        this.createParticles();
        this.animateProgress();
    }
    
    init() {
        // Collect all assets that need to be loaded
        const assets = document.querySelectorAll(this.assetSelector);
        this.assetsTotal = assets.length;
        
        // Start with at least 5% for better UX
        this.updateProgress(5);
        
        // If no assets, simulate loading
        if (this.assetsTotal === 0) {
            this.simulateLoading();
            return;
        }
        
        // Track loading of each asset
        assets.forEach(asset => {
            if (this.isAssetLoaded(asset)) {
                this.assetLoaded();
            } else {
                asset.addEventListener('load', () => this.assetLoaded());
                asset.addEventListener('error', () => this.assetLoaded()); // Count errors as "loaded" to avoid hanging
            }
        });
        
        // Fallback in case some assets don't trigger events
        window.addEventListener('load', () => {
            setTimeout(() => {
                if (this.loadingScreen && !this.loadingScreen.classList.contains('hidden')) {
                    this.completeLoading();
                }
            }, 1000);
        });
    }
    
    createParticles() {
        if (!this.particlesContainer) return;
        
        // Create floating particles for the background
        for (let i = 0; i < 30; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            // Random size between 2px and 6px
            const size = Math.random() * 4 + 2;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            // Random position
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`;
            
            // Random opacity
            particle.style.opacity = (Math.random() * 0.5 + 0.1).toString();
            
            // Add to container
            this.particlesContainer.appendChild(particle);
            
            // Animate the particle
            this.animateParticle(particle);
        }
    }
    
    animateParticle(particle) {
        // Initial position
        const startX = parseFloat(particle.style.left);
        const startY = parseFloat(particle.style.top);
        
        // Random movement range
        const rangeX = 10 + Math.random() * 20;
        const rangeY = 10 + Math.random() * 20;
        
        // Random duration between 10s and 20s
        const duration = 10000 + Math.random() * 10000;
        
        // Animation start time
        const startTime = Date.now();
        
        // Animate function
        const animate = () => {
            const elapsed = Date.now() - startTime;
            const progress = (elapsed % duration) / duration;
            
            // Sine wave movement
            const x = startX + Math.sin(progress * Math.PI * 2) * rangeX;
            const y = startY + Math.cos(progress * Math.PI * 2) * rangeY;
            
            // Update position
            particle.style.left = `${x}%`;
            particle.style.top = `${y}%`;
            
            // Continue animation
            requestAnimationFrame(animate);
        };
        
        // Start animation
        animate();
    }
    
    animateProgress() {
        // Smoothly animate the progress bar
        const animate = () => {
            if (this.currentProgress < this.targetProgress) {
                this.currentProgress = Math.min(this.currentProgress + 0.5, this.targetProgress);
                this.setProgressUI(this.currentProgress);
            }
            
            if (!this.loadingScreen.classList.contains('hidden')) {
                requestAnimationFrame(animate);
            }
        };
        
        // Start animation
        animate();
    }
    
    isAssetLoaded(asset) {
        // Check if asset is already loaded
        if (asset.tagName === 'IMG') return asset.complete;
        if (asset.tagName === 'LINK') return asset.sheet !== null;
        if (asset.tagName === 'SCRIPT') return asset.readyState === 'complete' || asset.readyState === 'loaded';
        return false;
    }
    
    assetLoaded() {
        this.assetsLoaded++;
        const progress = 5 + Math.round((this.assetsLoaded / this.assetsTotal) * 90); // 5-95% range
        this.updateProgress(progress);
        
        if (this.assetsLoaded >= this.assetsTotal) {
            // Make sure we reach at least the minimum loading time
            const elapsed = Date.now() - this.startTime;
            if (elapsed < this.minLoadTime) {
                setTimeout(() => this.completeLoading(), this.minLoadTime - elapsed);
            } else {
                this.completeLoading();
            }
        }
    }
    
    simulateLoading() {
        let progress = 5;
        const interval = setInterval(() => {
            progress += 5;
            this.updateProgress(progress);
            
            if (progress >= 95) {
                clearInterval(interval);
                setTimeout(() => this.completeLoading(), 500);
            }
        }, 200);
    }
    
    updateProgress(percentage) {
        this.targetProgress = percentage;
    }
    
    setProgressUI(percentage) {
        if (this.loadingBar) {
            this.loadingBar.style.width = percentage + '%';
        }
        if (this.loadingPercentage) {
            this.loadingPercentage.textContent = Math.round(percentage) + '%';
            
            // Add a little bounce effect when the percentage changes
            this.loadingPercentage.style.transform = 'scale(1.2)';
            setTimeout(() => {
                if (this.loadingPercentage) {
                    this.loadingPercentage.style.transform = 'scale(1)';
                }
            }, 200);
        }
    }
    
    completeLoading() {
        // Final animation to 100%
        this.updateProgress(100);
        
        // After a brief delay, hide loading screen and show content
        setTimeout(() => {
            if (this.loadingScreen) {
                this.loadingScreen.classList.add('hidden');
            }
            if (this.mainContent) {
                this.mainContent.style.opacity = '1';
                this.mainContent.style.transform = 'translateY(0)';
                this.mainContent.classList.add('visible');
            }
            
            // Call the completion callback
            this.onComplete();
        }, 800);
    }
}

// Initialize the loader when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new SportsTallyLoader({
        minLoadTime: 2500, // Minimum time to show the loader
        onComplete: function() {
            console.log('All assets loaded. Site is ready!');
            // You can trigger any post-loading functions here
        }
    });
});