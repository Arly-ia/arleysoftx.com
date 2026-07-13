<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>¡Acceso Concedido! - Tutoriales Magic Travel</title>

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
                radial-gradient(circle at 50% -20%, rgba(0, 240, 255, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 50% 120%, rgba(57, 255, 20, 0.1) 0%, transparent 50%);
            background-attachment: fixed;
        }
        .bounce-glow {
            animation: bounce 2.5s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
    </style>
</head>
<body class="text-slate-100 font-sans min-h-screen flex flex-col justify-between selection:bg-neonBlue selection:text-black antialiased">

    <!-- Header Navigation -->
    <header class="w-full py-6 px-6 max-w-7xl mx-auto flex justify-between items-center relative z-10 border-b border-blue-950/40">
        <div class="flex items-center gap-2">
            <span class="text-2xl font-black font-outfit tracking-tighter bg-gradient-to-r from-neonBlue via-white to-neonGreen bg-clip-text text-transparent">
                TUTORIALES MAGIC TRAVEL
            </span>
        </div>
        <div>
            <span class="text-xs bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-3 py-1.5 rounded-full font-semibold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Acceso Verificado
            </span>
        </div>
    </header>

    <!-- Success Content -->
    <main class="max-w-4xl mx-auto px-6 py-12 relative z-10 flex-grow w-full space-y-12">
        
        <!-- Header Success Message -->
        <div class="text-center space-y-4">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-tr from-neonBlue to-neonGreen p-1 bounce-glow">
                <div class="w-full h-full bg-[#050B14] rounded-full flex items-center justify-center text-3xl">
                    🌟
                </div>
            </div>
            <h1 class="text-3xl sm:text-5xl font-black font-outfit text-white leading-tight">
                ¡Acceso Concedido a tus Videos y Guías!
            </h1>
            <p class="text-slate-300 max-w-xl mx-auto text-base">
                Ya tienes acceso a tu material. Abajo puedes ver el video explicativo y descargar tus guías de ayuda en tu celular de forma sencilla.
            </p>
        </div>

        <!-- Video Player Section -->
        <div class="bg-darkCard/80 rounded-3xl border border-blue-900/40 p-4 sm:p-6 shadow-2xl relative">
            <div class="absolute top-4 right-6 bg-neonBlue/15 text-neonBlue text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                Exclusivo Alumnos
            </div>
            
            <h2 class="font-outfit font-bold text-white text-xl sm:text-2xl mb-4 pr-24 flex items-center gap-2">
                🎬 Video Instructivo: Cómo Abrir la Cuenta de Aplicación TASK
            </h2>

            <!-- Video Player Container (Insert unlisted tutorial URL here) -->
            <div class="aspect-video w-full rounded-2xl overflow-hidden bg-black/90 border border-blue-950 shadow-inner relative">
                
                <!-- IFRAME PLACEHOLDER: Enlace del video de YouTube, Vimeo, Drive, etc. -->
                <iframe 
                    class="w-full h-full" 
                    src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ" 
                    title="Magic Travel Video Tutorial" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen>
                </iframe>
                
            </div>

            <!-- Video instructions -->
            <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-xs text-slate-400 pt-4 border-t border-blue-900/20">
                <p>💡 <em>Consejo: Si te pierdes, ponle pausa al video y míralo con calma.</em></p>
                <div class="flex items-center gap-3">
                    <span>1080p FHD</span>
                </div>
            </div>
        </div>

        <!-- Resources & Downloadables -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Downloadable Resource 1 -->
            <div class="bg-darkCard/40 p-6 rounded-2xl border border-blue-900/20 hover:border-neonBlue/50 transition duration-300 flex items-start gap-4">
                <div class="text-3xl p-3 bg-neonBlue/10 rounded-xl text-neonBlue">
                    📄
                </div>
                <div class="space-y-1">
                    <h3 class="font-outfit font-bold text-white text-base">Guía de Ayuda Sencilla (PDF)</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Archivo fácil de leer que te explica paso a paso cómo configurar todo desde cero en tu celular.
                    </p>
                    <a href="#" class="inline-flex items-center gap-1 text-xs text-neonBlue font-bold hover:underline pt-2">
                        Descargar Guía PDF para celular
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Downloadable Resource 2 -->
            <div class="bg-darkCard/40 p-6 rounded-2xl border border-blue-900/20 hover:border-neonGreen/50 transition duration-300 flex items-start gap-4">
                <div class="text-3xl p-3 bg-neonGreen/10 rounded-xl text-neonGreen">
                    📊
                </div>
                <div class="space-y-1">
                    <h3 class="font-outfit font-bold text-white text-base">Lista de Ayuda y Control</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Una lista simple en Excel para ver lo que debes hacer y controlar tus ganancias diarias.
                    </p>
                    <a href="#" class="inline-flex items-center gap-1 text-xs text-neonGreen font-bold hover:underline pt-2">
                        Descargar Lista en Excel
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </a>
                </div>
            </div>
            
        </div>

        <!-- Order Summary Details -->
        @if(isset($paymentDetails))
            <div class="bg-darkCard/20 p-5 rounded-2xl border border-blue-900/10 text-xs text-slate-400 space-y-2">
                <h4 class="font-bold text-slate-300 uppercase tracking-wider text-[10px]">Resumen de tu Compra</h4>
                <div class="grid grid-cols-2 gap-1.5">
                    <div>Código de Pago:</div>
                    <div class="text-slate-300 text-right select-all font-mono">{{ $paymentDetails['id'] }}</div>
                    
                    <div>Monto Cobrado:</div>
                    <div class="text-slate-300 text-right">${{ number_format($paymentDetails['amount_total'] / 100, 2) }} {{ strtoupper($paymentDetails['currency']) }}</div>
                    
                    @if(isset($paymentDetails['customer_details']['email']))
                        <div>Tu Correo:</div>
                        <div class="text-slate-300 text-right">{{ $paymentDetails['customer_details']['email'] }}</div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Customer Support section -->
        <div class="text-center py-6 border-t border-blue-900/10 text-xs text-slate-400 space-y-2">
            <p>¿Tienes alguna duda o necesitas ayuda para abrir tus archivos? Escríbenos directamente a:</p>
            <p class="font-bold text-neonBlue select-all">soporte@magictravelacademy.com</p>
            <div class="pt-4">
                <a href="{{ route('tutorial.landing') }}" class="px-5 py-2.5 bg-darkCard border border-blue-900/30 rounded-xl text-xs hover:text-white font-medium transition duration-300">
                    ← Volver a la página de inicio
                </a>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full py-8 border-t border-blue-950/40 bg-[#02050a] text-center text-xs text-slate-500 relative z-10">
        <div class="max-w-6xl mx-auto px-6">
            <p>© 2026 Tutoriales Magic Travel. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>
