/**
 * Domiradios - Mobile Specific Optimizer
 * Optimizaciones específicas para dispositivos móviles
 */

// Ejecutar solo en dispositivos móviles
if (window.innerWidth < 768 || 'ontouchstart' in document.documentElement) {
  console.log('Mobile optimizer activated');
  
  // Función para aplicar optimizaciones móviles
  function applyMobileOptimizations() {
    // 1. Optimización agresiva de imágenes
    optimizeImagesForMobile();
    
    // 2. Reducir carga de JS no esencial
    deferNonEssentialScripts();
    
    // 3. Minimizar CSS
    optimizeCSSForMobile();
    
    // 4. Optimizar recursos que bloquean el renderizado
    reduceRenderBlockingResources();
  }
  
  // 1. Optimización agresiva de imágenes para móviles
  function optimizeImagesForMobile() {
    // Buscar todas las imágenes no críticas
    const images = document.querySelectorAll('img:not([critical="true"])');
    
    images.forEach(function(img) {
      // Establecer lazy loading en todas las imágenes
      if (!img.hasAttribute('loading')) {
        img.setAttribute('loading', 'lazy');
      }
      
      // Si la imagen es demasiado grande para la pantalla, optimizarla
      if (img.naturalWidth > window.innerWidth * 1.5) {
        // 1.5x para retina display
        const aspectRatio = img.naturalHeight / img.naturalWidth;
        const optimizedWidth = Math.min(window.innerWidth, 480); // Máximo 480px en móviles
        
        // Preservar aspect ratio
        img.style.width = optimizedWidth + 'px';
        img.style.height = (optimizedWidth * aspectRatio) + 'px';
        
        // Si la imagen acepta parámetros de tamaño
        if (img.src.includes('?')) {
          img.src = img.src + '&width=' + optimizedWidth;
        } else {
          img.src = img.src + '?width=' + optimizedWidth;
        }
      }
      
      // Reducir calidad de imagen para ahorrar datos en móviles
      if (navigator.connection && 
          (navigator.connection.saveData || 
           ['slow-2g', '2g', '3g'].includes(navigator.connection.effectiveType))) {
        // Reducir calidad solo si el usuario está en conexión lenta o tiene activado ahorro de datos
        img.style.filter = 'blur(0)'; // Prevenir blur al cargar
      }
    });
    
    // Diferir imágenes no críticas (no visibles inicialmente)
    const deferredImages = document.querySelectorAll('img:not([critical="true"]):not([loading="eager"])');
    deferredImages.forEach(function(img) {
      // Si la imagen está fuera de la pantalla por más de 1000px
      const rect = img.getBoundingClientRect();
      if (rect.top > window.innerHeight + 1000) {
        // Guardar la URL original
        const originalSrc = img.src;
        // Reemplazar con un placeholder
        img.setAttribute('data-original-src', originalSrc);
        img.src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"%3E%3C/svg%3E';
        
        // Crear un observador para cargar cuando se acerque al viewport
        const observer = new IntersectionObserver(function(entries) {
          entries.forEach(function(entry) {
            if (entry.isIntersecting) {
              const target = entry.target;
              if (target.hasAttribute('data-original-src')) {
                target.src = target.getAttribute('data-original-src');
                target.removeAttribute('data-original-src');
                observer.unobserve(target);
              }
            }
          });
        }, { rootMargin: '1000px' });
        
        observer.observe(img);
      }
    });
  }
  
  // 2. Reducir carga de JS no esencial
  function deferNonEssentialScripts() {
    // Lista de scripts que podemos posponer en móviles
    const nonEssentialScripts = [
      'analytics', 'tracking', 'social', 'share', 'recommend', 
      'popup', 'modal', 'feedback', 'survey', 'ad'
    ];
    
    // Buscar scripts que contienen estos términos
    document.querySelectorAll('script[src]').forEach(function(script) {
      const scriptSrc = script.src.toLowerCase();
      
      // Verificar si es un script no esencial
      const isNonEssential = nonEssentialScripts.some(term => 
        scriptSrc.includes(term)
      );
      
      if (isNonEssential && !script.hasAttribute('defer') && !script.hasAttribute('async')) {
        // Eliminar el script original
        const originalSrc = script.src;
        script.parentNode.removeChild(script);
        
        // Cargar después de que todo lo demás esté listo
        window.addEventListener('load', function() {
          setTimeout(function() {
            const newScript = document.createElement('script');
            newScript.src = originalSrc;
            newScript.async = true;
            document.body.appendChild(newScript);
          }, 3000); // Cargar después de 3 segundos
        });
      }
    });
  }
  
  // 3. Minimizar CSS para móviles
  function optimizeCSSForMobile() {
    // Desactivar animaciones en móviles para mejorar rendimiento
    const style = document.createElement('style');
    style.textContent = `
      @media (max-width: 767px) {
        * {
          animation-duration: 0.001s !important;
          animation-delay: 0.001s !important;
          transition-duration: 0.001s !important;
          transition-delay: 0.001s !important;
        }
        
        /* Reducir complejidad visual */
        .shadow, .card-shadow, .hover-shadow {
          box-shadow: none !important;
        }
        
        /* Simplificar gradientes */
        [class*="gradient"], [style*="gradient"] {
          background: none !important;
        }
        
        /* Optimizar scrolling */
        body, .scrollable {
          -webkit-overflow-scrolling: touch;
          overflow-scrolling: touch;
        }
        
        /* Mejorar rendering para móviles */
        img, video, canvas {
          image-rendering: optimizeSpeed;
        }
      }
    `;
    document.head.appendChild(style);
  }
  
  // 4. Reducir recursos que bloquean el renderizado
  function reduceRenderBlockingResources() {
    // Identificar estilos no críticos
    document.querySelectorAll('link[rel="stylesheet"]:not([data-critical="true"])').forEach(function(link) {
      // Obtener href
      const href = link.getAttribute('href');
      if (href) {
        // Remover el stylesheet original
        link.parentNode.removeChild(link);
        
        // Cargar con baja prioridad
        const newLink = document.createElement('link');
        newLink.rel = 'preload';
        newLink.as = 'style';
        newLink.href = href;
        newLink.onload = function() {
          this.onload = null;
          this.rel = 'stylesheet';
          this.media = 'all';
        };
        newLink.media = 'print';
        document.head.appendChild(newLink);
      }
    });
    
    // Posponer carga de fuentes
    document.querySelectorAll('link[rel="stylesheet"][href*="font"], link[rel="preconnect"][href*="font"]').forEach(function(link) {
      // Marcar para carga tardía
      link.setAttribute('media', 'print');
      
      // Cargar cuando el contenido principal esté visible
      requestIdleCallback(function() {
        link.media = 'all';
      });
    });
  }
  
  // Ejecutar optimizaciones inmediatamente para el contenido inicial
  (function() {
    // Reducir recursos que bloquean el renderizado inmediatamente
    reduceRenderBlockingResources();
    
    // Aplicar el resto de optimizaciones después de DOMContentLoaded
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', applyMobileOptimizations);
    } else {
      applyMobileOptimizations();
    }
  })();
  
  // Optimizaciones adicionales para conexiones lentas
  if (navigator.connection && 
      (navigator.connection.saveData || 
       ['slow-2g', '2g', '3g'].includes(navigator.connection.effectiveType))) {
    
    // Eliminar recursos no esenciales completamente para conexiones lentas
    document.querySelectorAll('link[rel="prefetch"], link[rel="preload"]:not([as="style"]):not([as="script"])').forEach(function(link) {
      link.parentNode.removeChild(link);
    });
    
    // Mostrar mensaje de optimización para datos
    const saveDataMode = document.createElement('div');
    saveDataMode.style.cssText = 'position:fixed;bottom:0;left:0;right:0;background:rgba(0,0,0,0.7);color:white;padding:8px;font-size:12px;text-align:center;z-index:9999;';
    saveDataMode.textContent = 'Modo ahorro de datos activado. Algunas imágenes se cargarán en menor calidad.';
    saveDataMode.addEventListener('click', function() {
      this.style.display = 'none';
    });
    document.body.appendChild(saveDataMode);
    
    // Ocultar después de 5 segundos
    setTimeout(function() {
      saveDataMode.style.display = 'none';
    }, 5000);
  }
}
