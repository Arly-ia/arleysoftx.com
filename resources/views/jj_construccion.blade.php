<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="JJ Construccion - Empresas aliadas: Chalet Motel 192 y 20 Wings">
    <title>JJ Construccion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #1a1a2e;
            min-height: 100vh;
            color: #fff;
        }

        /* ===== BANNER ===== */
        .banner {
            position: relative;
            width: 100%;
            min-height: 260px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 20px;
        }

        /* Banderas como fondo del banner */
        .flags-bg {
            position: absolute;
            inset: 0;
            display: flex;
            width: 100%;
            height: 100%;
        }

        /* Bandera Colombia */
        .flag-colombia {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .flag-colombia .stripe-yellow { background: #FCD116; flex: 2; }
        .flag-colombia .stripe-blue   { background: #003893; flex: 1; }
        .flag-colombia .stripe-red    { background: #CE1126; flex: 1; }

        /* Bandera Venezuela */
        .flag-venezuela {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .flag-venezuela .stripe-yellow { background: #CF1020; flex: 1; }
        .flag-venezuela .stripe-blue   { background: #00247D; flex: 1; }
        .flag-venezuela .stripe-red    { background: #CF1020; flex: 1; }

        /* Overlay oscuro para que se lea bien el texto */
        .banner-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.52);
        }

        /* Línea divisora de banderas */
        .flag-divider {
            position: absolute;
            top: 0; bottom: 0;
            left: 50%;
            width: 3px;
            background: rgba(255,255,255,0.3);
            z-index: 1;
        }

        /* Texto del banner */
        .banner-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .banner-title {
            font-family: 'Bebas Neue', cursive;
            font-size: clamp(52px, 10vw, 110px);
            letter-spacing: 6px;
            color: #fff;
            text-shadow:
                0 0 20px rgba(252,209,22,0.8),
                0 4px 16px rgba(0,0,0,0.9),
                2px 2px 0 #CE1126,
                -2px -2px 0 #003893;
            line-height: 1;
            animation: glow 3s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { text-shadow: 0 0 20px rgba(252,209,22,0.8), 0 4px 16px rgba(0,0,0,0.9), 2px 2px 0 #CE1126, -2px -2px 0 #003893; }
            to   { text-shadow: 0 0 40px rgba(252,209,22,1),   0 4px 24px rgba(0,0,0,0.9), 2px 2px 0 #CE1126, -2px -2px 0 #003893; }
        }

        .banner-subtitle {
            font-size: 14px;
            letter-spacing: 8px;
            text-transform: uppercase;
            color: #FCD116;
            margin-top: 6px;
            font-weight: 600;
            text-shadow: 0 2px 8px rgba(0,0,0,0.8);
        }

        /* ===== SECCIÓN DE EMPRESAS ===== */
        .section-title {
            text-align: center;
            padding: 40px 20px 10px;
            font-family: 'Bebas Neue', cursive;
            font-size: 28px;
            letter-spacing: 4px;
            color: #FCD116;
            text-transform: uppercase;
        }

        .companies-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            padding: 30px 20px 60px;
            max-width: 900px;
            margin: 0 auto;
        }

        /* ===== CARD BASE ===== */
        .card {
            background: linear-gradient(145deg, #16213e, #0f3460);
            border: 1px solid rgba(252,209,22,0.25);
            border-radius: 18px;
            width: 340px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            transition: transform 0.35s ease, box-shadow 0.35s ease;
            position: relative;
        }

        .card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 50px rgba(252,209,22,0.2), 0 8px 30px rgba(0,0,0,0.6);
        }

        .card-badge {
            position: absolute;
            top: 14px; left: 14px;
            background: rgba(252,209,22,0.9);
            color: #1a1a2e;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            z-index: 3;
        }

        .card-image-wrap {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .card-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }

        .card:hover .card-image-wrap img {
            transform: scale(1.08);
        }

        .card-image-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 70px;
            background: linear-gradient(to top, #16213e, transparent);
        }

        .card-body {
            padding: 20px 22px 24px;
        }

        .card-number {
            font-size: 11px;
            color: #FCD116;
            letter-spacing: 3px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .card-name {
            font-family: 'Bebas Neue', cursive;
            font-size: 32px;
            letter-spacing: 2px;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 10px;
        }

        .card-desc {
            font-size: 13px;
            color: rgba(255,255,255,0.6);
            line-height: 1.6;
        }

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 22px;
            border-top: 1px solid rgba(252,209,22,0.15);
        }

        .card-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255,255,255,0.07);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            color: rgba(255,255,255,0.7);
        }

        /* ===== CHALET MOTEL 192 especifico ===== */
        .card-chalet .card-image-wrap {
            background: linear-gradient(135deg, #2d1b69, #11998e);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chalet-number {
            font-family: 'Bebas Neue', cursive;
            font-size: 100px;
            color: rgba(255,255,255,0.15);
            line-height: 1;
            position: absolute;
            bottom: -10px;
            right: 10px;
        }

        .chalet-icon {
            font-size: 64px;
            margin-bottom: 8px;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.5));
        }

        /* ===== 20 WINGS especifico ===== */
        .card-wings .card-image-wrap {
            background: #1a0a00;
        }

        .card-wings .card-image-wrap img {
            object-fit: contain;
            padding: 10px;
        }

        /* ===== FOOTER PAGE ===== */
        .page-footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: rgba(255,255,255,0.25);
            letter-spacing: 2px;
        }

        @media (max-width: 600px) {
            .card { width: 100%; max-width: 380px; }
            .banner-title { font-size: 52px; }
        }
    </style>
</head>
<body>

    <!-- ===== BANNER ===== -->
    <div class="banner">
        <!-- Banderas de fondo -->
        <div class="flags-bg">
            <!-- Colombia izquierda -->
            <div class="flag-colombia">
                <div class="stripe-yellow"></div>
                <div class="stripe-blue"></div>
                <div class="stripe-red"></div>
            </div>
            <!-- Venezuela derecha -->
            <div class="flag-venezuela">
                <div class="stripe-yellow"></div>
                <div class="stripe-blue" style="position:relative;">
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                        <span style="color:#FCD116;font-size:9px;letter-spacing:4px;">&#9733; &#9733; &#9733; &#9733; &#9733; &#9733; &#9733; &#9733;</span>
                    </div>
                </div>
                <div class="stripe-red"></div>
            </div>
        </div>

        <!-- Linea divisora central -->
        <div class="flag-divider"></div>

        <!-- Overlay -->
        <div class="banner-overlay"></div>

        <!-- Contenido del banner -->
        <div class="banner-content">
            <h1 class="banner-title">JJ CONSTRUCCION</h1>
            <p class="banner-subtitle">&#127464;&#127476; Colombia &nbsp;&middot;&nbsp; Venezuela &#127483;&#127466;</p>
        </div>
    </div>

    <!-- ===== EMPRESAS ===== -->
    <p class="section-title">Nuestras Empresas</p>

    <div class="companies-grid">

        <!-- CUADRO 1: CHALET MOTEL 192 -->
        <div class="card card-chalet">
            <div class="card-badge">Cuadro 1</div>
            <div class="card-image-wrap">
                <div style="position:relative;width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1d0b4b,#0b3d57,#11998e);">
                    <div style="text-align:center;">
                        <div class="chalet-icon">&#127976;</div>
                    </div>
                    <span class="chalet-number">192</span>
                </div>
                <div class="card-image-overlay"></div>
            </div>
            <div class="card-body">
                <p class="card-number">Empresa &middot; 01</p>
                <h2 class="card-name">Chalet Motel 192</h2>
                <p class="card-desc">Descanso y confort en un ambiente exclusivo. Tu refugio perfecto para cada ocasion.</p>
            </div>
            <div class="card-footer">
                <span class="card-tag">&#127976; Hospedaje</span>
                <span class="card-tag">&#10024; Exclusivo</span>
            </div>
        </div>

        <!-- CUADRO 2: 20 WINGS -->
        <div class="card card-wings">
            <div class="card-badge">Cuadro 2</div>
            <div class="card-image-wrap">
                <img src="{{ asset('images/20wings_logo.png') }}" alt="20 Wings Logo" loading="lazy">
                <div class="card-image-overlay"></div>
            </div>
            <div class="card-body">
                <p class="card-number">Empresa &middot; 02</p>
                <h2 class="card-name">20 Wings</h2>
                <p class="card-desc">Best Wings In Town &#127829; Las alitas mas sabrosas de la ciudad. Sabor, calidad y actitud.</p>
            </div>
            <div class="card-footer">
                <span class="card-tag">&#127829; Restaurante</span>
                <span class="card-tag">&#128293; Best Wings</span>
            </div>
        </div>

    </div>

    <div class="page-footer">&copy; 2026 JJ CONSTRUCCION &mdash; Todos los derechos reservados</div>

</body>
</html>
