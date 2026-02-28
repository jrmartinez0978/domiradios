// === RTCStream Player =========================================================
// Mayo-2025 · Versión 1.7.9 (Adaptada con puerto y slug dinámicos)
//
// Reproductor WebRTC ultra-baja latencia con reconexión automática,
// MediaSession, WakeLock y controles Play/Stop.
// ======================================================================

(function () {
  /* ------------------------------------------------------------------ */
  /* CONFIG PÚBLICA                                                     */
  /* ------------------------------------------------------------------ */
  window.RTCStreamPlayer = {
    config: {
      wsUrl: null,           // URL WebSocket completa
      autoplay: false,       // Autoplay opcional
      title: 'En Tiempo Real',
      artist: 'Radio Online',
      artwork: [
        { src:'https://live.rtcstreaming.com:9000/logo96x96.png',     sizes:'96x96',  type:'image/png' },
        { src:'https://live.rtcstreaming.com:9000/logo256x256.png',  sizes:'256x256',type:'image/png' }
      ]
    },
    
    // Método para inicializar con la URL WebSocket completa
    init: function(options) {
      if (options.wsUrl) this.config.wsUrl = options.wsUrl;
      if (options.title) this.config.title = options.title;
      if (options.artist) this.config.artist = options.artist;
      if (options.autoplay) this.config.autoplay = options.autoplay;
      if (options.artwork) this.config.artwork = options.artwork;
      
      // Log de diagnóstico para depuración
      console.log('RTCStreamPlayer inicializado con URL:', this.config.wsUrl);
      
      // Actualizar slug en la UI si existe el elemento
      if ($slugEl) {
        const slug = this.config.wsUrl.split('/').pop();
        $slugEl.textContent = slug || this.config.title;
      }
      
      // Autoplay si está configurado
      if (this.config.autoplay) setTimeout(connect, 100);
      
      return this;
    },
    
    connect, disconnect,
    getStatus () {
      return { connected: !!consumer && !consumer.closed, playing: !$audio.paused, time: sec };
    }
  };

  /* DOM --------------------------------------------------------------- */
  const $btnPlay = document.getElementById('btnConnect') || document.getElementById('btnPlay');
  const $btnStop = document.getElementById('btnDisconnect') || document.getElementById('btnStop');
  const $status  = document.getElementById('playerStatus');
  const $timer   = document.getElementById('playerTimer');
  const $audio   = document.getElementById('playerAudio') || document.getElementById('audioPlayer');
  const $slugEl  = document.getElementById('playerSlug');

  /* STATE ------------------------------------------------------------- */
  const TURN = { ip: '40.160.13.94', port: 2031, user: 'rtcuser', pass: 'rtcpass' };
let ws, device, recvTransport, consumer;
  let producerId = null, producerReady = false;
  let intentionalClose = false, intentionalPause = false;
  let wakeLock = null, audioContext = null, mediaElementSource = null;
  let monitor   = null, mediaSessionActive = false;
  let sec = 0, tick;
  let retryTimer = null;
  let lastNetworkType = null;
  const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
  const SAMPLE_RATE = 48000;

  /* UI helpers -------------------------------------------------------- */
  const st = (t, c) => { 
    if ($status) { 
      $status.textContent = t; 
      $status.className = $status.className.replace(/status-\w+/g, '') + ' status-'+c; 
    }
  };
  const startT = () => { 
    if ($timer) {
      clearInterval(tick); 
      tick=setInterval(()=>{ 
        $timer.textContent=`${String(sec/60|0).padStart(2,'0')}:${String(++sec%60).padStart(2,'0')}`;
      },1e3); 
    }
  };
  const resetT = () => { 
    if ($timer) {
      clearInterval(tick); 
      sec=0; 
      $timer.textContent='00:00'; 
    }
  };

  /* Network change detection ------------------------------------------ */
  function setupNetworkMonitoring() {
    if ('connection' in navigator) {
      lastNetworkType = navigator.connection.type || navigator.connection.effectiveType;
      
      navigator.connection.addEventListener('change', () => {
        const currentType = navigator.connection.type || navigator.connection.effectiveType;
        
        if (lastNetworkType !== currentType) {
          console.log(`Red cambiada: ${lastNetworkType} → ${currentType}`);
          lastNetworkType = currentType;
          
          // Solo reconectar si estamos reproduciendo
          if (consumer && !consumer.closed && !$audio.paused && !intentionalPause) {
            st('Red cambiada, reconectando...', 'info');
            // Esperar un momento para que se estabilice la red
            setTimeout(() => {
              safeSampleRateReconnect();
            }, 1000);
          }
        }
      });
    }
    
    // Eventos de online/offline
    window.addEventListener('online', () => {
      console.log('Dispositivo en línea');
      if (consumer && consumer.closed && !intentionalClose) {
        setTimeout(connect, 1000);
      }
    });
    
    window.addEventListener('offline', () => {
      console.log('Dispositivo sin conexión');
      st('Sin conexión', 'error');
    });
  }

  /* Reconexión segura para mantener sample rate ----------------------- */
  function safeSampleRateReconnect() {
    // Guardar el estado del contexto actual
    const currentSampleRate = audioContext?.sampleRate || SAMPLE_RATE;
    const hadAudioContext = !!audioContext;
    
    // Cerrar conexión pero no el contexto de audio
    if (consumer) {
      consumer.close();
      consumer = null;
    }
    
    if (recvTransport) {
      recvTransport.close();
      recvTransport = null;
    }
    
    ws?.close();
    ws = null;
    
    // Detener pistas pero mantener el contexto
    if ($audio.srcObject) {
      $audio.srcObject.getTracks().forEach(t => t.stop());
      $audio.srcObject = null;
    }
    
    if (hadAudioContext && (!audioContext || audioContext.state === 'closed')) {
      // Recrear contexto si es necesario
      createAudioContext(true);
    }
    
    // Reconectar
    connect();
  }

  /* AudioContext fijo a 48kHz ----------------------------------------- */
  function createAudioContext(forceNew = false) {
    if (audioContext && !forceNew) return;
    
    if (audioContext && forceNew) {
      try {
        // Desconectar fuente de audio
        mediaElementSource?.disconnect();
        audioContext.close().catch(() => {});
      } catch (e) {
        console.warn('Error cerrando AudioContext previo:', e);
      }
      audioContext = null;
      mediaElementSource = null;
    }
    
    const AC = window.AudioContext || window.webkitAudioContext;
    try {
      // Forzar sample rate de 48kHz
      audioContext = new AC({
        latencyHint: 'interactive',
        sampleRate: SAMPLE_RATE
      });
      
      console.log('AudioContext creado con sample rate:', audioContext.sampleRate);
      
      // Manejar cambios de estado
      audioContext.onstatechange = () => {
        console.log('Estado de AudioContext cambiado a:', audioContext.state);
        if (audioContext.state === 'running' && mediaElementSource === null) {
          connectAudioSource();
        } else if (audioContext.state === 'suspended' && !intentionalPause) {
          audioContext.resume().catch(() => {});
        }
      };
      
      // Especial para iOS
      if (isIOS) {
        // Desbloquear audioContext en cualquier interacción
        const unlockAudio = () => {
          if (audioContext && audioContext.state !== 'running') {
            audioContext.resume().catch(() => {});
          }
          
          document.removeEventListener('touchstart', unlockAudio);
          document.removeEventListener('touchend', unlockAudio);
          document.removeEventListener('click', unlockAudio);
        };
        
        document.addEventListener('touchstart', unlockAudio, {once: false, passive: true});
        document.addEventListener('touchend', unlockAudio, {once: false, passive: true});
        document.addEventListener('click', unlockAudio, {once: false, passive: true});
      }
    } catch (e) {
      console.error('Error creando AudioContext:', e);
      // Fallback sin forzar sample rate
      audioContext = new AC();
    }
  }

  function connectAudioSource() {
    if (!audioContext || !$audio.srcObject) return;
    
    try {
      // Desconectar conexión existente
      if (mediaElementSource) {
        try {
          mediaElementSource.disconnect();
        } catch (e) {
          console.warn('Error desconectando fuente de audio:', e);
        }
        mediaElementSource = null;
      }
      
      // Crear y conectar nueva fuente
      mediaElementSource = audioContext.createMediaElementSource($audio);
      mediaElementSource.connect(audioContext.destination);
      
      console.log('Fuente de audio conectada a contexto con sample rate:', audioContext.sampleRate);
    } catch (e) {
      console.error('Error conectando fuente de audio:', e);
    }
  }

  /* Wake-Lock --------------------------------------------------------- */
  async function requestWake () {
    if (!('wakeLock' in navigator) || wakeLock) return;
    try { 
      wakeLock = await navigator.wakeLock.request('screen'); 
      console.log('Wake Lock activado');
    } catch(e) {
      console.warn('Error solicitando Wake Lock:', e);
    }
  }
  
  function releaseWake () { 
    if (wakeLock) {
      wakeLock.release().catch(()=>{}); 
      wakeLock = null;
    }
  }

  /* MediaSession ------------------------------------------------------ */
  function setupMS () {
    if (!('mediaSession' in navigator) || mediaSessionActive) return;
    const cfg = window.RTCStreamPlayer.config;
    navigator.mediaSession.metadata = new MediaMetadata({
      title: cfg.title||`Radio ${cfg.slug}`, artist: cfg.artist, album:'Radio en vivo', artwork: cfg.artwork
    });
    navigator.mediaSession.setActionHandler('play',  resumeAudio);
    navigator.mediaSession.setActionHandler('pause', pauseAudio);
    navigator.mediaSession.setActionHandler('stop',  disconnect);
    mediaSessionActive = true;
  }

  /* Pitch control avanzado -------------------------------------------- */
  function forcePlay(el) {
    // Reset completo para iOS
    el.playbackRate = 1.0;
    el.preservesPitch = true;
    if (el.webkitPreservesPitch !== undefined) el.webkitPreservesPitch = true;
    if (el.mozPreservesPitch !== undefined) el.mozPreservesPitch = true;
    
    // Asegurar que AudioContext esté activo
    if (audioContext && audioContext.state !== 'running') {
      audioContext.resume().catch(() => {});
    }
    
    return el.play().catch((e) => {
      console.warn('Error intentando reproducir:', e);
      
      // Para iOS, intentar una vez más con simulación de interacción
      if (isIOS) {
        return new Promise((resolve, reject) => {
          setTimeout(() => {
            el.play().then(resolve).catch(reject);
          }, 100);
        });
      }
      
      return Promise.reject(e);
    });
  }
  
  const forcePause = el => el.pause();

  /* Cleanup ----------------------------------------------------------- */
  async function cleanup () {
    releaseWake();
    ws?.close(); ws=null;
    consumer?.close(); consumer=null;
    recvTransport?.close(); recvTransport=null;
    $audio.srcObject?.getTracks().forEach(t=>t.stop());
    $audio.srcObject=null; forcePause($audio);
    mediaElementSource?.disconnect();
    
    // Solo cerrar AudioContext si no estamos reconectando
    if (audioContext && intentionalClose) {
      try {
        await audioContext.close().catch(()=>{});
        audioContext = null;
      } catch(e) {
        console.warn('Error cerrando AudioContext:', e);
      }
    }
    
    mediaElementSource=null;
    clearInterval(monitor); monitor=null;
    clearTimeout(retryTimer); retryTimer=null;

    if ('mediaSession' in navigator){
      navigator.mediaSession.setActionHandler('play',null);
      navigator.mediaSession.setActionHandler('pause',null);
      navigator.mediaSession.setActionHandler('stop',null);
      try { navigator.mediaSession.setPositionState({}); } catch{}
      mediaSessionActive=false;
    }
    resetT();
  }

  /* WebSocket send helper --------------------------------------------- */
  const send = (type,payload={}) => ws?.readyState===1 && ws.send(JSON.stringify({type,payload}));

  /* Main connect ------------------------------------------------------ */
  async function connect () {
    const { wsUrl } = window.RTCStreamPlayer.config;
    
    // Verificar que la URL de WebSocket esté presente
    if (!wsUrl) {
      console.error('Error: No se ha proporcionado una URL WebSocket para RTCStream');
      st('Error: URL no válida', 'error');
      return;
    }
    
    console.log('Conectando a WebSocket:', wsUrl);
    intentionalClose = false;
    intentionalPause = false;
    
    // Limpiar recursos previos
    await cleanup();
    
    // Preparar elementos de UI
    if ($btnPlay) $btnPlay.disabled = true;
    if ($btnStop) $btnStop.disabled = false;
    
    // Mostrar estado
    st('Conectando...', 'info');
    
    // Asegurar que AudioContext esté creado con el sample rate correcto
    createAudioContext();
    
    // Crear conexión WebSocket usando directamente la URL completa
    ws = new WebSocket(wsUrl);
    
    setupNetworkMonitoring();

    ws.onopen   = () => { 
      if (typeof mediasoupClient === 'undefined') {
        console.error('Error: mediasoupClient no definido');
        st('Error: mediasoupClient no cargado', 'error');
        if ($btnPlay) $btnPlay.disabled = false;
        return;
      }
      device = new mediasoupClient.Device(); 
      send('getRouterRtpCapabilities'); 
    };
    ws.onclose  = () => { 
      if(!intentionalClose){ 
        st('Desconectado','wait'); 
        if ($btnPlay) $btnPlay.disabled=false; 
        scheduleRetry(); 
      }
    };
    ws.onerror  = e => console.error('WS error',e);
    ws.onmessage= ({data}) => handle(JSON.parse(data));
  }

  function disconnect () {
    intentionalClose=true; st('Desconectado','info');
    if ($btnPlay) $btnPlay.disabled=false; 
    if ($btnStop) $btnStop.disabled=true; 
    cleanup();
  }

  /* WS message handler ------------------------------------------------ */
  function handle (m) {
    switch(m.type){
      case 'routerRtpCapabilities':
        device.load({routerRtpCapabilities:m.payload})
          .then(()=>{ st('Creando transporte…','info'); send('createWebRtcTransport'); })
          .catch(e => {
            console.error('Error cargando dispositivo:', e);
            scheduleRetry();
          });
        break;

      case 'webRtcTransportCreated':
        // Integrar TURN server en iceServers
const iceServers = [
  {
    urls: [`turn:${TURN.ip}:${TURN.port}`],
    username: TURN.user,
    credential: TURN.pass
  }
];
recvTransport = device.createRecvTransport({ ...m.payload, iceServers });
        recvTransport.on('connect',({dtlsParameters},cb)=>{ send('connectWebRtcTransport',{transportId:recvTransport.id,dtlsParameters}); cb();});
        recvTransport.on('connectionstatechange',s=> {
          console.log('Estado de conexión de transporte:', s);
          if(['failed','disconnected'].includes(s)) scheduleRetry();
        });
        if(producerReady) sendConsume();
        break;

      case 'producerReady':  producerReady=true; producerId=m.payload.producerId; if(recvTransport) sendConsume(); break;
      case 'producerClosed': producerReady=false; scheduleRetry(); break;

      case 'consumed': createConsumer(m.payload); break;

      case 'consumerResumed':
        st('▶ Reproduciendo','ok'); 
        if ($btnStop) $btnStop.disabled=false; 
        startT();
        setupMS(); requestWake(); break;

      case 'error': st('Error: '+m.payload.message,'error'); scheduleRetry(); break;
    }
  }

  function sendConsume(){
    if(!recvTransport||recvTransport.closed||!producerId) return;
    send('consume',{transportId:recvTransport.id,producerId,rtpCapabilities:device.rtpCapabilities});
  }

  /* Consumer con sample rate fijo ------------------------------------- */
  async function createConsumer({id,producerId,kind,rtpParameters}){
    try{
      consumer = await recvTransport.consume({id,producerId,kind,rtpParameters});
      
      // Crear AudioContext con sample rate fijo
      createAudioContext();
      
      $audio.srcObject = new MediaStream([consumer.track]);
      $audio.setAttribute('playsinline','');
      
      // Conectar a AudioContext
      connectAudioSource();
      
      if (isIOS) {
        // Workaround activación iOS
        document.body.click();
        await new Promise(resolve => setTimeout(resolve, 100));
        
        // Asegurar que AudioContext esté activo
        if (audioContext && audioContext.state !== 'running') {
          await audioContext.resume();
        }
      }
      
      await forcePlay($audio);

      if ('mediaSession' in navigator){
        navigator.mediaSession.playbackState = 'playing';
        try {
          navigator.mediaSession.setPositionState({duration:Infinity,playbackRate:1,position:0});
        } catch{}
      }

      send('resumeConsumer',{consumerId:consumer.id});
      setupMS(); requestWake(); monitorAudio();
    }catch(e){
      console.error('Error creando consumer:', e);
      st('Error creando reproductor','error'); scheduleRetry();
    }
  }

  /* Monitor de estado de audio ---------------------------------------- */
  function monitorAudio(){
    clearInterval(monitor);
    monitor=setInterval(()=>{
      if(intentionalPause) return;
      
      // Verificar estado del AudioContext
      if(audioContext && audioContext.state !== 'running'){
        audioContext.resume().catch(() => {});
      }
      
      // Si el playbackRate ha cambiado, corregirlo
      if ($audio.playbackRate !== 1.0) {
        console.log('Corrigiendo playbackRate a 1.0');
        $audio.playbackRate = 1.0;
      }
      
      // Verificar que audio esté reproduciendo
      if($audio.paused){
        forcePlay($audio).catch(() => scheduleRetry());
      }
    }, isIOS ? 2000 : 5000);  // Más frecuente en iOS
  }

  /* Lógica de reintentos mejorada ------------------------------------- */
  function scheduleRetry(){
    if(retryTimer || intentionalPause) return;
    if(isIOS && document.hidden) return;

    retryTimer = setTimeout(()=>{
      retryTimer=null;
      if(intentionalPause || intentionalClose) return;

      // Verificar conexión de red
      if (!navigator.onLine) {
        st('Sin conexión, esperando...', 'error');
        return;
      }

      // Asegurar que AudioContext esté activo
      if(audioContext?.state !== 'running'){
        try{ audioContext?.resume(); } catch{}
      }
      
      if(ws?.readyState === 1){
        if(consumer && !consumer.closed){
          // Solo intentar reproducir si está pausado
          if($audio.paused) forcePlay($audio).catch(() => safeSampleRateReconnect());
          return;
        }
        // Si tenemos productor pero no consumer, pedir consumer
        if(producerReady) return sendConsume();
      }
      
      // Reconexión completa pero preservando AudioContext
      safeSampleRateReconnect();
    }, isIOS ? 3000 : 3000);
  }

  /* Manejo de audio avanzado ------------------------------------------ */
  function pauseAudio(){
    intentionalPause=true;
    forcePause($audio);
    releaseWake();
    st('⏸ Pausado','wait');
    if('mediaSession' in navigator){
      navigator.mediaSession.playbackState='paused';
      try { navigator.mediaSession.setPositionState({}); } catch{}
    }
  }
  
  function resumeAudio(){
    intentionalPause=false;
    
    // Asegurar que AudioContext esté creado
    createAudioContext();
    
    forcePlay($audio)
      .then(()=>{ 
        st('▶ Reproduciendo','ok'); startT(); requestWake();
        if('mediaSession' in navigator){
          navigator.mediaSession.playbackState='playing';
          try { navigator.mediaSession.setPositionState({duration:Infinity,playbackRate:1,position:0}); } catch{}
        }
      })
      .catch(scheduleRetry);
  }

  /* Manejo específico para iOS --------------------------------------- */
  document.addEventListener('visibilitychange',()=>{
    if(document.visibilityState === 'visible' && !intentionalPause){
      if(isIOS){
        // Verificar contexto de audio
        if (!audioContext || audioContext.state === 'closed') {
          createAudioContext(true);
          connectAudioSource();
        } else if (audioContext.state !== 'running') {
          audioContext.resume().catch(() => {});
        }
      }
      
      // Intentar reproducir si está pausado
      if ($audio.paused && !intentionalPause) {
        forcePlay($audio).catch(scheduleRetry);
      }
    }
  });

  // Reset de audio en eventos táctiles
  document.addEventListener('touchstart',()=>{
    if(isIOS && $audio.paused && !intentionalPause){
      audioContext?.resume().catch(() => {});
      forcePlay($audio).catch(scheduleRetry);
    }
  }, {passive:true});

  /* Eventos de audio críticos ---------------------------------------- */
  $audio.addEventListener('stalled',() => !intentionalPause && scheduleRetry());
  $audio.addEventListener('error',  () => !intentionalPause && scheduleRetry());
  
  // Detectar cambios de playbackRate
  $audio.addEventListener('ratechange', () => {
    if ($audio.playbackRate !== 1.0) {
      console.log('playbackRate cambiado, corrigiendo a 1.0');
      $audio.playbackRate = 1.0;
    }
  });

  /* Controles -------------------------------------------------------- */
  window.rtcPlayerConnect    = () => (intentionalPause ? resumeAudio() : connect());
  window.rtcPlayerDisconnect = () => (consumer && !consumer.closed && !$audio.paused ? pauseAudio() : disconnect());
  
  // Asignar onclick solo si los elementos existen
  if ($btnPlay) $btnPlay.onclick = window.rtcPlayerConnect;
  if ($btnStop) $btnStop.onclick = window.rtcPlayerDisconnect;

  console.log('RTCStream Player v1.7.9 (Dinámico) listo');
})();
