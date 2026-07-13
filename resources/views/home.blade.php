<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArleySoftx - Aprendizaje y Recursos Digitales</title>
    
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
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.2);
        }
        .neon-glow-green {
            box-shadow: 0 0 15px rgba(57, 255, 20, 0.2);
        }
    </style>
</head>
<body class="bg-darkBg text-slate-100 font-jakarta min-h-screen flex flex-col justify-between overflow-x-hidden selection:bg-neonBlue selection:text-darkBg">
    
    <!-- Background grid decoration -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f293710_1px,transparent_1px),linear-gradient(to_bottom,#1f293710_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] pointer-events-none"></div>

    <!-- Header -->
    <header class="relative z-10 border-b border-slate-800/40 bg-darkBg/80 backdrop-blur-md">
        <div class="max-w-6xl mx-auto px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="font-outfit font-black text-2xl tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-neonBlue to-neonGreen">
                    ARLEYSOFTX
                </span>
            </div>
            <div>
                <a href="{{ url('/tutoriales-task') }}" class="relative inline-flex items-center justify-center p-0.5 overflow-hidden text-xs sm:text-sm font-bold text-white rounded-full group bg-gradient-to-br from-neonBlue to-neonGreen hover:text-darkBg transition-all duration-300">
                    <span class="relative px-4 sm:px-6 py-2 transition-all ease-in duration-75 bg-darkBg rounded-full group-hover:bg-opacity-0">
                        Acceder a Tutoriales
                    </span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 max-w-6xl mx-auto px-6 py-16 md:py-24 text-center my-auto">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-neonBlue/30 bg-neonBlue/5 mb-8">
            <span class="w-2 h-2 rounded-full bg-neonBlue"></span>
            <span class="text-xs font-semibold text-neonBlue uppercase tracking-widest font-outfit">Recursos & Reportes</span>
        </div>

        <!-- Title -->
        <h1 class="font-outfit font-black text-4xl sm:text-5xl md:text-6xl tracking-tight text-white mb-6 leading-none">
            Portal de Recursos <br class="hidden sm:inline">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-neonBlue via-cyan-400 to-neonGreen">
                y Capacitación
            </span>
        </h1>

        <!-- Subtitle -->
        <p class="text-slate-400 text-base sm:text-lg max-w-2xl mx-auto mb-12 font-light leading-relaxed">
            Explora nuestros reportes técnicos y guías de aprendizaje. Información y herramientas de alta calidad para potenciar tus habilidades.
        </p>

        <!-- Future & AI Banner -->
        <div class="relative w-full max-w-4xl mx-auto rounded-3xl overflow-hidden border border-slate-800/80 neon-glow h-52 sm:h-72 mb-12">
            <img src="{{ asset('images/generative_ai_banner.png') }}" alt="Futuro e Inteligencia Artificial" class="w-full h-full object-cover object-center filter brightness-95">
            <div class="absolute inset-0 bg-gradient-to-t from-darkBg via-darkBg/10 to-transparent"></div>
        </div>

        <!-- Cards Grid -->
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
            <!-- Tutorial Card Link -->
            <div class="relative group flex flex-col h-full">
                <div class="absolute -inset-1.5 bg-gradient-to-r from-neonBlue to-neonGreen rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                
                <div class="relative bg-slate-900 border border-slate-800/80 rounded-2xl p-6 sm:p-8 flex flex-col justify-between text-left gap-6 flex-grow h-full">
                    <div class="space-y-3">
                        <span class="text-xs font-bold text-neonGreen uppercase tracking-wider font-outfit">Disponible Ahora</span>
                        <h3 class="font-outfit font-black text-2xl text-white">Guías & Tutoriales TASK</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Aprende a generar ingresos desde tu casa: guías paso a paso de optimización y videotutoriales prácticos para maximizar tus ganancias.
                        </p>
                    </div>
                    <div class="pt-2">
                        <a href="{{ url('/tutoriales-task') }}" class="w-full inline-flex items-center justify-center px-6 py-3.5 rounded-xl bg-gradient-to-r from-neonBlue to-neonGreen text-darkBg font-outfit font-black tracking-wide shadow-lg hover:scale-105 active:scale-95 transition-all duration-300">
                            Ver Guía de Ayuda
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 ml-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Generative AI Report Card -->
            <div class="relative group flex flex-col h-full">
                <div class="absolute -inset-1.5 bg-gradient-to-r from-neonBlue to-neonGreen rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                
                <div class="relative bg-slate-900 border border-slate-800/80 rounded-2xl p-6 sm:p-8 flex flex-col justify-between text-left gap-6 flex-grow h-full">
                    <div class="space-y-3">
                        <span class="text-xs font-bold text-neonBlue uppercase tracking-wider font-outfit">Reporte Exclusivo</span>
                        <h3 class="font-outfit font-black text-2xl text-white">Últimas IA Generativas</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Análisis detallado de los últimos modelos de inteligencia artificial y su impacto en la productividad digital.
                        </p>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('ia.report') }}" class="w-full inline-flex items-center justify-center px-6 py-3.5 rounded-xl bg-gradient-to-r from-neonBlue to-neonGreen text-darkBg font-outfit font-black tracking-wide shadow-lg hover:scale-105 active:scale-95 transition-all duration-300">
                            Leer Reporte
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 ml-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-900/60 bg-darkBg/90 py-8">
        <div class="max-w-6xl mx-auto px-6 text-center text-xs text-slate-500 font-light flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; {{ date('Y') }} ArleySoftx. Todos los derechos reservados.</p>
            <p>Soporte y Recursos Digitales de Alta Calidad.</p>
        </div>
    </footer>

</body>
</html>
