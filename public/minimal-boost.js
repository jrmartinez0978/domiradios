/**
 * Domiradios - Minimal Performance Optimizer
 * Optimizaciones ligeras que no interfieren con el código existente
 */

(function() {
  // Solo aplicar optimizaciones básicas que no afecten el rendimiento negativamente
  
  // 1. Lazy loading nativo para imágenes bajo el viewport
  document.addEventListener('DOMContentLoaded', function() {
    if ('loading' in HTMLImageElement.prototype) {
      // El navegador soporta lazy loading nativo
      document.querySelectorAll('img:not([loading])').forEach(function(img) {
        // No aplicar a imágenes en la parte superior visible
        const rect = img.getBoundingClientRect();
        if (rect.top > window.innerHeight) {
          img.setAttribute('loading', 'lazy');
        }
      });
    }
  });
  
  // 2. Asegurar que las imágenes tengan dimensiones explícitas para prevenir CLS
  window.addEventListener('load', function() {
    document.querySelectorAll('img:not([width]):not([height])').forEach(function(img) {
      if (img.complete && img.naturalWidth > 0) {
        img.setAttribute('width', img.naturalWidth);
        img.setAttribute('height', img.naturalHeight);
      }
    });
  });
  
  // 3. Precargar recursos críticos cuando el navegador esté inactivo
  if ('requestIdleCallback' in window) {
    requestIdleCallback(function() {
      // Precargar páginas principales para navegación más rápida
      const pagesToPreload = ['/emisoras', '/ciudades'];
      
      pagesToPreload.forEach(function(url) {
        const link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = url;
        document.head.appendChild(link);
      });
    });
  }
})();
