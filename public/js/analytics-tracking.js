// Analytics Tracking Script for Frontend
(function() {
    const analyticsTracker = {
        sessionId: null,
        startTime: Date.now(),
        clickCount: 0,
        
        init: function() {
            this.startSession();
            this.attachClickListeners();
            this.setupUnloadListener();
        },
        
        startSession: function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) return;
            
            fetch('/api/analytics/start-session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content
                }
            })
            .then(response => response.json())
            .then(data => {
                this.sessionId = data.session_id;
                console.log('Analytics session started:', this.sessionId);
            })
            .catch(error => console.error('Error starting analytics session:', error));
        },
        
        recordClick: function(itemType, itemId, pageUrl = null) {
            this.clickCount++;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) return;
            
            fetch('/api/analytics/record-click', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content
                },
                body: JSON.stringify({
                    item_type: itemType,
                    item_id: itemId,
                    page_url: pageUrl || window.location.href
                })
            })
            .catch(error => console.error('Error recording click:', error));
        },
        
        attachClickListeners: function() {
            // Theo dõi click trên các link bài viết (article)
            document.querySelectorAll('[data-article-id]').forEach(el => {
                el.addEventListener('click', (e) => {
                    const articleId = el.getAttribute('data-article-id');
                    this.recordClick('article', articleId);
                });
            });
            
            // Theo dõi click trên các link sản phẩm (product)
            document.querySelectorAll('[data-product-id]').forEach(el => {
                el.addEventListener('click', (e) => {
                    const productId = el.getAttribute('data-product-id');
                    this.recordClick('product', productId);
                });
            });
            
            // Theo dõi click trên các link có class "track-click"
            document.querySelectorAll('.track-click').forEach(el => {
                el.addEventListener('click', (e) => {
                    const type = el.getAttribute('data-track-type') || 'other';
                    const id = el.getAttribute('data-track-id') || Date.now();
                    this.recordClick(type, id);
                });
            });
        },
        
        setupUnloadListener: function() {
            window.addEventListener('beforeunload', () => {
                const durationSeconds = Math.round((Date.now() - this.startTime) / 1000);
                
                if (this.sessionId) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        navigator.sendBeacon('/api/analytics/end-session', JSON.stringify({
                            session_id: this.sessionId,
                            duration_seconds: durationSeconds,
                            total_clicks: this.clickCount
                        }));
                    }
                }
            });
        }
    };
    
    // Khởi tạo tracker khi DOM loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            analyticsTracker.init();
        });
    } else {
        analyticsTracker.init();
    }
    
    // Expose to global scope for manual tracking if needed
    window.analyticsTracker = analyticsTracker;
})();
