<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pago Cancelado - Fórmula POV</title>

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
                radial-gradient(circle at 50% 20%, rgba(0, 240, 255, 0.1) 0%, transparent 40%);
            background-attachment: fixed;
        }
    </style>
</head>
<body class="text-slate-100 font-sans min-h-screen flex flex-col justify-between selection:bg-neonBlue selection:text-black antialiased">

    <!-- Header Navigation -->
    <header class="w-full py-6 px-6 max-w-7xl mx-auto flex justify-between items-center relative z-10">
        <div class="flex items-center gap-2">
            <a href="{{ url('/') }}" class="text-2xl font-black font-outfit tracking-tighter bg-gradient-to-r from-neonBlue via-white to-neonGreen bg-clip-text text-transparent hover:opacity-90">
                GUIA Y TUTORIALES TASK
            </a>
        </div>
        <a href="{{ url('/') }}" class="text-xs font-bold text-slate-400 hover:text-white transition duration-200 flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
            Volver al Inicio
        </a>
    </header>

    <!-- Cancel Content -->
    <main class="max-w-md mx-auto px-6 py-12 sm:py-24 text-center space-y-8 relative z-10 flex-grow flex flex-col justify-center">
        
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-4xl text-yellow-400 mx-auto">
            ⚡
        </div>

        <div class="space-y-3">
            <h1 class="text-3xl font-black font-outfit text-white">Pago Cancelado</h1>
            <p class="text-slate-300 text-sm leading-relaxed">
                El proceso de pago de Stripe fue interrumpido y no se realizó ningún cargo en tu tarjeta.
            </p>
        </div>

        <div class="bg-darkCard/50 p-5 rounded-2xl border border-blue-900/20 text-xs text-slate-400 leading-relaxed text-left">
            📌 <strong>¿Sabías qué?</strong> El precio promocional de <strong>$27 USD</strong> es temporal. Si deseas acceder al video instructivo y obtener las listas de ayuda más tarde, puedes regresar cuando gustes.
        </div>

        <div class="flex flex-col gap-3 pt-2">
            <a href="{{ route('tutorial.landing') }}" class="px-6 py-3.5 bg-gradient-to-r from-neonBlue to-blue-700 rounded-xl text-black font-black text-sm shadow-lg hover:shadow-neonBlue/20 transition duration-300">
                Regresar a la Página de Ventas
            </a>
        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full py-8 border-t border-blue-950/40 bg-[#02050a] text-center text-xs text-slate-500 relative z-10">
        <div class="max-w-6xl mx-auto px-6">
            <p>© 2026 Guías y Tutoriales Task. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>
