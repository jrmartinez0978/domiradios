// === RTCStream Player v2.0 ====================================================
// Febrero-2026 · Módulo limpio sin UI hardcodeada
// Reproductor WebRTC ultra-baja latencia via mediasoup
// ======================================================================

(function () {
  'use strict';

  const TURN = { ip: '40.160.13.94', port: 2031, user: 'rtcuser', pass: 'rtcpass' };
  const SAMPLE_RATE = 48000;
  const PING_TIMEOUT_MS = 60000;
  const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);

  function validateSlug(slug) {
    if (!slug || typeof slug !== 'string') throw new Error('Slug requerido');
    const s = slug.trim();
    if (!s.length || !/^[a-zA-Z0-9_-]+$/.test(s)) throw new Error('Slug inválido: ' + slug);
    return s;
  }

  function generateListenerId() {
    return 'l_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
  }

  // -- Player instance factory --
  function createPlayer(config) {
    const slug = validateSlug(config.slug);
    const serverUrl = config.serverUrl || 'live.rtcstreaming.com';
    const port = config.port || 9000;

    let ws, device, recvTransport, consumer;
    let producerId = null, producerReady = false;
    let intentionalClose = false;
    let audioContext = null, mediaElementSource = null;
    let retryTimer = null, pingChecker = null, lastPing = 0;
    let listenerId = null, activityTimer = null;
    let state = 'disconnected'; // disconnected | connecting | connected | error

    const listeners = {};

    function emit(event, data) {
      (listeners[event] || []).forEach(fn => { try { fn(data); } catch (e) { console.error(e); } });
    }

    function setState(s, detail) {
      if (state === s) return;
      state = s;
      emit('state', { state: s, detail: detail || '' });
    }

    function send(type, payload) {
      if (ws && ws.readyState === 1) ws.send(JSON.stringify({ type, payload: payload || {} }));
    }

    // -- AudioContext 48kHz --
    function ensureAudioContext() {
      if (audioContext && audioContext.state !== 'closed') return;
      const AC = window.AudioContext || window.webkitAudioContext;
      try {
        audioContext = new AC({ latencyHint: 'interactive', sampleRate: SAMPLE_RATE });
      } catch (e) {
        audioContext = new AC();
      }
    }

    function connectAudioSource(audioEl) {
      if (!audioContext || !audioEl.srcObject) return;
      try {
        if (mediaElementSource) { mediaElementSource.disconnect(); mediaElementSource = null; }
        mediaElementSource = audioContext.createMediaElementSource(audioEl);
        mediaElementSource.connect(audioContext.destination);
      } catch (e) {
        console.warn('RTCStream: Error conectando fuente de audio:', e);
      }
    }

    // -- Listener tracking --
    async function trackListener() {
      if (!slug) return;
      if (!listenerId) listenerId = generateListenerId();
      try {
        const r = await fetch(`https://rtcstreaming.com/api/v1/embed/track/${encodeURIComponent(slug)}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify({ listener_id: listenerId, source: 'embed', referrer: document.referrer || location.href, user_agent: navigator.userAgent, timestamp: new Date().toISOString() })
        });
        if (r.ok) startActivity();
      } catch (e) { console.warn('RTCStream: track error', e); }
    }

    function startActivity() {
      stopActivity();
      activityTimer = setInterval(() => {
        if (consumer && !consumer.closed) {
          fetch('https://rtcstreaming.com/api/v1/embed/activity', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ listener_id: listenerId, timestamp: new Date().toISOString() })
          }).catch(() => {});
        }
      }, 30000);
    }

    function stopActivity() {
      if (activityTimer) { clearInterval(activityTimer); activityTimer = null; }
    }

    async function disconnectListener() {
      stopActivity();
      if (!listenerId) return;
      try {
        await fetch('https://rtcstreaming.com/api/v1/embed/disconnect', {
          method: 'POST', headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ radio_slug: slug, listener_id: listenerId, timestamp: new Date().toISOString() })
        });
      } catch (e) { /* ignore */ }
      listenerId = null;
    }

    // -- Ping/pong heartbeat --
    function startPingCheck() {
      stopPingCheck();
      lastPing = Date.now();
      pingChecker = setInterval(() => {
        if (Date.now() - lastPing > PING_TIMEOUT_MS) {
          console.warn('RTCStream: ping timeout');
          stopPingCheck();
          scheduleRetry();
        }
      }, 15000);
    }

    function stopPingCheck() {
      if (pingChecker) { clearInterval(pingChecker); pingChecker = null; }
    }

    // -- Cleanup --
    function cleanup() {
      stopPingCheck();
      clearTimeout(retryTimer); retryTimer = null;
      if (ws) { try { ws.close(); } catch (e) {} ws = null; }
      if (consumer) { try { consumer.close(); } catch (e) {} consumer = null; }
      if (recvTransport) { try { recvTransport.close(); } catch (e) {} recvTransport = null; }
      if (mediaElementSource) { try { mediaElementSource.disconnect(); } catch (e) {} mediaElementSource = null; }
      if (intentionalClose && audioContext) {
        try { audioContext.close(); } catch (e) {}
        audioContext = null;
      }
      producerReady = false;
      producerId = null;
      device = null;
    }

    // -- Retry --
    function scheduleRetry() {
      if (retryTimer || intentionalClose) return;
      retryTimer = setTimeout(() => {
        retryTimer = null;
        if (intentionalClose) return;
        if (!navigator.onLine) { setState('error', 'Sin conexión'); return; }
        connect();
      }, 3000);
    }

    // -- WS message handler --
    function handle(m) {
      console.log('RTCStream: Mensaje:', m.type);
      switch (m.type) {
        case 'routerRtpCapabilities':
          device.load({ routerRtpCapabilities: m.payload })
            .then(function() { console.log('RTCStream: Device cargado'); send('createWebRtcTransport'); })
            .catch(function(e) { console.error('RTCStream: Error cargando device:', e); scheduleRetry(); });
          break;

        case 'webRtcTransportCreated': {
          const iceServers = [{ urls: [`turn:${TURN.ip}:${TURN.port}`], username: TURN.user, credential: TURN.pass }];
          recvTransport = device.createRecvTransport({ ...m.payload, iceServers });
          recvTransport.on('connect', ({ dtlsParameters }, cb) => { send('connectWebRtcTransport', { transportId: recvTransport.id, dtlsParameters }); cb(); });
          recvTransport.on('connectionstatechange', s => {
            if (s === 'connected') trackListener();
            if (['failed', 'disconnected'].includes(s)) scheduleRetry();
          });
          if (producerReady) sendConsume();
          break;
        }

        case 'webRtcTransportConnected': break;

        case 'producerReady':
          producerReady = true;
          producerId = m.payload.producerId;
          if (recvTransport) sendConsume();
          break;

        case 'producerClosed':
          producerReady = false;
          scheduleRetry();
          break;

        case 'consumed':
          createConsumer(m.payload);
          break;

        case 'consumerResumed':
          setState('connected');
          break;

        case 'ping':
          lastPing = Date.now();
          send('pong', { timestamp: m.payload?.timestamp || Date.now(), source: 'player-v2' });
          break;

        case 'error':
          setState('error', m.payload?.message);
          scheduleRetry();
          break;
      }
    }

    function sendConsume() {
      if (!recvTransport || recvTransport.closed || !producerId) return;
      send('consume', { transportId: recvTransport.id, producerId, rtpCapabilities: device.rtpCapabilities });
    }

    async function createConsumer({ id, producerId: pid, kind, rtpParameters }) {
      try {
        console.log('RTCStream: Creando consumer, kind:', kind);
        consumer = await recvTransport.consume({ id, producerId: pid, kind, rtpParameters });
        console.log('RTCStream: Consumer creado, track:', consumer.track.kind, consumer.track.readyState);
        var stream = new MediaStream([consumer.track]);
        emit('stream', stream);
        send('resumeConsumer', { consumerId: consumer.id });
      } catch (e) {
        console.error('RTCStream: Error creando consumer:', e);
        setState('error', 'Error creando reproductor');
        scheduleRetry();
      }
    }

    // -- Public: connect --
    function connect() {
      if (state === 'connecting' && ws && ws.readyState < 2) return; // solo ignorar si ya hay WS activo
      intentionalClose = false;
      cleanup();
      setState('connecting');
      ensureAudioContext();

      var wsUrl = 'wss://' + serverUrl + ':' + port + '/ws/listen/' + slug;
      console.log('RTCStream: Conectando a', wsUrl);
      ws = new WebSocket(wsUrl);
      ws.binaryType = 'arraybuffer';

      var timeout = setTimeout(function() {
        if (ws && ws.readyState !== WebSocket.OPEN) {
          console.warn('RTCStream: Timeout de conexión WebSocket');
          ws.close();
          scheduleRetry();
        }
      }, 8000);

      ws.onopen = function() {
        clearTimeout(timeout);
        console.log('RTCStream: WebSocket abierto');
        startPingCheck();
        if (typeof mediasoupClient === 'undefined') {
          console.error('RTCStream: mediasoupClient no definido');
          setState('error', 'mediasoup no cargado');
          return;
        }
        device = new mediasoupClient.Device();
        send('getRouterRtpCapabilities');
      };

      ws.onclose = function(ev) {
        clearTimeout(timeout);
        stopPingCheck();
        console.log('RTCStream: WebSocket cerrado', ev.code, ev.reason);
        if (!intentionalClose) { setState('error', 'Desconectado'); scheduleRetry(); }
      };

      ws.onerror = function(ev) {
        console.error('RTCStream: WebSocket error');
      };

      ws.onmessage = function(ev) {
        try { handle(JSON.parse(ev.data)); } catch (e) { console.error('RTCStream: parse error', e); }
      };
    }

    // -- Public: disconnect --
    function disconnect() {
      intentionalClose = true;
      disconnectListener();
      cleanup();
      setState('disconnected');
    }

    // -- Public: attach to audio element --
    function attachAudio(audioEl) {
      ensureAudioContext();
      connectAudioSource(audioEl);
    }

    // -- Public: get AudioContext (para que el caller pueda resumir) --
    function getAudioContext() {
      ensureAudioContext();
      return audioContext;
    }

    return {
      connect,
      disconnect,
      attachAudio,
      getAudioContext,
      on(event, fn) {
        if (!listeners[event]) listeners[event] = [];
        listeners[event].push(fn);
        return this;
      },
      off(event, fn) {
        if (!listeners[event]) return this;
        listeners[event] = listeners[event].filter(f => f !== fn);
        return this;
      },
      get state() { return state; },
      get slug() { return slug; },
      get serverUrl() { return serverUrl; },
      get port() { return port; }
    };
  }

  // -- Load mediasoup-client dynamically --
  let mediasoupLoading = null;
  function loadMediasoup(serverUrl, port) {
    if (typeof window.mediasoupClient !== 'undefined') return Promise.resolve();
    if (mediasoupLoading) return mediasoupLoading;
    mediasoupLoading = new Promise((resolve, reject) => {
      const script = document.createElement('script');
      script.src = `https://${serverUrl}:${port}/mediasoup-client.min.js`;
      script.onload = () => resolve();
      script.onerror = () => { mediasoupLoading = null; reject(new Error('No se pudo cargar mediasoup-client')); };
      document.head.appendChild(script);
    });
    return mediasoupLoading;
  }

  // -- Public API --
  window.RTCStreamPlayer = {
    create: createPlayer,
    loadMediasoup: loadMediasoup
  };
})();
