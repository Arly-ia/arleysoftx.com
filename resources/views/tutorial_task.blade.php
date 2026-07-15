<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorial Completo: Tareas TASK</title>
    
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
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.15);
        }
        .neon-border {
            border-color: rgba(0, 240, 255, 0.2);
        }
        .neon-glow-green {
            box-shadow: 0 0 20px rgba(57, 255, 20, 0.15);
        }
        .tab-btn.active {
            color: #00F0FF;
            border-color: #00F0FF;
            background-color: rgba(0, 240, 255, 0.05);
        }
    </style>
</head>
<body class="bg-darkBg text-slate-100 font-jakarta min-h-screen flex flex-col justify-between overflow-x-hidden selection:bg-neonBlue selection:text-darkBg">
    
    <!-- Background Grid Decoration -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f293710_1px,transparent_1px),linear-gradient(to_bottom,#1f293710_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] pointer-events-none"></div>

    <!-- Header Navigation -->
    <header class="w-full py-6 px-6 max-w-7xl mx-auto flex justify-between items-center relative z-10 border-b border-slate-800/40 bg-darkBg/50 backdrop-blur-md sticky top-0">
        <div class="flex items-center gap-2">
            <a href="{{ url('/') }}" class="text-2xl font-black font-outfit tracking-tighter bg-gradient-to-r from-neonBlue via-white to-neonGreen bg-clip-text text-transparent hover:opacity-90 transition">
                TASK
            </a>
        </div>
        <div class="flex items-center gap-3">
            @if($isPreview)
                <form action="{{ route('tutorial.checkout') }}" method="POST" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-neonBlue hover:text-white transition duration-200 flex items-center gap-1.5 px-4 py-2 rounded-xl bg-neonBlue/10 border border-neonBlue/20 shadow-[0_0_15px_rgba(0,240,255,0.1)]">
                        🚀 Comprar Guía
                    </button>
                </form>
            @endif
            <a href="{{ url('/') }}" class="text-xs font-bold text-slate-400 hover:text-white transition duration-200 flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 border border-slate-800/60">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Volver al Inicio
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-6 py-12 relative z-10 flex-grow w-full space-y-10">
        
        @if(session('success'))
            <div class="max-w-3xl mx-auto p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm flex items-center justify-between shadow-[0_0_15px_rgba(16,185,129,0.1)]">
                <div class="flex items-center gap-2">
                    <span class="text-base">✨</span>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xs opacity-50 hover:opacity-100 font-bold ml-4">Cerrar</button>
            </div>
        @endif

        @if($isPreview)
            <div class="max-w-3xl mx-auto p-6 rounded-3xl bg-gradient-to-r from-neonBlue/15 to-emerald-500/10 border border-neonBlue/30 text-slate-100 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-[0_0_30px_rgba(0,240,255,0.15)] relative overflow-hidden backdrop-blur-md">
                <!-- Decorative Glow -->
                <div class="absolute -right-10 -bottom-10 w-24 h-24 bg-neonBlue/20 rounded-full blur-2xl pointer-events-none"></div>
                <div class="space-y-1.5 text-center sm:text-left relative z-10">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full border border-neonBlue/40 bg-neonBlue/10 text-[10px] font-bold text-neonBlue uppercase tracking-wider font-outfit font-black">
                        🔒 Modo Vista Previa
                    </div>
                    <h3 class="font-outfit font-black text-lg text-white">Guía y Tutoriales Limitados</h3>
                    <p class="text-xs text-slate-400 max-w-md">Estás viendo una versión de prueba. Completa tu pago para desbloquear de inmediato todas las 26 labores, videos e instrucciones detalladas.</p>
                </div>
                <form action="{{ route('tutorial.checkout') }}" method="POST" class="shrink-0 w-full sm:w-auto relative z-10">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gradient-to-r from-neonBlue to-emerald-500 hover:from-cyan-400 hover:to-emerald-400 text-darkBg font-extrabold text-xs uppercase tracking-wider transition duration-300 shadow-[0_0_15px_rgba(0,240,255,0.25)] active:scale-[0.98]">
                        🚀 Desbloquear Guía ($27 USD)
                    </button>
                </form>
            </div>
        @endif
        
        <!-- Title and Introduction -->
        <div class="text-center space-y-4 max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-neonGreen/30 bg-neonGreen/5">
                <span class="w-2 h-2 rounded-full bg-neonGreen"></span>
                <span class="text-xs font-semibold text-neonGreen uppercase tracking-widest font-outfit">Área de Entrenamiento</span>
            </div>
            <h1 class="font-outfit font-black text-4xl sm:text-5xl text-white tracking-tight leading-none">
                Guía Completa y <br class="hidden sm:inline">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-neonBlue via-white to-neonGreen">
                    Tutorial de Tareas TASK
                </span>
            </h1>
            <p class="text-slate-400 text-sm sm:text-base leading-relaxed">
                Aprende paso a paso cómo abrir tu cuenta de aplicación TASK, optimizar tu perfil para recibir tareas de alta rentabilidad y retirar tus ganancias a tu banco o billetera digital.
            </p>
        </div>

        <!-- Navigation Tabs -->
        <div class="border-b border-slate-800/80 max-w-4xl mx-auto">
            <nav class="flex flex-wrap justify-center -mb-px gap-2 sm:gap-4" aria-label="Tabs">
                <button onclick="switchTab('tab-intro')" id="btn-tab-intro" class="tab-btn active px-4 py-2.5 text-sm font-semibold rounded-t-xl border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all duration-200">
                    ℹ️ Introducción
                </button>
                <button onclick="switchTab('tab-config')" id="btn-tab-config" class="tab-btn px-4 py-2.5 text-sm font-semibold rounded-t-xl border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all duration-200">
                    ⚙️ Registro y Configuración
                </button>
                <button onclick="switchTab('tab-work')" id="btn-tab-work" class="tab-btn px-4 py-2.5 text-sm font-semibold rounded-t-xl border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all duration-200">
                    💼 Guía de Trabajo
                </button>
                <button onclick="switchTab('tab-downloads')" id="btn-tab-downloads" class="tab-btn px-4 py-2.5 text-sm font-semibold rounded-t-xl border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all duration-200">
                    📥 Recursos y Descargas
                </button>
                <button onclick="switchTab('tab-faqs')" id="btn-tab-faqs" class="tab-btn px-4 py-2.5 text-sm font-semibold rounded-t-xl border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all duration-200">
                    ❓ Preguntas y Soporte
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <div class="max-w-4xl mx-auto bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 sm:p-10 shadow-xl relative backdrop-blur-sm">
            
            <!-- Tab 1: Introducción -->
            <div id="tab-intro" class="tab-content space-y-6">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🌟</span>
                    <h2 class="font-outfit font-black text-2xl text-white">¿Qué son las Tareas TASK?</h2>
                </div>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    Las tareas TASK representan micro-trabajos remunerados que puedes realizar directamente desde tu smartphone o computadora. Estas actividades van desde la categorización de imágenes y moderación de contenido hasta la transcripción de textos y validación de datos para entrenar modelos de Inteligencia Artificial.
                </p>

                <div class="bg-gradient-to-r from-neonBlue/10 to-transparent p-5 rounded-2xl border-l-4 border-neonBlue space-y-2">
                    <h4 class="font-bold text-white text-sm">💡 Ventajas de este modelo de ingresos:</h4>
                    <ul class="list-disc list-inside text-xs sm:text-sm text-slate-300 space-y-1">
                        <li><strong>Flexibilidad total:</strong> Trabaja en tu propio horario y desde cualquier lugar.</li>
                        <li><strong>Pagos internacionales:</strong> Cobra tus ganancias en dólares (USD) a través de billeteras digitales.</li>
                        <li><strong>Sin experiencia previa:</strong> Solo necesitas conexión a internet y atención al detalle.</li>
                    </ul>
                </div>

                <!-- Video Overview Mockup -->
                <div class="space-y-4 pt-4">
                    <h3 class="font-outfit font-bold text-white text-lg">🎬 Video de Introducción general:</h3>
                    <div class="aspect-video w-full rounded-2xl overflow-hidden bg-black/90 border border-slate-800 shadow-inner relative">
                        <iframe 
                            class="w-full h-full" 
                            src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ" 
                            title="Task Intro Video" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Registro y Configuración -->
            <div id="tab-config" class="tab-content hidden space-y-6">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">⚙️</span>
                    <h2 class="font-outfit font-black text-2xl text-white">Registro e Instalación Paso a Paso</h2>
                </div>
                <p class="text-slate-300 text-sm sm:text-base">
                    Sigue esta secuencia exacta para asegurarte de que tu cuenta sea aprobada rápidamente y empiece a recibir tareas de inmediato:
                </p>

                <!-- Steps Checklist -->
                <div class="space-y-4">
                    <div class="flex gap-4 p-4 rounded-xl bg-slate-900/60 border border-slate-800/40">
                        <div class="w-8 h-8 rounded-full bg-neonBlue/10 border border-neonBlue text-neonBlue flex items-center justify-center font-bold text-sm shrink-0">1</div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-white text-sm sm:text-base">Descargar la Aplicación Oficial</h4>
                            <p class="text-xs sm:text-sm text-slate-400">Busca la aplicación oficial en Google Play Store o Apple App Store. Evita descargar de fuentes externas no verificadas.</p>
                        </div>
                    </div>

                    <div class="flex gap-4 p-4 rounded-xl bg-slate-900/60 border border-slate-800/40">
                        <div class="w-8 h-8 rounded-full bg-neonBlue/10 border border-neonBlue text-neonBlue flex items-center justify-center font-bold text-sm shrink-0">2</div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-white text-sm sm:text-base">Registro con Correo Verificado</h4>
                            <p class="text-xs sm:text-sm text-slate-400">Regístrate utilizando un correo electrónico principal. Te llegará un enlace de confirmación; asegúrate de hacer clic antes de avanzar.</p>
                        </div>
                    </div>

                    <div class="flex gap-4 p-4 rounded-xl bg-slate-900/60 border border-slate-800/40">
                        <div class="w-8 h-8 rounded-full bg-neonBlue/10 border border-neonBlue text-neonBlue flex items-center justify-center font-bold text-sm shrink-0">3</div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-white text-sm sm:text-base">Configuración de Métodos de Pago</h4>
                            <p class="text-xs sm:text-sm text-slate-400">Vincula tu billetera digital (PayPal, Airtm o criptomonedas) en la sección de finanzas para que tus retiros automáticos queden listos.</p>
                        </div>
                    </div>

                    <div class="flex gap-4 p-4 rounded-xl bg-slate-900/60 border border-slate-800/40">
                        <div class="w-8 h-8 rounded-full bg-neonBlue/10 border border-neonBlue text-neonBlue flex items-center justify-center font-bold text-sm shrink-0">4</div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-white text-sm sm:text-base">Examen de Habilitación</h4>
                            <p class="text-xs sm:text-sm text-slate-400">Muchas tareas requieren una prueba corta. Lee las guías correspondientes en nuestra zona de descargas para aprobarlas a la primera.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Guía de Trabajo -->
            <div id="tab-work" class="tab-content hidden space-y-6">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">💼</span>
                    <h2 class="font-outfit font-black text-2xl text-white">Metodología y Ejecución de Tareas</h2>
                </div>
                <p class="text-slate-300 text-sm sm:text-base">
                    Para maximizar la cantidad de dinero que generas diariamente, debes optimizar el tiempo dedicado a cada micro-tarea. Aplica estas estrategias probadas por expertos:
                </p>

                <!-- Tip Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-5 rounded-2xl bg-slate-900/50 border border-slate-800/80 space-y-2">
                        <span class="text-2xl">⚡</span>
                        <h4 class="font-bold text-white text-sm sm:text-base">Elige Tareas Rápidas</h4>
                        <p class="text-xs text-slate-400">A veces es mejor realizar 10 tareas rápidas de 0.10 USD que una sola tarea larga de 1 USD que te tome una hora. Calcula el retorno por minuto.</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-900/50 border border-slate-800/80 space-y-2">
                        <span class="text-2xl">🎯</span>
                        <h4 class="font-bold text-white text-sm sm:text-base">Mantén Alta Precisión</h4>
                        <p class="text-xs text-slate-400">El sistema califica tu precisión. Si respondes al azar, te suspenderán temporalmente la tarea. Es mejor ir con calma y asegurar el pago.</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-900/50 border border-slate-800/80 space-y-2">
                        <span class="text-2xl">🔔</span>
                        <h4 class="font-bold text-white text-sm sm:text-base">Activa Notificaciones</h4>
                        <p class="text-xs text-slate-400">Las tareas de mayor valor se agotan rápido. Activa las notificaciones en tu celular para ingresar inmediatamente apenas aparezcan.</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-900/50 border border-slate-800/80 space-y-2">
                        <span class="text-2xl">📈</span>
                        <h4 class="font-bold text-white text-sm sm:text-base">Sube de Nivel de Cuenta</h4>
                        <p class="text-xs text-slate-400">A medida que completas tareas con buena calificación, tu reputación sube, permitiéndote acceder a tareas exclusivas mejor pagadas.</p>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-800/80 space-y-6">
                    <div class="space-y-1">
                        <h3 class="font-outfit font-bold text-white text-lg flex items-center gap-2">
                            📸 Guía de Grabación y Requisitos por Tarea
                        </h3>
                        <p class="text-slate-400 text-xs sm:text-sm">
                            Consulta la lista oficial de tareas disponibles, su tasa de pago y las especificaciones técnicas obligatorias para cada grabación.
                        </p>
                    </div>

                    <div class="space-y-6">
                        @foreach($tasks as $index => $task)
                        @php
                            $isLockedTask = $isPreview && ($index > 1);
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-900/60 border border-slate-800/40 p-6 rounded-2xl items-center hover:border-neonBlue/40 transition duration-300 relative overflow-hidden">
                            @if($isLockedTask)
                                <!-- Lock Overlay -->
                                <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-[6px] z-20 flex flex-col items-center justify-center p-6 text-center space-y-4">
                                    <div class="w-12 h-12 rounded-2xl bg-neonBlue/10 border border-neonBlue/30 text-neonBlue flex items-center justify-center text-xl shadow-[0_0_15px_rgba(0,240,255,0.15)]">
                                        🔒
                                    </div>
                                    <div class="space-y-1">
                                        <h5 class="font-outfit font-extrabold text-sm text-white uppercase tracking-wider">Labor {{ $index + 1 }}: {{ $task['title'] }}</h5>
                                        <p class="text-xs text-slate-400 max-w-xs">Contenido exclusivo para clientes. Adquiere la guía completa para desbloquear esta labor y las 23 restantes.</p>
                                    </div>
                                    <form action="{{ route('tutorial.checkout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-neonBlue/20 border border-neonBlue/40 hover:bg-neonBlue/30 text-neonBlue text-xs font-bold transition">
                                            🔑 Desbloquear Labor
                                        </button>
                                    </form>
                                </div>
                            @endif
                            <!-- Izquierda: Información de la Tarea -->
                            <div class="space-y-4">
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-slate-800/80 text-slate-300 rounded-full border border-slate-700/50">
                                        Tarea #{{ $index + 1 }}
                                    </span>
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-neonBlue/10 text-neonBlue rounded-full border border-neonBlue/20">
                                        {{ $task['categoria'] }}
                                    </span>
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-500/15 text-emerald-400 rounded-full border border-emerald-500/20">
                                        💵 Pago: {{ $task['pago'] }}
                                    </span>
                                </div>
                                
                                <h4 class="font-outfit font-black text-xl text-white tracking-tight">
                                    {{ $task['title'] }}
                                </h4>

                                <!-- Instrucciones detalladas -->
                                <div class="space-y-1">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest font-outfit">Instrucciones:</span>
                                    <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                                        {{ $task['instrucciones'] }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest font-outfit">Requisitos de Video:</span>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach(explode(',', $task['requisitos']) as $req)
                                            <span class="px-2 py-0.5 text-[10px] font-semibold bg-slate-800/45 text-slate-300 rounded border border-slate-700/45">
                                                {{ trim($req) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Derecha: Imagen / Captura vertical -->
                            <div class="rounded-xl overflow-hidden bg-slate-950 border border-slate-800/60 aspect-[3/4] relative group w-full max-w-[280px] md:max-w-xs justify-self-center md:justify-self-end flex items-center justify-center">
                                @php
                                    $imgPath = 'images/' . $task['image'];
                                    $hasImg = !empty($task['image']) && file_exists(public_path($imgPath));
                                @endphp
                                @if($hasImg)
                                    <img src="{{ asset($imgPath) }}" alt="{{ $task['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-600 p-4 select-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-2 text-slate-800 animate-pulse">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                        </svg>
                                        <span class="text-[10px] font-bold font-outfit uppercase tracking-widest text-slate-800">Foto Pendiente</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tab 4: Recursos y Descargas -->
            <div id="tab-downloads" class="tab-content hidden space-y-6">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">📥</span>
                    <h2 class="font-outfit font-black text-2xl text-white">Manuales y Recursos de Apoyo</h2>
                </div>
                <p class="text-slate-300 text-sm sm:text-base">
                    Hemos preparado material didáctico específico para acelerar tu aprendizaje. Descárgalo y utilízalo como referencia mientras trabajas.
                </p>

                <!-- Downloadable items -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                    
                    <!-- Item 1 -->
                    <div class="bg-slate-900 border border-slate-800 hover:border-neonBlue/50 transition duration-300 rounded-2xl p-6 flex flex-col justify-between h-full">
                        <div class="space-y-2">
                            <span class="text-3xl block">📄</span>
                            <h4 class="font-bold text-white text-sm sm:text-base">Manual de Optimización (PDF)</h4>
                            <p class="text-xs text-slate-400">Guía práctica con imágenes ilustrativas de cómo configurar tu celular para evitar bloqueos y mejorar la recepción de tareas.</p>
                        </div>
                        <div class="pt-4">
                            @if($isPreview)
                                <form action="{{ route('tutorial.checkout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-slate-800 text-slate-500 text-xs font-bold border border-slate-700/50 cursor-pointer hover:bg-neonBlue/10 hover:text-neonBlue hover:border-neonBlue/20 transition">
                                        🔒 Desbloquear Manual (Premium)
                                    </button>
                                </form>
                            @else
                                <a href="#" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-neonBlue/10 hover:bg-neonBlue/20 text-neonBlue text-xs font-bold transition">
                                    Descargar PDF
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 ml-1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="bg-slate-900 border border-slate-800 hover:border-neonGreen/50 transition duration-300 rounded-2xl p-6 flex flex-col justify-between h-full">
                        <div class="space-y-2">
                            <span class="text-3xl block">📊</span>
                            <h4 class="font-bold text-white text-sm sm:text-base">Planilla de Control (Excel)</h4>
                            <p class="text-xs text-slate-400">Lleva un registro ordenado de tus horas dedicadas, tareas realizadas, tasa de precisión y tus cobros acumulados mes a mes.</p>
                        </div>
                        <div class="pt-4">
                            @if($isPreview)
                                <form action="{{ route('tutorial.checkout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-slate-800 text-slate-500 text-xs font-bold border border-slate-700/50 cursor-pointer hover:bg-neonGreen/10 hover:text-neonGreen hover:border-neonGreen/20 transition">
                                        🔒 Desbloquear Planilla (Premium)
                                    </button>
                                </form>
                            @else
                                <a href="#" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-neonGreen/10 hover:bg-neonGreen/20 text-neonGreen text-xs font-bold transition">
                                    Descargar Planilla
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 ml-1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <!-- Tab 5: Preguntas y Soporte -->
            <div id="tab-faqs" class="tab-content hidden space-y-6">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">❓</span>
                    <h2 class="font-outfit font-black text-2xl text-white">Preguntas Frecuentes y Soporte</h2>
                </div>
                <p class="text-slate-300 text-sm sm:text-base">
                    Resolvemos las principales inquietudes para que no detengas tu ritmo de trabajo:
                </p>

                <!-- FAQ Accordion -->
                <div class="space-y-4">
                    <!-- Elementos originales -->
                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">¿Cuánto dinero se puede ganar realmente?</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">Esto depende del tiempo dedicado y el nivel de precisión. Usuarios intermedios en Latinoamérica reportan ganancias estables de entre $5 y $15 USD por día trabajando unas pocas horas.</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">¿Cuál es el mínimo para retirar y cuándo pagan?</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">El monto mínimo de retiro suele ser muy bajo, usualmente a partir de los $2 o $5 USD. Los pagos se procesan semanalmente o de forma instantánea de acuerdo a tu billetera vinculada.</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">¿Puedo realizar tareas desde múltiples dispositivos?</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">Sí, puedes iniciar sesión en tu celular y en tu computadora, pero <strong>no uses la misma cuenta de manera simultánea en dos dispositivos distintos</strong>, ya que el sistema podría detectarlo como comportamiento sospechoso.</p>
                    </div>

                    <!-- Nuevos elementos de WhatsApp -->
                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">Casos de éxito</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">Preguntas y respuestas: ¿Qué contiene el curso/guía?</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">¿En cuánto tiempo puede llegar la guía?</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">¿Necesito carro?</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">¿Cómo recibo el dinero?</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">¿Cuánto tiempo se demora en pagar las horas?</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">¿Cuánto tiempo demora en subir los videos?</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">Video: ¿Cómo se hace plata con TASK?</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">Código de referido</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">¿Cuánto se gana por cada venta de la guía?</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">Comunidad</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">¿Trae una guía incluida?</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">Estados / países donde funciona</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">Videos</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>

                    <div class="border-b border-slate-800 pb-3 space-y-1">
                        <h4 class="font-bold text-white text-sm sm:text-base">Acceso a la comunidad</h4>
                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed italic">Respuesta pendiente de redacción...</p>
                    </div>
                </div>

                <!-- Support Box -->
                <div class="bg-darkBg/60 border border-slate-800 p-5 rounded-2xl text-center space-y-2 mt-6">
                    <p class="text-xs text-slate-400">¿Tienes dudas adicionales o presentas algún problema técnico con tu acceso?</p>
                    <p class="text-sm font-bold text-neonBlue">soporte@magictravelacademy.com</p>
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

    <!-- Simple JavaScript for Interactive Tab Switching -->
    <script>
        function switchTab(tabId) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => {
                content.classList.add('hidden');
            });

            // Show active tab content
            document.getElementById(tabId).classList.remove('hidden');

            // Deactivate all tab buttons
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => {
                btn.classList.remove('active');
            });

            // Activate current button
            // Map tabId back to button ID
            const buttonId = 'btn-' + tabId;
            document.getElementById(buttonId).classList.add('active');
        }
    </script>

</body>
</html>
