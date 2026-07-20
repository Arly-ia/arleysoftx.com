<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panel de rutas — ArleySoftX">
    <title>Puntico · Rutas del Proyecto | ArleySoftX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Outfit', sans-serif;
            background: #07070f;
            color: #fff;
            min-height: 100vh;
            padding-bottom: 80px;
            background-image:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(120,80,255,0.15), transparent),
                radial-gradient(ellipse 50% 40% at 90% 80%, rgba(229,57,53,0.08), transparent);
        }

        header {
            padding: 40px 32px 28px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .logo {
            font-family: 'Bebas Neue', cursive;
            font-size: 13px;
            letter-spacing: 5px;
            color: rgba(255,255,255,0.3);
            margin-bottom: 10px;
        }
        h1 {
            font-family: 'Bebas Neue', cursive;
            font-size: clamp(42px, 8vw, 72px);
            letter-spacing: 4px;
            background: linear-gradient(135deg, #fff 0%, rgba(120,80,255,0.9) 60%, #e53935 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 14px;
            color: rgba(255,255,255,0.35);
            font-weight: 400;
            letter-spacing: 1px;
        }
        .dot {
            display: inline-block;
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #10b981;
            margin-right: 6px;
            vertical-align: middle;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.5); }
            50%       { box-shadow: 0 0 0 6px rgba(16,185,129,0); }
        }

        .stats-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            padding: 18px 24px;
            background: rgba(255,255,255,0.025);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            justify-content: center;
        }
        .stat-chip {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 4px 14px;
            font-weight: 600;
        }
        .stat-chip strong { color: #fff; font-weight: 700; }

        main {
            max-width: 900px;
            margin: 0 auto;
            padding: 36px 24px;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .section-title {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.06);
        }

        .route-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px;
            text-decoration: none;
            color: inherit;
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
            margin-bottom: 8px;
        }
        .route-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, var(--accent, rgba(120,80,255,0.06)), transparent);
            opacity: 0;
            transition: opacity 0.25s;
        }
        .route-card:hover { border-color: rgba(255,255,255,0.15); transform: translateX(4px); }
        .route-card:hover::before { opacity: 1; }

        .route-icon {
            font-size: 24px;
            flex-shrink: 0;
            width: 44px; height: 44px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
        }
        .route-info { flex: 1; min-width: 0; }
        .route-name {
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .route-url {
            font-size: 12px;
            color: rgba(255,255,255,0.35);
            font-weight: 400;
            font-family: 'Courier New', monospace;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .route-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }
        .badge-externo { background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.25); }
        .badge-privado { background: rgba(245,158,11,0.12); color: #fbbf24; border: 1px solid rgba(245,158,11,0.25); }

        .route-arrow {
            font-size: 16px;
            color: rgba(255,255,255,0.15);
            flex-shrink: 0;
            transition: color 0.2s, transform 0.2s;
        }
        .route-card:hover .route-arrow { color: rgba(255,255,255,0.5); transform: translateX(3px); }

        .copy-toast {
            position: fixed;
            bottom: 30px; left: 50%; transform: translateX(-50%) translateY(20px);
            background: rgba(16,185,129,0.95);
            color: #fff;
            padding: 10px 22px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            z-index: 999;
            opacity: 0;
            transition: all 0.3s;
            pointer-events: none;
            white-space: nowrap;
        }
        .copy-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

        @media (max-width: 600px) {
            header { padding: 28px 20px 20px; }
            main { padding: 24px 16px; }
        }
    </style>
</head>
<body>

<header>
    <div class="logo">ARLEYSOFTX · PANEL INTERNO</div>
    <h1>📍 Puntico</h1>
    <div class="subtitle"><span class="dot"></span>Panel de rutas · {{ date('d M Y') }}</div>
</header>

<div class="stats-row">
    <div class="stat-chip">Total: <strong>5 rutas</strong></div>
    <div class="stat-chip">🟢 <strong>Externo</strong> · acceso abierto</div>
    <div class="stat-chip">🟡 <strong>Privado</strong> · uso interno</div>
</div>

<main>

    {{-- ── GENERAL ── --}}
    <div>
        <div class="section-title">🌐 General</div>

        <a class="route-card" href="https://arleysoftx.com/" target="_blank"
           style="--accent: rgba(120,80,255,0.08);" onclick="copyUrl(event,'https://arleysoftx.com/')">
            <div class="route-icon">🏠</div>
            <div class="route-info">
                <div class="route-name">Home — ArleySoftX</div>
                <div class="route-url">arleysoftx.com/</div>
            </div>
            <span class="route-badge badge-externo">Externo</span>
            <span class="route-arrow">→</span>
        </a>

        <a class="route-card" href="https://arleysoftx.com/reporte-ia" target="_blank"
           style="--accent: rgba(120,80,255,0.08);" onclick="copyUrl(event,'https://arleysoftx.com/reporte-ia')">
            <div class="route-icon">🤖</div>
            <div class="route-info">
                <div class="route-name">Reporte IA Generativa</div>
                <div class="route-url">arleysoftx.com/reporte-ia</div>
            </div>
            <span class="route-badge badge-externo">Externo</span>
            <span class="route-arrow">→</span>
        </a>
    </div>

    {{-- ── TUTORIALES TASK ── --}}
    <div>
        <div class="section-title">🎯 Tutoriales Task</div>

        <a class="route-card" href="https://arleysoftx.com/tutoriales-task" target="_blank"
           style="--accent: rgba(229,57,53,0.08);" onclick="copyUrl(event,'https://arleysoftx.com/tutoriales-task')">
            <div class="route-icon">🎬</div>
            <div class="route-info">
                <div class="route-name">Landing de venta — Tutorial POV Task</div>
                <div class="route-url">arleysoftx.com/tutoriales-task</div>
            </div>
            <span class="route-badge badge-externo">Externo</span>
            <span class="route-arrow">→</span>
        </a>
    </div>

    {{-- ── JJ CONSTRUCCION ── --}}
    <div>
        <div class="section-title">🏗️ JJ Construcción · 20 Wings</div>

        <a class="route-card" href="https://arleysoftx.com/jj-construccion" target="_blank"
           style="--accent: rgba(245,158,11,0.08);" onclick="copyUrl(event,'https://arleysoftx.com/jj-construccion')">
            <div class="route-icon">🏗️</div>
            <div class="route-info">
                <div class="route-name">JJ Construcción — Portal</div>
                <div class="route-url">arleysoftx.com/jj-construccion</div>
            </div>
            <span class="route-badge badge-privado">Privado</span>
            <span class="route-arrow">→</span>
        </a>

        <a class="route-card" href="https://arleysoftx.com/jj-construccion/20wings-tareas" target="_blank"
           style="--accent: rgba(245,158,11,0.1);" onclick="copyUrl(event,'https://arleysoftx.com/jj-construccion/20wings-tareas')">
            <div class="route-icon">✅</div>
            <div class="route-info">
                <div class="route-name">20 Wings · Gestión de tareas de obra</div>
                <div class="route-url">arleysoftx.com/jj-construccion/20wings-tareas</div>
            </div>
            <span class="route-badge badge-privado">Privado</span>
            <span class="route-arrow">→</span>
        </a>
    </div>

</main>

<div class="copy-toast" id="copyToast">📋 URL copiada</div>

<script>
function copyUrl(e, url) {
    navigator.clipboard.writeText(url).catch(() => {});
    const toast = document.getElementById('copyToast');
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2200);
}
</script>

</body>
</html>
