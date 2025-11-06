/* ========================================
   ALDAWAN - Unified Loading Animation System
   ======================================== */

// Initialize loading animations when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. FORM SUBMISSION LOADING
    // Add loading state to all form submit buttons
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.classList.contains('no-loading')) {
                // Add loading class to button
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                
                // Store original text
                if (!submitBtn.dataset.originalText) {
                    submitBtn.dataset.originalText = submitBtn.innerHTML;
                }
            }
        });
    });
    
    // 2. PAGE TRANSITION LOADING
    // Show loader on page navigation (clicks on links)
    document.querySelectorAll('a:not([target="_blank"]):not([href^="#"]):not(.no-loading)').forEach(link => {
        link.addEventListener('click', function(e) {
            // Only show loader for internal navigation
            if (this.hostname === window.location.hostname && !this.hasAttribute('download')) {
                showPageLoader();
            }
        });
    });
    
    // Hide page loader when page is fully loaded
    window.addEventListener('load', function() {
        hidePageLoader();
    });
    
    
    
});

// UTILITY FUNCTIONS

/**
 * Show page loading overlay
 */
function showPageLoader() {
    let loader = document.getElementById('page-loader');
    if (!loader) {
        loader = document.createElement('div');
        loader.id = 'page-loader';
        loader.innerHTML = '<div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"><span class="visually-hidden">Loading...</span></div>';
        document.body.appendChild(loader);
    }
    loader.classList.remove('hidden');
}

/**
 * Hide page loading overlay
 */
function hidePageLoader() {
    const loader = document.getElementById('page-loader');
    if (loader) {
        loader.classList.add('hidden');
    }
}

/**
 * Add loading spinner to an element
 * @param {HTMLElement} element - Element to add spinner to
 */
function showInlineLoader(element) {
    if (!element.querySelector('.spinner-inline')) {
        const spinner = document.createElement('span');
        spinner.className = 'spinner-inline ms-2';
        element.appendChild(spinner);
    }
}

/**
 * Remove loading spinner from an element
 * @param {HTMLElement} element - Element to remove spinner from
 */
function hideInlineLoader(element) {
    const spinner = element.querySelector('.spinner-inline');
    if (spinner) {
        spinner.remove();
    }
}

/**
 * Reset form button loading state
 * @param {HTMLElement} button - Button element to reset
 */
function resetButtonLoading(button) {
    button.classList.remove('btn-loading');
    button.disabled = false;
    if (button.dataset.originalText) {
        button.innerHTML = button.dataset.originalText;
    }
}

// Export functions for global use
window.showPageLoader = showPageLoader;
window.hidePageLoader = hidePageLoader;
window.showInlineLoader = showInlineLoader;
window.hideInlineLoader = hideInlineLoader;
window.resetButtonLoading = resetButtonLoading;
