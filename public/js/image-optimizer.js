/**
 * Advanced Image Optimizer for Domiradios
 * This script optimizes images on-the-fly without modifying original code
 */

// Execute when DOM is ready for best performance
document.addEventListener('DOMContentLoaded', function() {
  // Find all image elements that could be optimized
  const images = document.querySelectorAll('img:not([data-optimized])');
  
  // Implement improved lazy loading with advanced features
  if ('loading' in HTMLImageElement.prototype) {
    // Modern browsers support native lazy loading
    images.forEach(function(img) {
      if (!img.hasAttribute('loading')) {
        // Add native lazy loading
        img.setAttribute('loading', 'lazy');
        // Mark as handled
        img.setAttribute('data-optimized', 'true');
        
        // If image has no explicit dimensions, try to preserve aspect ratio
        if (!img.hasAttribute('width') && !img.hasAttribute('height') && img.complete) {
          if (img.naturalWidth > 0 && img.naturalHeight > 0) {
            // Set explicit width/height to prevent layout shifts
            img.setAttribute('width', img.naturalWidth);
            img.setAttribute('height', img.naturalHeight);
          }
        }
      }
    });
  } else {
    // Fallback for browsers without native support
    const lazyImageObserver = new IntersectionObserver(function(entries, observer) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          const img = entry.target;
          
          // If there's a data-src, use it
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
          }
          
          // Mark as optimized
          img.setAttribute('data-optimized', 'true');
          
          // Once loaded, stop observing
          observer.unobserve(img);
        }
      });
    });
    
    images.forEach(function(img) {
      lazyImageObserver.observe(img);
    });
  }
  
  // Dynamically convert to WebP for supported browsers
  if ('createImageBitmap' in window && 'HTMLCanvasElement' in window) {
    const supportsWebP = function() {
      const elem = document.createElement('canvas');
      if (elem.getContext && elem.getContext('2d')) {
        return elem.toDataURL('image/webp').indexOf('data:image/webp') === 0;
      }
      return false;
    };
    
    if (supportsWebP()) {
      // Find non-WebP images that could be converted
      const convertibleImages = document.querySelectorAll('img[src$=".jpg"], img[src$=".jpeg"], img[src$=".png"]');
      
      convertibleImages.forEach(function(img) {
        // Only proceed if not already optimized
        if (img.getAttribute('data-webp-converted') !== 'true') {
          const originalSrc = img.getAttribute('src');
          
          // Skip if already WebP or data URL
          if (originalSrc.indexOf('.webp') === -1 && !originalSrc.startsWith('data:')) {
            // Construct WebP URL by changing extension
            const webpSrc = originalSrc.replace(/\.(jpg|jpeg|png)$/i, '.webp');
            
            // Test if WebP version exists
            const testImage = new Image();
            testImage.onload = function() {
              // WebP version exists, use it
              img.setAttribute('src', webpSrc);
              img.setAttribute('data-webp-converted', 'true');
              
              // Preload original format as fallback
              const link = document.createElement('link');
              link.rel = 'preload';
              link.as = 'image';
              link.href = originalSrc;
              link.setAttribute('data-fallback', 'true');
              document.head.appendChild(link);
            };
            
            // Set source to test if WebP exists
            testImage.src = webpSrc;
          }
        }
      });
    }
  }
  
  // Implement adaptive image loading based on screen size and network
  const adaptiveImageLoading = function() {
    // Check connection type if available
    const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    const isSaveData = connection && connection.saveData;
    const effectiveType = connection && connection.effectiveType;
    
    // Determine if we should load lower quality images
    const shouldOptimize = isSaveData || (effectiveType && ['slow-2g', '2g', '3g'].includes(effectiveType));
    
    if (shouldOptimize) {
      // Find large images that could be down-scaled
      document.querySelectorAll('img[src]:not([data-reduced])').forEach(function(img) {
        // Skip small images and images with specific quality requirements
        if (img.naturalWidth > 600 && !img.classList.contains('high-quality')) {
          const src = img.getAttribute('src');
          
          // Check if we can use a smaller version
          if (src.match(/\.(jpg|jpeg|png|webp)$/i)) {
            // Only downscale images that are displayed smaller than their natural size
            if (img.width > 0 && img.width < img.naturalWidth * 0.7) {
              // Calculate optimal size based on display size
              const optimalWidth = Math.ceil(img.width * window.devicePixelRatio);
              
              // Add query parameter for responsive image handling
              if (src.indexOf('?') === -1) {
                img.setAttribute('src', src + '?w=' + optimalWidth);
              } else {
                img.setAttribute('src', src + '&w=' + optimalWidth);
              }
              
              img.setAttribute('data-reduced', 'true');
            }
          }
        }
      });
    }
  };
  
  // Run adaptive loading after images have loaded
  window.addEventListener('load', adaptiveImageLoading);
  
  // Re-run on resize (debounced)
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(adaptiveImageLoading, 200);
  });
  
  // Advanced: preloading for images that will soon be in viewport
  const preloadNearbyImages = function() {
    const viewportHeight = window.innerHeight;
    
    document.querySelectorAll('img[src]:not([data-preloaded])').forEach(function(img) {
      const rect = img.getBoundingClientRect();
      
      // If image is within 500px of viewport but not yet visible
      if (rect.top > viewportHeight && rect.top < viewportHeight + 500) {
        // Mark as being preloaded
        img.setAttribute('data-preloaded', 'pending');
        
        // Create preload link
        const link = document.createElement('link');
        link.rel = 'preload';
        link.as = 'image';
        link.href = img.src;
        document.head.appendChild(link);
        
        // Mark as fully preloaded
        img.setAttribute('data-preloaded', 'complete');
      }
    });
  };
  
  // Initial preload check
  setTimeout(preloadNearbyImages, 1000);
  
  // Check again on scroll (throttled)
  let scrollTimer;
  let lastScrollTime = 0;
  window.addEventListener('scroll', function() {
    const now = Date.now();
    
    if (now - lastScrollTime > 200) {
      lastScrollTime = now;
      preloadNearbyImages();
    }
  }, { passive: true });
});
