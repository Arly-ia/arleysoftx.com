<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Clave de Acceso - TASK Tutorial</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0f172a;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #e2e8f0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 24px;
            padding: 40px;
        }
        .logo-area {
            text-align: center;
            margin-bottom: 32px;
        }
        .logo-text {
            font-size: 22px;
            font-weight: 900;
            color: #00f0ff;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .logo-sub {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
        .divider {
            border: none;
            border-top: 1px solid #334155;
            margin: 24px 0;
        }
        h1 {
            font-size: 24px;
            font-weight: 900;
            color: #f8fafc;
            margin: 0 0 12px;
        }
        p {
            font-size: 15px;
            color: #94a3b8;
            line-height: 1.7;
            margin: 0 0 16px;
        }
        .key-box {
            background: #0f172a;
            border: 2px solid #00f0ff;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            margin: 28px 0;
            box-shadow: 0 0 30px rgba(0, 240, 255, 0.1);
        }
        .key-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .key-value {
            font-size: 32px;
            font-weight: 900;
            color: #00f0ff;
            letter-spacing: 4px;
            font-family: 'Courier New', monospace;
        }
        .btn {
            display: block;
            text-align: center;
            background: linear-gradient(135deg, #00f0ff, #10b981);
            color: #0f172a;
            font-weight: 900;
            font-size: 16px;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 14px;
            margin: 24px 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .steps {
            background: #0f172a;
            border-radius: 14px;
            padding: 20px 24px;
            margin: 20px 0;
        }
        .steps ol {
            margin: 0;
            padding-left: 20px;
        }
        .steps li {
            font-size: 14px;
            color: #94a3b8;
            margin-bottom: 8px;
            line-height: 1.5;
        }
        .warning {
            background: #7f1d1d20;
            border: 1px solid #ef4444;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 20px 0;
        }
        .warning p {
            color: #fca5a5;
            font-size: 13px;
            margin: 0;
        }
        .footer {
            text-align: center;
            margin-top: 32px;
        }
        .footer p {
            font-size: 12px;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <!-- Logo -->
            <div class="logo-area">
                <div class="logo-text">ArleySoftx</div>
                <div class="logo-sub">Guía y Tutoriales TASK</div>
            </div>

            <hr class="divider">

            <h1>🎉 ¡Tu acceso está listo!</h1>
            <p>
                Gracias por tu compra. A continuación encontrarás tu <strong>clave de acceso personal</strong>
                para ingresar a todos los tutoriales y la guía completa de TASK.
            </p>

            <!-- Key Box -->
            <div class="key-box">
                <div class="key-label">Tu Clave de Acceso Personal</div>
                <div class="key-value">{{ $licenseKey }}</div>
            </div>

            <!-- CTA Button -->
            <a href="{{ url('/guia-y-tutoriales-task/acceso') }}" class="btn">
                → Ingresar al Tutorial Ahora
            </a>

            <!-- Steps -->
            <div class="steps">
                <p style="font-size:13px; font-weight:700; color:#e2e8f0; margin:0 0 12px;">¿Cómo ingresar?</p>
                <ol>
                    <li>Haz clic en el botón de arriba o ve a <strong style="color:#00f0ff;">arleysoftx.com/guia-y-tutoriales-task/acceso</strong></li>
                    <li>Escribe tu clave de acceso: <strong style="color:#00f0ff;">{{ $licenseKey }}</strong></li>
                    <li>¡Listo! Tendrás acceso completo al contenido.</li>
                </ol>
            </div>

            <!-- Warning -->
            <div class="warning">
                <p>
                    ⚠️ <strong>Importante:</strong> Esta clave es personal e intransferible.
                    Solo funciona en hasta 2 dispositivos. Si compartes tu clave y se usa en otro dispositivo,
                    tu acceso podría bloquearse automáticamente.
                </p>
            </div>

            <hr class="divider">

            <p style="font-size:13px;">
                ¿Tienes algún problema? Contáctanos por WhatsApp o redes sociales y te ayudamos.
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} ArleySoftx. Todos los derechos reservados.</p>
            <p>Este email fue generado automáticamente al confirmar tu pago.</p>
        </div>
    </div>
</body>
</html>
