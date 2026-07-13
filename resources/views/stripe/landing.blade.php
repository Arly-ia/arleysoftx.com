<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Guías y Tutoriales - Magic Travel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        neonGreen: '#39FF14',
                        neonBlue: '#00F0FF',
                        darkBg: '#050B14',
                        darkCard: '#0D1B2A',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #050B14;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(0, 240, 255, 0.12) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(57, 255, 20, 0.1) 0%, transparent 40%);
            background-attachment: fixed;
        }
        .glow-button {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .glow-button::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.6s;
        }
        .glow-button:hover::after {
            left: 100%;
        }
        .glow-button:hover {
            box-shadow: 0 0 25px rgba(0, 240, 255, 0.6);
            transform: scale(1.03);
        }
    </style>
</head>
<body class="text-slate-100 font-sans min-h-screen flex flex-col justify-between selection:bg-neonBlue selection:text-black antialiased">

    @if(session('error'))
        <div class="bg-red-500/20 border-b border-red-500/30 backdrop-blur-md sticky top-0 z-50 py-3 px-4 text-center text-red-200 text-sm">
            ❌ {{ session('error') }}
        </div>
    @endif

    <!-- Header Navigation -->
    <header class="w-full py-6 px-6 max-w-7xl mx-auto flex justify-between items-center relative z-10">
        <div class="flex items-center gap-2">
            <span class="text-2xl font-black font-outfit tracking-tighter bg-gradient-to-r from-neonBlue via-white to-neonGreen bg-clip-text text-transparent">
                TUTORIALES MAGIC TRAVEL
            </span>
        </div>
    </header>

    <!-- Main Content (Hero Section) -->
    <main class="max-w-7xl mx-auto px-6 py-8 sm:py-16 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10 flex-grow">
        
        <!-- Left Column: Copy / Sales pitch -->
        <div class="lg:col-span-7 flex flex-col space-y-6">
            <span class="w-fit text-xs font-bold uppercase tracking-widest bg-gradient-to-r from-neonBlue to-neonGreen text-black px-3 py-1 rounded-md shadow-lg font-outfit">
                acceso inmediato
            </span>
            
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black font-outfit leading-tight tracking-tight text-white">
                Aprende a Configurar y <span class="text-neonBlue">Ganar Dinero</span> con tu Cuenta
            </h1>

            <p class="text-lg text-slate-300 leading-relaxed max-w-2xl">
                Mira un video explicativo que te enseña paso a paso cómo iniciar y configurar tu cuenta de trabajo para empezar a recibir pagos de inmediato y de forma muy fácil.
            </p>

            <!-- Key Features Badges -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div class="flex items-center gap-3 bg-darkCard/50 p-3 rounded-xl border border-blue-900/30">
                    <span class="text-2xl">⚡</span>
                    <span class="text-sm font-medium text-slate-200">Consejos fáciles para ganar más</span>
                </div>
                <div class="flex items-center gap-3 bg-darkCard/50 p-3 rounded-xl border border-blue-900/30">
                    <span class="text-2xl">📹</span>
                    <span class="text-sm font-medium text-slate-200">Video explicativo paso a paso</span>
                </div>
                <div class="flex items-center gap-3 bg-darkCard/50 p-3 rounded-xl border border-blue-900/30">
                    <span class="text-2xl">📄</span>
                    <span class="text-sm font-medium text-slate-200">Guía fácil en PDF para tu celular</span>
                </div>
                <div class="flex items-center gap-3 bg-darkCard/50 p-3 rounded-xl border border-blue-900/30">
                    <span class="text-2xl">📊</span>
                    <span class="text-sm font-medium text-slate-200">Lista de ayuda y control sencilla</span>
                </div>
            </div>

            <!-- Price & CTA -->
            <div class="bg-gradient-to-r from-neonBlue/10 via-darkCard to-neonGreen/10 p-6 rounded-2xl border border-blue-900/40 mt-4 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 line-through text-sm">$99 USD</span>
                        <span class="text-xs bg-neonBlue/20 text-neonBlue px-2 py-0.5 rounded font-bold">72% DESCUENTO</span>
                    </div>
                    <div class="text-3xl sm:text-4xl font-black font-outfit text-white flex items-baseline gap-1 mt-1">
                        $27 <span class="text-sm text-slate-300 font-normal">USD</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Pago único. Acceso de por vida.</p>
                </div>

                <div class="w-full sm:w-auto">
                    <form action="{{ route('tutorial.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="glow-button w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-neonBlue to-blue-700 rounded-xl text-black font-black text-lg flex items-center justify-center gap-2 shadow-lg">
                            Ver Video y Guías
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Visual Mockup / Video Player Preview -->
        <div class="lg:col-span-5 flex justify-center">
            <div class="w-full max-w-md bg-darkCard/80 p-5 rounded-3xl border border-blue-900/60 shadow-2xl relative">
                <!-- Glowing effect in background -->
                <div class="absolute -inset-1 bg-gradient-to-r from-neonBlue to-neonGreen rounded-3xl blur opacity-25"></div>
                
                <div class="relative bg-[#020712] rounded-2xl overflow-hidden shadow-inner">
                    <!-- Image Cover style with Text Overlay -->
                    <div class="w-full bg-slate-900 border-b border-blue-900/40 overflow-hidden relative group">
                        <img src="{{ asset('images/magic_travel_banner.png') }}?v={{ time() }}" alt="Tutorial Preview" class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent flex flex-col justify-end p-5">
                            <span class="text-xs font-bold text-neonBlue uppercase tracking-widest font-outfit">Guía de Ayuda</span>
                            <h4 class="font-outfit font-black text-white text-lg sm:text-xl mt-0.5 leading-tight uppercase tracking-tight">
                                Gana Dinero desde Casa
                            </h4>
                        </div>
                    </div>

                    <!-- Info text below video mockup -->
                    <div class="p-5 space-y-4">
                        <h3 class="font-outfit font-bold text-white text-lg leading-tight">
                            ¿Qué incluye tu acceso?
                        </h3>
                        <ul class="space-y-2.5 text-sm text-slate-300">
                            <li class="flex items-start gap-2.5">
                                <span class="text-neonBlue">✓</span>
                                <span>Video instructivo: Cómo abrir la cuenta de aplicación TASK</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-neonBlue">✓</span>
                                <span>Guía fácil en PDF para tu celular</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-neonBlue">✓</span>
                                <span>Lista de ayuda y control sencilla</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-neonBlue">✓</span>
                                <span>Entra desde tu celular cuando quieras</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-8 border-t border-blue-950/40 bg-[#02050a] text-center text-xs text-slate-500 relative z-10">
        <div class="max-w-6xl mx-auto px-6 space-y-2">
            <p>© 2026 Tutoriales Magic Travel. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>
