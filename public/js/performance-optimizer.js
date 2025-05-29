/**
 * Domiradios - Performance Optimizer
 * Este script mejora el rendimiento sin modificar la estructura base
 */

// Implementación de Lazy Loading para imágenes
document.addEventListener('DOMContentLoaded', function() {
  // Selector para todas las imágenes que no sean críticas (no en la vista principal)
  const lazyImages = document.querySelectorAll('img:not([loading="eager"])');
  
  if ('loading' in HTMLImageElement.prototype) {
    // Si el navegador soporta lazy loading nativo
    lazyImages.forEach(img => {
      if (!img.hasAttribute('loading')) {
        img.setAttribute('loading', 'lazy');
      }
    });
  } else {
    // Fallback para navegadores sin soporte nativo
    const lazyImageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const lazyImage = entry.target;
          if (lazyImage.dataset.src) {
            lazyImage.src = lazyImage.dataset.src;
            lazyImage.removeAttribute('data-src');
          }
          observer.unobserve(lazyImage);
        }
      });
    });

    lazyImages.forEach(lazyImage => {
      if (!lazyImage.hasAttribute('loading')) {
        lazyImageObserver.observe(lazyImage);
      }
    });
  }
});

// Optimización de carga de CSS y JavaScript
document.addEventListener('DOMContentLoaded', function() {
  // Obtener todos los links a CSS no críticos
  const stylesheets = document.querySelectorAll('link[rel="stylesheet"]:not([data-critical="true"])');
  
  // Obtener todos los scripts no críticos
  const scripts = document.querySelectorAll('script:not([data-critical="true"])');
  
  // Función para cargar recursos de forma diferida
  function loadDeferredResource(resource) {
    if (resource.tagName.toLowerCase() === 'link') {
      const href = resource.getAttribute('href');
      if (href) {
        const preloadLink = document.createElement('link');
        preloadLink.rel = 'preload';
        preloadLink.as = 'style';
        preloadLink.href = href;
        preloadLink.onload = function() {
          this.onload = null;
          this.rel = 'stylesheet';
        };
        document.head.appendChild(preloadLink);
      }
    } else if (resource.tagName.toLowerCase() === 'script') {
      const src = resource.getAttribute('src');
      if (src) {
        const script = document.createElement('script');
        script.src = src;
        script.async = true;
        document.body.appendChild(script);
      }
    }
  }
  
  // Usar requestIdleCallback para cargar recursos cuando el navegador esté inactivo
  if ('requestIdleCallback' in window) {
    requestIdleCallback(function() {
      stylesheets.forEach(loadDeferredResource);
      scripts.forEach(loadDeferredResource);
    });
  } else {
    // Fallback para navegadores sin soporte
    setTimeout(function() {
      stylesheets.forEach(loadDeferredResource);
      scripts.forEach(loadDeferredResource);
    }, 1000);
  }
});

// Optimizador de imágenes (detección y sugerencias)
document.addEventListener('DOMContentLoaded', function() {
  const largeImages = [];
  const imageThreshold = 100 * 1024; // 100KB
  
  // Buscar imágenes grandes
  document.querySelectorAll('img').forEach(img => {
    if (img.complete) {
      checkImageSize(img);
    } else {
      img.addEventListener('load', () => checkImageSize(img));
    }
  });
  
  function checkImageSize(img) {
    if (img.naturalWidth * img.naturalHeight > 1000000) { // Imágenes mayores de 1 megapixel
      // Comprobar proporción de tamaño real vs mostrado
      const displayArea = img.width * img.height;
      const naturalArea = img.naturalWidth * img.naturalHeight;
      if (naturalArea > displayArea * 2) { // La imagen es al menos el doble de grande de lo que se muestra
        console.warn('Imagen sobredimensionada detectada:', img.src);
        largeImages.push({
          src: img.src,
          displayed: `${img.width}x${img.height}`,
          natural: `${img.naturalWidth}x${img.naturalHeight}`,
          ratio: Math.round(naturalArea / displayArea)
        });
      }
    }
  }
  
  // Solo registrar en consola en desarrollo para depuración
  if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    setTimeout(() => {
      if (largeImages.length > 0) {
        console.table(largeImages);
        console.info('Optimización recomendada: Redimensionar estas imágenes al tamaño de visualización para mejorar el rendimiento.');
      }
    }, 3000);
  }
});
