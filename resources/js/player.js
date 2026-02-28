/**
 * Domiradios - Global Radio Player (Alpine.js Store)
 * Unifica HTML5 Audio + RTCStream en un player global persistente.
 */
document.addEventListener('alpine:init', () => {
    Alpine.store('player', {
        // State
        state: 'idle', // idle | connecting | playing | error | offline
        radioId: null,
        radioName: '',
        radioImage: '',
        radioSlug: '',
        radioFrequency: '',
        currentTrack: '',
        listeners: 0,
        volume: 80,
        isFavorite: false,
        isRTC: false,

        // Internal refs
        _audio: null,
        _rtcPlayer: null,
        _rtcAudio: null,
        _reconnectAttempts: 0,
        _maxReconnect: 2,
        _reconnectTimeout: null,
        _trackInterval: null,
        _registeredPlay: false,
        _rtcScriptLoaded: false,

        get isPlaying() { return this.state === 'playing'; },
        get isConnecting() { return this.state === 'connecting'; },
        get isActive() { return this.state !== 'idle'; },

        /**
         * Play a radio station from card data
         */
        play(data) {
            // data: { id, name, image, slug, frequency, streamUrl, sourceRadio, rtcSlug, rtcServer, rtcPort }
            const isSameRadio = this.radioId === data.id;

            // Toggle if same radio is playing
            if (isSameRadio && this.state === 'playing') {
                this.stop();
                return;
            }

            // Stop any current playback
            this.stop();

            // Set radio info
            this.radioId = data.id;
            this.radioName = data.name;
            this.radioImage = data.image;
            this.radioSlug = data.slug;
            this.radioFrequency = data.frequency || '';
            this.currentTrack = '';
            this.listeners = 0;
            this.isRTC = data.sourceRadio === 'RTCStream';
            this._registeredPlay = false;
            this.state = 'connecting';

            // Check favorite status
            this._checkFavorite();

            if (this.isRTC) {
                this._playRTC(data);
            } else {
                this._playHTML5(data.streamUrl);
            }

            // Start fetching current track
            this._startTrackPolling();
        },

        /**
         * HTML5 Audio playback
         */
        _playHTML5(streamUrl) {
            this._audio = new Audio(streamUrl);
            this._audio.crossOrigin = 'anonymous';
            this._audio.volume = this.volume / 100;

            this._audio.addEventListener('canplay', () => {
                this._audio.play().then(() => {
                    this.state = 'playing';
                    this._reconnectAttempts = 0;
                    this._registerPlay();
                    this._updateMediaSession();
                }).catch(() => this._attemptReconnect());
            });

            this._audio.addEventListener('playing', () => {
                this.state = 'playing';
                this._updateMediaSession();
            });

            this._audio.addEventListener('error', () => {
                this.state = 'connecting';
                this._attemptReconnect();
            });

            this._audio.addEventListener('stalled', () => {
                this.state = 'connecting';
                this._attemptReconnect();
            });

            this._audio.play().then(() => {
                this.state = 'playing';
                this._reconnectAttempts = 0;
                this._registerPlay();
                this._updateMediaSession();
            }).catch(() => this._attemptReconnect());
        },

        /**
         * RTCStream playback
         */
        async _playRTC(data) {
            // Create AudioContext during user gesture
            const AC = window.AudioContext || window.webkitAudioContext;
            if (AC && !window._rtcUserAudioCtx) {
                try {
                    window._rtcUserAudioCtx = new AC({ sampleRate: 48000 });
                    window._rtcUserAudioCtx.resume();
                } catch(e) {}
            }

            // Create audio element during user gesture
            this._rtcAudio = new Audio();
            this._rtcAudio.setAttribute('playsinline', '');
            this._rtcAudio.setAttribute('webkit-playsinline', '');
            this._rtcAudio.volume = this.volume / 100;

            try {
                await this._loadRTCScript();
                await RTCStreamPlayer.loadMediasoup(data.rtcServer, data.rtcPort);

                const player = RTCStreamPlayer.create({
                    slug: data.rtcSlug,
                    serverUrl: data.rtcServer,
                    port: data.rtcPort
                });
                this._rtcPlayer = player;

                player.on('stream', (mediaStream) => {
                    this._rtcAudio.srcObject = mediaStream;
                    player.attachAudio(this._rtcAudio);
                    const ctx = player.getAudioContext();
                    if (ctx && ctx.state !== 'running') ctx.resume();
                    this._rtcAudio.play().then(() => {
                        this.state = 'playing';
                        this._registerPlay();
                        this._updateMediaSession();
                    }).catch(() => {
                        this.state = 'offline';
                    });
                });

                player.on('state', (info) => {
                    if (info.state === 'error') {
                        setTimeout(() => {
                            if (this._rtcPlayer === player && player.state === 'error') {
                                this.state = 'offline';
                            }
                        }, 10000);
                    }
                    if (info.state === 'connecting') {
                        this.state = 'connecting';
                    }
                });

                player.connect();
            } catch (e) {
                console.error('RTCStream error:', e);
                this.state = 'offline';
                this._rtcPlayer = null;
            }
        },

        /**
         * Stop all playback
         */
        stop() {
            // Stop HTML5
            if (this._audio) {
                this._audio.pause();
                this._audio.src = '';
                this._audio = null;
            }

            // Stop RTC
            if (this._rtcPlayer) {
                this._rtcPlayer.disconnect();
                this._rtcPlayer = null;
            }
            if (this._rtcAudio) {
                this._rtcAudio.pause();
                this._rtcAudio.srcObject = null;
                this._rtcAudio = null;
            }

            // Clear reconnect
            clearTimeout(this._reconnectTimeout);
            this._reconnectAttempts = 0;

            // Clear track polling
            clearInterval(this._trackInterval);

            this.state = 'idle';
        },

        /**
         * Toggle play/pause
         */
        toggle() {
            if (this.state === 'playing') {
                if (this._audio) this._audio.pause();
                if (this._rtcAudio) this._rtcAudio.pause();
                this.state = 'idle';
            } else if (this.state === 'idle' && this.radioId) {
                if (this._audio) {
                    this._audio.play();
                    this.state = 'playing';
                }
                if (this._rtcAudio) {
                    this._rtcAudio.play();
                    this.state = 'playing';
                }
            }
        },

        /**
         * Set volume (0-100)
         */
        setVolume(val) {
            this.volume = val;
            if (this._audio) this._audio.volume = val / 100;
            if (this._rtcAudio) this._rtcAudio.volume = val / 100;
        },

        /**
         * Toggle favorite
         */
        toggleFavorite() {
            if (!this.radioId) return;
            let favs = JSON.parse(localStorage.getItem('favoritos') || '[]');
            const idx = favs.indexOf(this.radioId.toString());
            if (idx > -1) {
                favs.splice(idx, 1);
                this.isFavorite = false;
            } else {
                favs.push(this.radioId.toString());
                this.isFavorite = true;
            }
            localStorage.setItem('favoritos', JSON.stringify(favs));
        },

        // --- Private methods ---

        _checkFavorite() {
            const favs = JSON.parse(localStorage.getItem('favoritos') || '[]');
            this.isFavorite = favs.includes(this.radioId?.toString());
        },

        _attemptReconnect() {
            if (this._reconnectAttempts >= this._maxReconnect) {
                this.state = 'offline';
                this._reconnectAttempts = 0;
                return;
            }
            this._reconnectAttempts++;
            this.state = 'connecting';
            this._reconnectTimeout = setTimeout(() => {
                if (this._audio) {
                    this._audio.play().then(() => {
                        this.state = 'playing';
                        this._reconnectAttempts = 0;
                        this._registerPlay();
                    }).catch(() => this._attemptReconnect());
                }
            }, 7000);
        },

        _registerPlay() {
            if (this._registeredPlay || !this.radioId) return;
            this._registeredPlay = true;
            const ctrl = new AbortController();
            const t = setTimeout(() => ctrl.abort(), 5000);
            fetch('/radio/register-play', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ radio_id: this.radioId }),
                signal: ctrl.signal
            }).then(r => { clearTimeout(t); }).catch(() => clearTimeout(t));
        },

        _startTrackPolling() {
            clearInterval(this._trackInterval);
            this._fetchTrack();
            this._trackInterval = setInterval(() => this._fetchTrack(), 30000);
        },

        _fetchTrack() {
            if (!this.radioId) return;
            const ctrl = new AbortController();
            const t = setTimeout(() => ctrl.abort(), 5000);
            fetch(`/api/radio/current-track/${this.radioId}`, { signal: ctrl.signal })
                .then(r => { clearTimeout(t); if (!r.ok) throw new Error(); return r.json(); })
                .then(data => {
                    if (data.track) this.currentTrack = data.track;
                    if (data.listeners !== undefined) this.listeners = data.listeners;
                })
                .catch(() => clearTimeout(t));
        },

        _updateMediaSession() {
            if (!('mediaSession' in navigator)) return;
            navigator.mediaSession.metadata = new MediaMetadata({
                title: this.currentTrack || this.radioName + ' - En vivo',
                artist: this.radioName,
                album: 'Domiradios',
                artwork: this.radioImage ? [
                    { src: this.radioImage, sizes: '96x96', type: 'image/png' },
                    { src: this.radioImage, sizes: '256x256', type: 'image/png' },
                    { src: this.radioImage, sizes: '512x512', type: 'image/png' }
                ] : []
            });
            navigator.mediaSession.setActionHandler('play', () => this.toggle());
            navigator.mediaSession.setActionHandler('pause', () => this.toggle());
            navigator.mediaSession.setActionHandler('stop', () => this.stop());
        },

        _loadRTCScript() {
            return new Promise((resolve, reject) => {
                if (window.RTCStreamPlayer) { resolve(); return; }
                if (this._rtcScriptLoaded) { resolve(); return; }
                const s = document.createElement('script');
                s.src = '/js/rtc-player-v2.js?v=2.0';
                s.onload = () => { this._rtcScriptLoaded = true; resolve(); };
                s.onerror = () => reject(new Error('Error cargando rtc-player-v2.js'));
                document.head.appendChild(s);
            });
        }
    });
});
