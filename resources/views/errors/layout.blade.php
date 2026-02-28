<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') - Domiradios</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            html, body {
                background-color: #0a0a0f;
                color: #8b8fa8;
                font-family: 'Inter', system-ui, sans-serif;
                height: 100vh;
            }
            .container {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                height: 100vh;
                text-align: center;
                padding: 2rem;
            }
            .code {
                font-size: 7rem;
                font-weight: 800;
                background: linear-gradient(135deg, #E21C25, #ff4444);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                line-height: 1;
                margin-bottom: 1rem;
            }
            .message {
                font-size: 1.25rem;
                font-weight: 600;
                color: #e5e7eb;
                margin-bottom: 0.5rem;
            }
            .detail {
                font-size: 0.875rem;
                color: #5c5f7a;
                margin-bottom: 2rem;
            }
            .back-link {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.75rem 1.5rem;
                background: rgba(255,255,255,0.05);
                border: 1px solid rgba(255,255,255,0.08);
                border-radius: 0.75rem;
                color: #e5e7eb;
                text-decoration: none;
                font-size: 0.875rem;
                font-weight: 600;
                transition: all 0.2s;
                backdrop-filter: blur(12px);
            }
            .back-link:hover {
                background: rgba(255,255,255,0.1);
                border-color: rgba(255,255,255,0.12);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="code">@yield('code')</div>
            <div class="message">@yield('message')</div>
            <div class="detail">@yield('detail', 'Algo salió mal. Por favor intenta de nuevo.')</div>
            <a href="/" class="back-link">&larr; Volver a Domiradios</a>
        </div>
    </body>
</html>
