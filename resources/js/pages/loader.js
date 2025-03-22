
        /**
         * SportsTallyLoader - A modern, reusable loading screen for Sports Tally System
         * Handles asset loading and shows a professional loading animation
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
                
                this.assetsLoaded = 0;
                this.assetsTotal = 0;
                this.startTime = Date.now();
                
                this.init();
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
                if (this.loadingBar) {
                    this.loadingBar.style.width = percentage + '%';
                }
                if (this.loadingPercentage) {
                    this.loadingPercentage.textContent = percentage + '%';
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
                    }
                    
                    // Call the completion callback
                    this.onComplete();
                }, 500);
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
