<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Tutorial TASK | ArleySoftx</title>
    <meta name="description" content="Ingresa tu clave de acceso personal para ver los tutoriales y guía completa de TASK.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkBg:   '#080c14',
                        darkCard: '#0f172a',
                        neonBlue: '#00f0ff',
                        neonGreen:'#10b981',
                    },
                    fontFamily: { outfit: ['Outfit', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { background-color: #080c14; }
        .glow-border {
            box-shadow: 0 0 0 1px rgba(0,240,255,0.15), 0 0 40px rgba(0,240,255,0.05);
        }
        .key-input {
            font-family: 'Courier New', monospace;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }
        .key-input::placeholder {
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(0,240,255,0.1); }
            50%       { box-shadow: 0 0 40px rgba(0,240,255,0.25); }
        }
        .animate-glow { animation: pulse-glow 3s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-8px); }
        }
        .animate-float { animation: float 4s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen font-outfit flex flex-col items-center justify-center px-4 py-12 relative overflow-hidden">

    <!-- Background particles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-neonBlue/3 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-neonGreen/3 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-purple-500/3 rounded-full blur-3xl"></div>
    </div>

    <!-- Main Card -->
    <div class="relative z-10 w-full max-w-md">

        <!-- Logo / Brand -->
        <div class="text-center mb-8 animate-float">
            <div class="inline-flex items-center gap-2 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-neonBlue/20 to-neonGreen/20 border border-neonBlue/20 flex items-center justify-center">
                    <span class="text-xl">🔐</span>
                </div>
                <span class="font-outfit font-black text-xl text-white tracking-wider">ArleySoftx</span>
            </div>
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Guía y Tutoriales TASK</p>
        </div>

        <!-- Card -->
        <div class="bg-darkCard/80 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-8 glow-border">

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="font-outfit font-black text-2xl text-white mb-2">
                    Ingresa tu Clave
                </h1>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Escribe la clave personal que recibiste por email al realizar tu compra.
                </p>
            </div>

            <!-- Error Message -->
            @if($error)
                <div class="mb-6 bg-red-500/10 border border-red-500/30 rounded-2xl p-4">
                    <p class="text-red-400 text-sm font-medium leading-relaxed">{{ $error }}</p>
                </div>
            @endif

            <!-- Success Message -->
            @if($success)
                <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl p-4">
                    <p class="text-emerald-400 text-sm font-medium">{{ $success }}</p>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('task.access.verify') }}" id="access-form">
                @csrf

                <!-- Hidden device fingerprint field -->
                <input type="hidden" name="device_fingerprint" id="device_fingerprint">

                <!-- Key Input -->
                <div class="space-y-2 mb-6">
                    <label for="license_key" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Clave de Acceso
                    </label>
                    <input
                        type="text"
                        id="license_key"
                        name="license_key"
                        placeholder="TASK-XXXX-XXXX-XXXX"
                        maxlength="24"
                        class="key-input w-full bg-slate-950/80 border border-slate-700/60 rounded-2xl px-5 py-4 text-center text-white text-lg font-bold focus:border-neonBlue focus:ring-2 focus:ring-neonBlue/20 focus:outline-none transition duration-200 placeholder-slate-600 animate-glow"
                        autocomplete="off"
                        spellcheck="false"
                    >
                    <p class="text-[10px] text-slate-500 text-center">
                        Formato: TASK-XXXX-XXXX-XXXX (puedes incluir o no los guiones)
                    </p>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    id="submit-btn"
                    class="w-full py-4 rounded-2xl bg-gradient-to-r from-neonBlue to-neonGreen text-darkBg font-black text-base uppercase tracking-wider transition duration-300 hover:from-cyan-400 hover:to-emerald-400 active:scale-[0.98] shadow-[0_0_30px_rgba(0,240,255,0.15)]"
                >
                    🔓 Acceder al Tutorial
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 border-t border-slate-800"></div>
                <span class="text-[10px] text-slate-600 uppercase tracking-widest">¿No tienes clave?</span>
                <div class="flex-1 border-t border-slate-800"></div>
            </div>

            <!-- Buy CTA -->
            <a
                href="{{ url('/tutoriales-task') }}"
                class="block text-center w-full py-3.5 rounded-2xl border border-neonGreen/30 bg-neonGreen/5 text-neonGreen font-bold text-sm uppercase tracking-wider hover:bg-neonGreen/10 transition duration-200"
            >
                🛒 Comprar Acceso — $27 USD
            </a>

        </div>

        <!-- Privacy note -->
        <p class="text-center text-[11px] text-slate-600 mt-6 leading-relaxed px-4">
            🛡️ Tu clave es personal. El sistema registra el dispositivo al ingresar
            para proteger tu compra. Se permiten hasta 2 dispositivos por clave.
        </p>

    </div>

    <script>
        /**
         * Genera una huella del dispositivo basada en atributos del navegador.
         * Combina datos estables que identifican al dispositivo sin ser invasivos.
         */
        async function generateDeviceFingerprint() {
            const components = [
                navigator.userAgent,
                navigator.language || navigator.userLanguage || '',
                screen.width + 'x' + screen.height,
                screen.colorDepth,
                new Date().getTimezoneOffset(),
                navigator.hardwareConcurrency || '',
                navigator.platform || '',
            ];

            const raw = components.join('|');

            // Hashear con SHA-256 usando la Web Crypto API
            try {
                const encoder = new TextEncoder();
                const data = encoder.encode(raw);
                const hashBuffer = await crypto.subtle.digest('SHA-256', data);
                const hashArray = Array.from(new Uint8Array(hashBuffer));
                const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
                return hashHex;
            } catch (e) {
                // Fallback: simple hash
                let hash = 0;
                for (let i = 0; i < raw.length; i++) {
                    hash = ((hash << 5) - hash) + raw.charCodeAt(i);
                    hash |= 0;
                }
                return Math.abs(hash).toString(16);
            }
        }

        // Formatear la clave automáticamente mientras escribe
        document.getElementById('license_key').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^A-Z0-9a-z]/g, '').toUpperCase();

            // Si no empieza con TASK, intentar auto-completar el prefijo
            if (value.length > 4 && !value.startsWith('TASK')) {
                // Insertar formato TASK-XXXX-XXXX-XXXX
            }

            // Formatear con guiones automáticamente
            if (value.startsWith('TASK')) {
                let rest = value.slice(4);
                let parts = [];
                for (let i = 0; i < rest.length && parts.join('').length < 12; i += 4) {
                    parts.push(rest.slice(i, i + 4));
                }
                value = 'TASK-' + parts.join('-');
            }

            e.target.value = value;
        });

        // Al enviar el formulario, primero calcular el fingerprint
        document.getElementById('access-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('submit-btn');
            btn.textContent = '⏳ Verificando...';
            btn.disabled = true;

            try {
                const fp = await generateDeviceFingerprint();
                document.getElementById('device_fingerprint').value = fp;
            } catch (err) {
                console.error('Fingerprint error:', err);
            }

            this.submit();
        });
    </script>
</body>
</html>
