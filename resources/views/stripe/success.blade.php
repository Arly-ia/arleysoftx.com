<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>¡Pago Exitoso! Tu Clave de Acceso - TASK Tutorial</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { outfit: ['Outfit', 'sans-serif'] },
                    colors: {
                        neonGreen: '#10b981',
                        neonBlue:  '#00f0ff',
                        darkBg:    '#080c14',
                        darkCard:  '#0f172a',
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #080c14; }
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }
        @keyframes key-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(0,240,255,0.15), 0 0 0 1px rgba(0,240,255,0.2); }
            50%       { box-shadow: 0 0 50px rgba(0,240,255,0.35), 0 0 0 1px rgba(0,240,255,0.4); }
        }
        .bounce-slow { animation: bounce-slow 3s ease-in-out infinite; }
        .key-glow    { animation: key-glow 2.5s ease-in-out infinite; }
        .key-font    { font-family: 'Courier New', monospace; letter-spacing: 0.25em; }
    </style>
</head>
<body class="min-h-screen font-outfit text-slate-100 antialiased">

    <!-- Header -->
    <header class="relative z-10 border-b border-slate-900/60 bg-darkBg/90 py-5">
        <div class="max-w-6xl mx-auto px-6 flex items-center justify-between">
            <span class="font-outfit font-black text-lg text-white tracking-wider">ArleySoftx</span>
            <span class="text-[11px] font-bold bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-3 py-1.5 rounded-full flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Pago Verificado
            </span>
        </div>
    </header>

    <!-- Main -->
    <main class="max-w-2xl mx-auto px-6 py-16 space-y-10">

        <!-- Success Icon + Title -->
        <div class="text-center space-y-4 bounce-slow">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-neonBlue/20 to-neonGreen/20 border border-neonBlue/30 text-4xl">
                🎉
            </div>
            <h1 class="font-outfit font-black text-3xl sm:text-4xl text-white leading-tight">
                ¡Gracias por tu compra!
            </h1>
            <p class="text-slate-400 text-base leading-relaxed max-w-md mx-auto">
                Tu pago fue procesado exitosamente.
                @if(isset($buyerEmail) && $buyerEmail)
                    Tu clave también fue enviada a <strong class="text-white">{{ $buyerEmail }}</strong>.
                @endif
            </p>
        </div>

        <!-- Key Box -->
        <div class="bg-darkCard/80 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-8 space-y-6">

            <div class="text-center space-y-1">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Tu Clave de Acceso Personal</p>
                <p class="text-[11px] text-slate-600">Guárdala bien — la necesitarás cada vez que entres</p>
            </div>

            @if(isset($licenseKey) && $licenseKey)
                <!-- Key Display -->
                <div class="key-glow bg-slate-950 rounded-2xl p-6 text-center border border-neonBlue/20">
                    <div id="key-display" class="key-font text-3xl sm:text-4xl font-black text-neonBlue tracking-widest select-all">
                        {{ $licenseKey }}
                    </div>
                </div>

                <!-- Copy Button -->
                <button
                    onclick="copyKey('{{ $licenseKey }}')"
                    id="copy-btn"
                    class="w-full py-3.5 rounded-2xl border border-neonBlue/30 bg-neonBlue/5 text-neonBlue font-bold text-sm uppercase tracking-wider hover:bg-neonBlue/10 transition duration-200 flex items-center justify-center gap-2"
                >
                    📋 Copiar Clave al Portapapeles
                </button>
            @else
                <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-4 text-center">
                    <p class="text-red-400 text-sm">Hubo un error generando tu clave. Contacta al soporte con tu comprobante de pago.</p>
                </div>
            @endif

            <!-- Divider -->
            <div class="flex items-center gap-3">
                <div class="flex-1 border-t border-slate-800"></div>
                <span class="text-[10px] text-slate-600 uppercase tracking-widest">próximo paso</span>
                <div class="flex-1 border-t border-slate-800"></div>
            </div>

            <!-- Access CTA -->
            <a
                href="{{ url('/guia-y-tutoriales-task/acceso') }}"
                class="block text-center w-full py-4 rounded-2xl bg-gradient-to-r from-neonBlue to-neonGreen text-darkBg font-black text-base uppercase tracking-wider hover:from-cyan-400 hover:to-emerald-400 transition duration-300 shadow-[0_0_30px_rgba(0,240,255,0.15)] active:scale-[0.98]"
            >
                🔓 Ingresar al Tutorial con mi Clave
            </a>

            <!-- Instructions -->
            <div class="bg-slate-900/60 rounded-2xl p-4 space-y-3">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">¿Cómo acceder?</p>
                <div class="flex items-start gap-3">
                    <span class="text-neonBlue font-black text-sm min-w-[16px]">1.</span>
                    <p class="text-slate-400 text-sm leading-relaxed">Haz clic en "Ingresar al Tutorial" o ve a <strong class="text-slate-300">arleysoftx.com/guia-y-tutoriales-task/acceso</strong></p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-neonBlue font-black text-sm min-w-[16px]">2.</span>
                    <p class="text-slate-400 text-sm leading-relaxed">Escribe o pega tu clave de acceso personal</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-neonBlue font-black text-sm min-w-[16px]">3.</span>
                    <p class="text-slate-400 text-sm leading-relaxed">¡Listo! Tendrás acceso completo. La clave funciona en hasta <strong class="text-slate-300">2 dispositivos</strong>.</p>
                </div>
            </div>

        </div>

        <!-- Warning -->
        <div class="bg-amber-500/5 border border-amber-500/20 rounded-2xl p-5">
            <p class="text-amber-400/80 text-sm leading-relaxed">
                ⚠️ <strong>Importante:</strong> Esta clave es personal e intransferible. Si la compartes y alguien más la usa en otro dispositivo, tu acceso podría bloquearse automáticamente. Si necesitas resetear tu dispositivo, contáctanos.
            </p>
        </div>

        <!-- Back link -->
        <div class="text-center">
            <a href="{{ url('/') }}" class="text-xs text-slate-600 hover:text-slate-400 transition">
                ← Volver al inicio
            </a>
        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900/60 py-6 text-center text-xs text-slate-600">
        <p>&copy; {{ date('Y') }} ArleySoftx. Todos los derechos reservados.</p>
    </footer>

    <script>
        function copyKey(key) {
            navigator.clipboard.writeText(key).then(() => {
                const btn = document.getElementById('copy-btn');
                btn.textContent = '✅ ¡Clave Copiada!';
                setTimeout(() => { btn.textContent = '📋 Copiar Clave al Portapapeles'; }, 3000);
            }).catch(() => {
                const el = document.createElement('textarea');
                el.value = key;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                document.getElementById('copy-btn').textContent = '✅ ¡Clave Copiada!';
            });
        }
    </script>

</body>
</html>
