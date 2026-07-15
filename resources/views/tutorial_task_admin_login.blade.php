<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrador: Tareas TASK | ArleySoftx</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkBg: '#030712',
                        darkCard: '#0f172a',
                        neonBlue: '#00F0FF',
                        neonGreen: '#39FF14',
                        neonRed: '#FF3131',
                    },
                    fontFamily: {
                        outfit: ['Outfit', 'sans-serif'],
                        jakarta: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        .neon-glow {
            box-shadow: 0 0 30px rgba(0, 240, 255, 0.2);
        }
        .glass-panel {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-darkBg text-slate-100 font-jakarta min-h-screen flex flex-col justify-between overflow-x-hidden selection:bg-neonBlue selection:text-darkBg">
    
    <!-- Background Decoration Grid -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f293710_1px,transparent_1px),linear-gradient(to_bottom,#1f293710_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] pointer-events-none"></div>

    <!-- Centered Login Box -->
    <main class="flex-grow flex items-center justify-center px-6 relative z-10 py-12">
        <div class="w-full max-w-md glass-panel border border-slate-800/80 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden transition-all duration-300 hover:border-neonBlue/30 hover:shadow-[0_0_40px_rgba(0,240,255,0.1)]">
            
            <!-- Glow Accent -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-neonBlue/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="text-center space-y-3 mb-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-neonBlue/30 bg-neonBlue/5 mb-2">
                    <span class="w-2 h-2 rounded-full bg-neonBlue animate-pulse"></span>
                    <span class="text-xs font-semibold text-neonBlue uppercase tracking-widest font-outfit">Panel Admin</span>
                </div>
                <h1 class="font-outfit font-black text-2xl sm:text-3xl text-white tracking-tight leading-none">
                    Iniciar Sesión
                </h1>
                <p class="text-slate-400 text-xs sm:text-sm">
                    Ingresa la contraseña maestra de administrador para acceder y gestionar las labores.
                </p>
            </div>

            <!-- Messages/Alerts -->
            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-neonRed/10 border border-neonRed/30 text-red-400 text-xs sm:text-sm flex items-center justify-between shadow-[0_0_15px_rgba(255,49,49,0.1)]">
                    <div class="flex items-center gap-2">
                        <span>❌</span>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs sm:text-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span>✅</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <form action="{{ route('tutorial.task.admin.login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-widest font-outfit">
                        Contraseña de Acceso
                    </label>
                    <div class="relative rounded-2xl overflow-hidden shadow-inner">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required
                            placeholder="••••••••••••••"
                            class="w-full px-5 py-4 bg-slate-950/80 border border-slate-800/80 text-white rounded-2xl placeholder-slate-600 focus:outline-none focus:border-neonBlue focus:ring-1 focus:ring-neonBlue/30 text-sm tracking-widest transition duration-200"
                        >
                    </div>
                </div>

                <button 
                    type="submit" 
                    class="w-full py-4 bg-gradient-to-r from-neonBlue to-emerald-500 hover:from-cyan-400 hover:to-emerald-400 text-darkBg font-extrabold text-sm uppercase tracking-wider rounded-2xl transition duration-300 shadow-[0_0_20px_rgba(0,240,255,0.15)] hover:shadow-[0_0_25px_rgba(0,240,255,0.35)] active:scale-[0.98]"
                >
                    Entrar al Panel
                </button>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ route('tutorial.landing') }}" class="text-xs text-slate-500 hover:text-slate-350 transition duration-200 flex items-center justify-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    Volver a la Página de Ventas
                </a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-900/60 bg-darkBg/90 py-8">
        <div class="max-w-6xl mx-auto px-6 text-center text-xs text-slate-500 font-light flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; {{ date('Y') }} ArleySoftx. Todos los derechos reservados.</p>
            <p>Panel de Administración y Control.</p>
        </div>
    </footer>

</body>
</html>
