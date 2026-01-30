<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Player</title>
    <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            background: #000; 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow: hidden;
        }
        
        .player-container { 
            width: 100vw; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            background: #000;
        }
        
        video { 
            max-width: 100%; 
            max-height: 100vh; 
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 48px;
            color: rgba(255, 255, 255, 0.08);
            pointer-events: none;
            text-align: center;
            z-index: 1;
            user-select: none;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .vjs-control-bar { 
            display: none !important; 
        }
        
        .video-js {
            width: 100%;
            height: 100%;
        }

        .vjs-pip-container {
            display: none !important;
        }

        .video-time-display {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
            font-family: 'Courier New', monospace;
            z-index: 999;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="video-time-display" id="timeDisplay">00:00 / 00:00</div>
    
    <div class="watermark" id="watermark">{{ auth()->user()->name ?? 'Protected Content' }}</div>
    
    <div class="player-container">
        <video id="player" class="video-js vjs-default-skin" playsinline>
            @if(!empty($videoUrl))
                <source src="{{ $videoUrl }}" type="video/mp4">
            @endif
            Your browser does not support HTML5 video.
        </video>
    </div>
    <script src="https://vjs.zencdn.net/7.20.3/video.js"></script>
    <script>
        document.addEventListener('contextmenu', e => e.preventDefault());
        document.addEventListener('keydown', e => {
            if (e.key === 'F12' || 
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.shiftKey && e.key === 'C') ||
                (e.ctrlKey && e.shiftKey && e.key === 'J') ||
                (e.ctrlKey && e.key === 's') ||
                (e.ctrlKey && e.key === 'S')) {
                e.preventDefault();
            }
        });
        document.addEventListener('dragstart', e => e.preventDefault());
        document.addEventListener('drop', e => e.preventDefault());
        const player = videojs('player', {
            controls: true,
            autoplay: false,
            preload: 'auto',
            width: '100%',
            height: '100%',
            controlBar: {
                children: ['playToggle', 'progressControl', 'volumePanel', 'fullscreenToggle']
            }
        });
        player.on('timeupdate', function() {
            const currentTime = Math.floor(player.currentTime());
            const duration = Math.floor(player.duration());
            
            const formatTime = (seconds) => {
                if (isNaN(seconds)) return '00:00';
                const mins = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            };
            
            document.getElementById('timeDisplay').textContent = formatTime(currentTime) + ' / ' + formatTime(duration);
        });

        player.on('loadedmetadata', function() {
            const duration = Math.floor(player.duration());
            const formatTime = (seconds) => {
                if (isNaN(seconds)) return '00:00';
                const mins = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            };
            document.getElementById('timeDisplay').textContent = '00:00 / ' + formatTime(duration);
        });
        player.ready(function() {
            const downloadBtn = this.controlBar.getChild('DownloadButton');
            if (downloadBtn) {
                downloadBtn.hide();
            }
            this.el().addEventListener('contextmenu', e => e.preventDefault());
        });
        setInterval(() => {
            const wm = document.getElementById('watermark');
            wm.style.opacity = Math.random() * 0.12 + 0.03;
        }, 1000);
        setInterval(() => {
            if (window.devtools && window.devtools.open) {
                window.location.href = '/';
            }
        }, 100);
    </script>
</body>
</html>
