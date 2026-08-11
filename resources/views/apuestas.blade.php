<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArleySoftX Play · Pronósticos y Apuestas Deportivas</title>
    <meta name="description" content="Plataforma de pronósticos y apuestas deportivas interactivas ArleySoftX Play. Calendario de los próximos 8 días, cuotas en vivo, estadísticas H2H y simulador demo.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Canvas Confetti CDN -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wpDark: '#070c14',
                        wpDark2: '#0d1624',
                        wpCard: '#131f32',
                        wpCardHover: '#1a2a43',
                        wpBorder: 'rgba(255, 255, 255, 0.08)',
                        wpGreen: '#00e676',
                        wpGreenDark: '#00b843',
                        wpGreenGlow: 'rgba(0, 230, 118, 0.35)',
                        wpBlue: '#00b0ff',
                        wpBlueDark: '#0081cb',
                        wpYellow: '#ffd600',
                        wpRed: '#ff3d00',
                    },
                    fontFamily: {
                        bebas: ['Bebas Neue', 'cursive'],
                        outfit: ['Outfit', 'sans-serif'],
                        jakarta: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #070c14; }
        ::-webkit-scrollbar-thumb { background: #1f304b; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #00e676; }

        .glow-green {
            box-shadow: 0 0 20px rgba(0, 230, 118, 0.3);
        }
        .glow-green-sm {
            box-shadow: 0 0 10px rgba(0, 230, 118, 0.25);
        }
        .glow-blue {
            box-shadow: 0 0 20px rgba(0, 176, 255, 0.3);
        }
        
        .pulse-live {
            animation: pulse-live 1.6s infinite;
        }
        @keyframes pulse-live {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.5; }
        }

        .odd-btn {
            transition: all 0.18s ease-in-out;
        }
        .odd-btn:hover {
            transform: translateY(-2px);
        }
        .odd-btn.selected {
            background: linear-gradient(135deg, #00e676 0%, #00b843 100%) !important;
            color: #070c14 !important;
            border-color: #00e676 !important;
            box-shadow: 0 0 14px rgba(0, 230, 118, 0.5);
            font-weight: 800;
        }
        .odd-btn.selected span.odd-val {
            color: #070c14 !important;
        }

        /* Day tab active */
        .day-tab.active {
            background: linear-gradient(135deg, #00e676 0%, #00b843 100%);
            color: #070c14;
            font-weight: 800;
            border-color: #00e676;
            box-shadow: 0 0 12px rgba(0, 230, 118, 0.3);
        }

        /* Mobile drawer slide */
        @media (max-width: 1023px) {
            #betslipDrawer.open {
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-wpDark text-slate-100 font-jakarta min-h-screen flex flex-col justify-between selection:bg-wpGreen selection:text-wpDark">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 bg-wpDark2/95 backdrop-blur-md border-b border-wpBorder">
        <!-- Main Header -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between gap-4">
            
            <!-- Brand Logo -->
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-400 hover:text-wpGreen transition flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden sm:inline">ArleySoftX</span>
                </a>
                <div class="h-4 w-px bg-slate-800 hidden sm:block"></div>
                <div class="flex items-center gap-1.5">
                    <span class="font-bebas text-3xl tracking-wider text-white">ARLEYSOFTX <span class="text-wpGreen">PLAY</span></span>
                    <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-widest bg-wpGreen/20 text-wpGreen border border-wpGreen/30 rounded font-outfit">
                        SIMULADOR DEMO
                    </span>
                </div>
            </div>

            <!-- Virtual Balance Bar & Actions -->
            <div class="flex items-center gap-2 sm:gap-4">
                
                <!-- Sound Toggle -->
                <button id="soundToggleBtn" onclick="toggleSound()" class="p-2 rounded-xl bg-wpCard hover:bg-wpCardHover border border-wpBorder text-slate-300 hover:text-wpGreen transition" title="Activar/Desactivar Sonido">
                    <span id="soundIcon">🔊</span>
                </button>

                <!-- Virtual Balance Card -->
                <div class="flex items-center bg-wpCard border border-wpBorder rounded-2xl px-3 sm:px-4 py-1.5 gap-2 sm:gap-3">
                    <div class="flex flex-col text-right">
                        <span class="text-[10px] uppercase font-bold text-slate-400 font-outfit tracking-wider">Saldo Demo</span>
                        <span id="userBalanceDisplay" class="font-bebas text-lg sm:text-2xl text-wpGreen tracking-wide">$100.000 COP</span>
                    </div>
                    <button onclick="rechargeBalance()" class="bg-gradient-to-r from-wpGreen to-wpGreenDark text-wpDark font-black font-outfit text-xs px-2.5 sm:px-3 py-1.5 rounded-xl hover:brightness-110 active:scale-95 transition shadow-sm flex items-center gap-1">
                        <span>+</span>
                        <span class="hidden sm:inline">Recargar</span>
                    </button>
                </div>

                <!-- Floating BetSlip Trigger on Mobile -->
                <button onclick="toggleMobileBetslip()" class="lg:hidden relative bg-wpBlue hover:bg-wpBlueDark text-wpDark font-black font-outfit px-3 py-2 rounded-xl flex items-center gap-1.5 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span id="mobileSlipCount" class="w-5 h-5 bg-wpDark text-wpGreen rounded-full flex items-center justify-center text-xs font-black">0</span>
                </button>
            </div>
        </div>

        <!-- 8-Day Calendar Schedule Navigation Bar -->
        <div class="bg-wpDark border-t border-wpBorder/80 overflow-x-auto scrollbar-none py-2 px-4">
            <div class="max-w-7xl mx-auto flex items-center gap-2 text-xs font-outfit" id="daysBarContainer">
                <!-- Injected dynamically by JS for the next 8 days -->
            </div>
        </div>

        <!-- Sports & Categories Bar -->
        <div class="bg-wpDark2/70 border-t border-wpBorder/40 overflow-x-auto scrollbar-none">
            <div class="max-w-7xl mx-auto px-4 flex items-center gap-1 sm:gap-2 py-2 text-xs font-outfit font-bold whitespace-nowrap">
                <button onclick="filterSport('all')" class="sport-tab active px-3 py-1.5 rounded-xl bg-wpGreen text-wpDark font-black transition flex items-center gap-1.5" data-sport="all">
                    <span>🔥</span>
                    <span>Destacados</span>
                </button>
                <button onclick="filterSport('live')" class="sport-tab px-3 py-1.5 rounded-xl bg-wpCard hover:bg-wpCardHover text-slate-200 border border-wpBorder transition flex items-center gap-1.5" data-sport="live">
                    <span class="w-2 h-2 rounded-full bg-wpRed pulse-live"></span>
                    <span class="text-wpRed font-black">EN VIVO</span>
                    <span class="text-[10px] px-1.5 py-0.2 bg-wpRed/20 text-wpRed rounded-full" id="liveMatchBadgeCount">3</span>
                </button>
                <button onclick="filterSport('futbol')" class="sport-tab px-3 py-1.5 rounded-xl bg-wpCard hover:bg-wpCardHover text-slate-200 border border-wpBorder transition flex items-center gap-1.5" data-sport="futbol">
                    <span>⚽</span>
                    <span>Fútbol</span>
                </button>
                <button onclick="filterSport('baloncesto')" class="sport-tab px-3 py-1.5 rounded-xl bg-wpCard hover:bg-wpCardHover text-slate-200 border border-wpBorder transition flex items-center gap-1.5" data-sport="baloncesto">
                    <span>🏀</span>
                    <span>Baloncesto (NBA)</span>
                </button>
                <button onclick="filterSport('tenis')" class="sport-tab px-3 py-1.5 rounded-xl bg-wpCard hover:bg-wpCardHover text-slate-200 border border-wpBorder transition flex items-center gap-1.5" data-sport="tenis">
                    <span>🎾</span>
                    <span>Tenis</span>
                </button>
                <button onclick="filterSport('esports')" class="sport-tab px-3 py-1.5 rounded-xl bg-wpCard hover:bg-wpCardHover text-slate-200 border border-wpBorder transition flex items-center gap-1.5" data-sport="esports">
                    <span>🎮</span>
                    <span>eSports</span>
                </button>
                
                <div class="h-4 w-px bg-slate-800 mx-1"></div>

                <!-- Simulation Quick Trigger -->
                <button onclick="simulateAllLiveTick()" class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-wpYellow/20 to-amber-500/20 text-wpYellow border border-wpYellow/40 hover:bg-wpYellow hover:text-wpDark font-black transition flex items-center gap-1.5">
                    <span>⚡</span>
                    <span>Simular Minuto a Minuto</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 w-full flex-grow">
        
        <!-- Hero Sports Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-wpCard via-[#122238] to-wpCard border border-wpBorder p-6 sm:p-8 mb-6">
            <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-wpGreen/10 to-transparent pointer-events-none"></div>
            
            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-wpGreen/10 border border-wpGreen/30 text-wpGreen text-xs font-bold font-outfit uppercase tracking-wider mb-3">
                    <span class="w-2 h-2 rounded-full bg-wpGreen animate-ping"></span>
                    ArleySoftX Play · Calendario 8 Días & Estadísticas H2H
                </div>
                <h1 class="font-bebas text-4xl sm:text-5xl md:text-6xl text-white tracking-wide leading-none mb-2">
                    PRONÓSTICOS & <span class="text-wpGreen">ESTADÍSTICAS EN VIVO</span>
                </h1>
                <p class="text-slate-300 text-sm sm:text-base font-light leading-relaxed mb-4">
                    Haz clic en cualquier partido para analizar el **historial de enfrentamientos directos (H2H)**, rachas y estadísticas antes de armar tus boletos simples o combinados.
                </p>
                <div class="flex flex-wrap gap-3 items-center">
                    <button onclick="quickAddCombo()" class="bg-gradient-to-r from-wpGreen to-wpGreenDark text-wpDark font-outfit font-black text-sm px-5 py-2.5 rounded-xl hover:scale-105 active:scale-95 transition glow-green-sm flex items-center gap-2">
                        <span>🚀</span>
                        <span>Cargar Parlay Recomendado (+12.45x)</span>
                    </button>
                    <button onclick="switchView('history')" class="bg-wpCard hover:bg-wpCardHover text-slate-200 border border-wpBorder font-outfit font-bold text-sm px-4 py-2.5 rounded-xl transition flex items-center gap-2">
                        <span>📜</span>
                        <span>Ver Mis Boletos (<span id="ticketCountBadge">0</span>)</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Layout: Matches Column + BetSlip Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left & Center: Matches Feed (8 cols on lg) -->
            <section class="lg:col-span-8 space-y-6">
                
                <!-- Active Filter & League Switcher -->
                <div class="flex flex-wrap items-center justify-between bg-wpDark2 border border-wpBorder rounded-2xl p-4 gap-2">
                    <div class="flex items-center gap-2">
                        <span id="currentLeagueTitle" class="font-outfit font-black text-base sm:text-lg text-white">
                            🏆 Partidos de Hoy & En Vivo
                        </span>
                        <span id="activeDateBadge" class="text-xs px-2.5 py-0.5 bg-wpCard border border-wpBorder rounded-full text-slate-400 font-bold font-outfit">
                            Hoy
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="resetAllMatches()" class="text-xs text-slate-400 hover:text-wpGreen transition font-outfit flex items-center gap-1">
                            <span>🔄</span> Reiniciar Partidos
                        </button>
                    </div>
                </div>

                <!-- Matches Container -->
                <div id="matchesContainer" class="space-y-4">
                    <!-- Dynamic Matches injected by JS -->
                </div>

            </section>

            <!-- Right: BetSlip Sidebar (4 cols on lg, Desktop Sticky) -->
            <aside class="hidden lg:block lg:col-span-4 sticky top-24">
                <div class="bg-wpDark2 border border-wpBorder rounded-3xl overflow-hidden shadow-2xl">
                    <!-- BetSlip Component Included Here -->
                    <div id="desktopBetslipContainer">
                        <!-- Rendered by JS -->
                    </div>
                </div>
            </aside>

        </div>

    </main>

    <!-- Mobile Drawer BetSlip (Modal Bottom Sheet) -->
    <div id="betslipDrawer" class="lg:hidden fixed inset-0 z-50 transform translate-y-full transition-transform duration-300 ease-in-out pointer-events-none">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm pointer-events-auto" onclick="toggleMobileBetslip()"></div>
        <div class="absolute bottom-0 inset-x-0 bg-wpDark2 border-t border-wpBorder rounded-t-3xl max-h-[85vh] flex flex-col pointer-events-auto shadow-2xl">
            <div class="p-4 border-b border-wpBorder flex items-center justify-between bg-wpCard/50">
                <div class="flex items-center gap-2">
                    <span class="font-bebas text-2xl text-white">BOLETO DE APUESTAS</span>
                    <span id="mobileDrawerCountBadge" class="px-2 py-0.5 bg-wpGreen text-wpDark font-black text-xs rounded-full">0</span>
                </div>
                <button onclick="toggleMobileBetslip()" class="p-2 text-slate-400 hover:text-white">✕</button>
            </div>
            <div id="mobileBetslipContainer" class="overflow-y-auto p-4 flex-grow">
                <!-- Rendered by JS -->
            </div>
        </div>
    </div>

    <!-- Modal: Estadísticas & Histórico H2H de Enfrentamientos -->
    <div id="statsModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4">
        <div class="bg-wpDark2 border border-wpBorder rounded-3xl w-full max-w-2xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            
            <!-- Modal Header -->
            <div class="p-4 sm:p-5 border-b border-wpBorder flex items-center justify-between bg-wpCard">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-wpGreen/15 border border-wpGreen/30 text-wpGreen flex items-center justify-center text-xl">
                        📊
                    </div>
                    <div>
                        <h2 class="font-bebas text-2xl text-white tracking-wide">ESTADÍSTICAS & HISTÓRICO H2H</h2>
                        <p class="text-xs text-slate-400" id="statsModalLeague">Liga BetPlay Dimayor</p>
                    </div>
                </div>
                <button onclick="closeStatsModal()" class="w-9 h-9 rounded-full bg-wpCardHover flex items-center justify-center text-slate-300 hover:text-white font-bold transition">
                    ✕
                </button>
            </div>

            <!-- Modal Content (Scrollable) -->
            <div id="statsModalContent" class="p-4 sm:p-6 overflow-y-auto space-y-6 flex-grow">
                <!-- Injected dynamically by JS -->
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-wpBorder bg-wpCard/40 flex items-center justify-between">
                <span class="text-xs text-slate-400 font-outfit">Datos históricos y proyecciones de rendimiento</span>
                <button onclick="closeStatsModal()" class="px-5 py-2 rounded-xl bg-wpGreen text-wpDark font-outfit font-black text-sm hover:brightness-110 transition">
                    Entendido
                </button>
            </div>
        </div>
    </div>

    <!-- Bet History Modal -->
    <div id="historyModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-wpDark2 border border-wpBorder rounded-3xl w-full max-w-3xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="p-5 border-b border-wpBorder flex items-center justify-between bg-wpCard">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-wpGreen/10 border border-wpGreen/30 text-wpGreen flex items-center justify-center text-xl">
                        📜
                    </div>
                    <div>
                        <h2 class="font-bebas text-2xl text-white tracking-wide">HISTORIAL DE APUESTAS & BOLETOS</h2>
                        <p class="text-xs text-slate-400">Consulta tus boletos pendientes, ganados o cerrados en el simulador</p>
                    </div>
                </div>
                <button onclick="closeHistoryModal()" class="w-9 h-9 rounded-full bg-wpCardHover flex items-center justify-center text-slate-300 hover:text-white font-bold transition">
                    ✕
                </button>
            </div>

            <div id="historyListContainer" class="p-6 overflow-y-auto space-y-4 flex-grow">
                <!-- Injected by JS -->
            </div>

            <div class="p-4 border-t border-wpBorder bg-wpCard/40 flex items-center justify-between">
                <button onclick="clearHistory()" class="text-xs text-rose-400 hover:text-rose-300 font-outfit font-bold transition">
                    🗑️ Borrar Historial
                </button>
                <button onclick="closeHistoryModal()" class="px-5 py-2 rounded-xl bg-wpCardHover text-white font-outfit font-bold text-sm hover:bg-slate-700 transition">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Recharge Modal -->
    <div id="rechargeModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-wpDark2 border border-wpBorder rounded-3xl w-full max-w-md p-6 shadow-2xl text-center">
            <div class="w-14 h-14 mx-auto rounded-3xl bg-wpGreen/20 border border-wpGreen/40 text-wpGreen flex items-center justify-center text-3xl mb-4 glow-green-sm">
                💰
            </div>
            <h3 class="font-bebas text-3xl text-white mb-2">RECARGA TU SALDO DEMO</h3>
            <p class="text-xs text-slate-400 mb-6 font-light">
                Este simulador no utiliza dinero real. Recarga saldo virtual para seguir probando tus mejores pronósticos deportivos.
            </p>
            <div class="grid grid-cols-2 gap-3 mb-6">
                <button onclick="applyRecharge(50000)" class="p-3 bg-wpCard hover:bg-wpGreen hover:text-wpDark border border-wpBorder rounded-2xl font-outfit font-black text-sm text-slate-200 transition">
                    +$50.000 COP
                </button>
                <button onclick="applyRecharge(100000)" class="p-3 bg-wpCard hover:bg-wpGreen hover:text-wpDark border border-wpBorder rounded-2xl font-outfit font-black text-sm text-slate-200 transition">
                    +$100.000 COP
                </button>
                <button onclick="applyRecharge(500000)" class="p-3 bg-wpCard hover:bg-wpGreen hover:text-wpDark border border-wpBorder rounded-2xl font-outfit font-black text-sm text-slate-200 transition">
                    +$500.000 COP
                </button>
                <button onclick="applyRecharge(1000000)" class="p-3 bg-wpCard hover:bg-wpGreen hover:text-wpDark border border-wpBorder rounded-2xl font-outfit font-black text-sm text-slate-200 transition">
                    +$1.000.000 (VIP)
                </button>
            </div>
            <button onclick="closeRechargeModal()" class="w-full py-3 bg-wpCardHover text-slate-300 rounded-xl font-outfit font-bold text-sm hover:text-white transition">
                Cancelar
            </button>
        </div>
    </div>

    <!-- Match Simulation Live Feed Ticker Toast -->
    <div id="simToast" class="fixed bottom-6 right-6 z-50 bg-wpCard border border-wpGreen/50 text-white rounded-2xl p-4 shadow-2xl max-w-sm hidden transition-all duration-300 transform translate-y-4">
        <div class="flex items-center gap-3">
            <span class="text-2xl animate-bounce">⚽</span>
            <div class="flex-1">
                <div class="text-[10px] font-bold text-wpGreen uppercase font-outfit tracking-wider">¡EVENTO EN VIVO!</div>
                <div id="simToastMessage" class="text-xs font-semibold">Gol anotado en tiempo real</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-wpBorder bg-wpDark2 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 font-light">
            <div class="flex items-center gap-2">
                <span class="font-bebas text-lg text-white">ARLEYSOFTX <span class="text-wpGreen">PLAY</span></span>
                <span>· Simulador & Estadísticas Deportivas</span>
            </div>
            <p>Plataforma de pronósticos y simulación deportiva para entretenimiento y aprendizaje.</p>
            <div class="flex items-center gap-4 text-slate-400">
                <a href="{{ route('puntico') }}" class="hover:text-wpGreen transition font-outfit font-semibold">📍 Panel de Rutas</a>
                <a href="{{ route('home') }}" class="hover:text-wpGreen transition font-outfit font-semibold">🏠 Home</a>
            </div>
        </div>
    </footer>

    <!-- Betting App Logic & Audio Synthesis Engine -->
    <script>
        /* =========================================================================
           1. AUDIO SYNTHESIZER (Web Audio API)
           ========================================================================= */
        let audioCtx = null;
        let soundEnabled = true;

        function getAudioContext() {
            if (!audioCtx) {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                audioCtx = new AudioContextClass();
            }
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            return audioCtx;
        }

        function playSound(type) {
            if (!soundEnabled) return;
            try {
                const ctx = getAudioContext();
                const now = ctx.currentTime;

                if (type === 'click') {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(440, now);
                    osc.frequency.exponentialRampToValueAtTime(880, now + 0.05);
                    gain.gain.setValueAtTime(0.08, now);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.05);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now);
                    osc.stop(now + 0.05);
                } else if (type === 'bet_placed') {
                    [523.25, 659.25, 783.99, 1046.50].forEach((freq, i) => {
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'triangle';
                        osc.frequency.setValueAtTime(freq, now + i * 0.06);
                        gain.gain.setValueAtTime(0.12, now + i * 0.06);
                        gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.06 + 0.18);
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.start(now + i * 0.06);
                        osc.stop(now + i * 0.06 + 0.18);
                    });
                } else if (type === 'goal') {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(2400, now);
                    osc.frequency.setValueAtTime(2800, now + 0.1);
                    osc.frequency.setValueAtTime(2400, now + 0.2);
                    gain.gain.setValueAtTime(0.1, now);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now);
                    osc.stop(now + 0.35);
                } else if (type === 'win') {
                    [523.25, 659.25, 783.99, 1046.50, 1318.51].forEach((freq, idx) => {
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(freq, now + idx * 0.09);
                        gain.gain.setValueAtTime(0.15, now + idx * 0.09);
                        gain.gain.exponentialRampToValueAtTime(0.001, now + idx * 0.09 + 0.3);
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.start(now + idx * 0.09);
                        osc.stop(now + idx * 0.09 + 0.3);
                    });
                }
            } catch (e) {
                console.log('Audio disabled or blocked', e);
            }
        }

        function toggleSound() {
            soundEnabled = !soundEnabled;
            localStorage.setItem('wp_sound', soundEnabled ? '1' : '0');
            document.getElementById('soundIcon').innerText = soundEnabled ? '🔊' : '🔇';
        }

        /* =========================================================================
           2. 8-DAY CALENDAR & MATCHES DATABASE
           ========================================================================= */
        const DEFAULT_BALANCE = 100000;
        let balance = parseInt(localStorage.getItem('wp_balance')) || DEFAULT_BALANCE;
        let selectedBets = [];
        let betMode = 'single';
        let currentSportFilter = 'all';
        let currentDayFilter = 0; // 0 = Hoy, 1 = Mañana, ..., 'all' = Todos los 8 días
        let betHistory = JSON.parse(localStorage.getItem('wp_history') || '[]');

        // Generate next 8 days array with real date strings
        const next8Days = [];
        const dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        const monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        for (let i = 0; i < 8; i++) {
            const d = new Date();
            d.setDate(d.getDate() + i);
            next8Days.push({
                offset: i,
                dayName: i === 0 ? 'Hoy' : i === 1 ? 'Mañana' : dayNames[d.getDay()],
                dateString: `${d.getDate()} ${monthNames[d.getMonth()]}`,
                fullFormatted: `${dayNames[d.getDay()]} ${d.getDate()} de ${monthNames[d.getMonth()]}`
            });
        }

        // Comprehensive Fixture across the 8 Days
        const INITIAL_MATCHES = [
            // DÍA 0: HOY
            {
                id: 'm1',
                dayOffset: 0,
                sport: 'futbol',
                league: '🇨🇴 Liga BetPlay Dimayor',
                isLive: true,
                minute: 74,
                home: 'Atlético Nacional',
                away: 'Millonarios FC',
                homeScore: 2,
                awayScore: 1,
                stats: { possession: [54, 46], shots: [8, 5], corners: [6, 4] },
                h2h: {
                    homeWins: 14, draws: 10, awayWins: 11,
                    lastMatches: [
                        { date: '15 Mar 2026', home: 'Millonarios', away: 'Nacional', score: '0 - 1' },
                        { date: '12 Nov 2025', home: 'Nacional', away: 'Millonarios', score: '2 - 2' },
                        { date: '28 Jul 2025', home: 'Millonarios', away: 'Nacional', score: '1 - 2' },
                        { date: '10 Feb 2025', home: 'Nacional', away: 'Millonarios', score: '0 - 0' },
                        { date: '19 Oct 2024', home: 'Nacional', away: 'Millonarios', score: '1 - 0' }
                    ],
                    homeStreak: ['V', 'V', 'E', 'V', 'D'],
                    awayStreak: ['D', 'E', 'V', 'D', 'V'],
                    homeWinProb: 48, drawProb: 28, awayWinProb: 24,
                    avgGoals: 2.4, bttsProb: 62
                },
                odds: {
                    '1X2': { '1': 1.62, 'X': 3.45, '2': 5.20 },
                    'over_under': { 'Over 2.5': 1.55, 'Under 2.5': 2.30 },
                    'btts': { 'Ambos Si': 1.70, 'Ambos No': 2.05 }
                }
            },
            {
                id: 'm2',
                dayOffset: 0,
                sport: 'futbol',
                league: '🇪🇺 UEFA Champions League',
                isLive: true,
                minute: 38,
                home: 'Real Madrid',
                away: 'Manchester City',
                homeScore: 1,
                awayScore: 1,
                stats: { possession: [48, 52], shots: [6, 7], corners: [3, 5] },
                h2h: {
                    homeWins: 6, draws: 5, awayWins: 5,
                    lastMatches: [
                        { date: '17 Abr 2025', home: 'Man City', away: 'Real Madrid', score: '1 - 1' },
                        { date: '09 Abr 2025', home: 'Real Madrid', away: 'Man City', score: '3 - 3' },
                        { date: '17 May 2024', home: 'Man City', away: 'Real Madrid', score: '4 - 0' },
                        { date: '09 May 2024', home: 'Real Madrid', away: 'Man City', score: '1 - 1' },
                        { date: '04 May 2023', home: 'Real Madrid', away: 'Man City', score: '3 - 1' }
                    ],
                    homeStreak: ['V', 'V', 'V', 'E', 'V'],
                    awayStreak: ['V', 'E', 'V', 'V', 'D'],
                    homeWinProb: 42, drawProb: 26, awayWinProb: 32,
                    avgGoals: 3.6, bttsProb: 75
                },
                odds: {
                    '1X2': { '1': 2.40, 'X': 3.60, '2': 2.75 },
                    'over_under': { 'Over 2.5': 1.65, 'Under 2.5': 2.15 },
                    'btts': { 'Ambos Si': 1.45, 'Ambos No': 2.55 }
                }
            },
            {
                id: 'm3',
                dayOffset: 0,
                sport: 'futbol',
                league: '🇨🇴 Liga BetPlay Dimayor',
                isLive: true,
                minute: 15,
                home: 'Junior de Barranquilla',
                away: 'América de Cali',
                homeScore: 0,
                awayScore: 0,
                stats: { possession: [60, 40], shots: [3, 1], corners: [2, 0] },
                h2h: {
                    homeWins: 9, draws: 7, awayWins: 8,
                    lastMatches: [
                        { date: '21 Ene 2026', home: 'América', away: 'Junior', score: '1 - 0' },
                        { date: '04 Oct 2025', home: 'Junior', away: 'América', score: '3 - 1' },
                        { date: '18 May 2025', home: 'América', away: 'Junior', score: '2 - 0' },
                        { date: '25 Ene 2025', home: 'Junior', away: 'América', score: '1 - 1' },
                        { date: '02 Sep 2024', home: 'América', away: 'Junior', score: '1 - 2' }
                    ],
                    homeStreak: ['E', 'V', 'D', 'V', 'E'],
                    awayStreak: ['V', 'V', 'E', 'D', 'V'],
                    homeWinProb: 45, drawProb: 30, awayWinProb: 25,
                    avgGoals: 2.2, bttsProb: 55
                },
                odds: {
                    '1X2': { '1': 2.05, 'X': 3.10, '2': 3.80 },
                    'over_under': { 'Over 2.5': 2.10, 'Under 2.5': 1.68 },
                    'btts': { 'Ambos Si': 1.95, 'Ambos No': 1.80 }
                }
            },
            {
                id: 'm4',
                dayOffset: 0,
                sport: 'baloncesto',
                league: '🇺🇸 NBA Basketball',
                isLive: false,
                startTime: 'Hoy 21:00',
                home: 'Los Angeles Lakers',
                away: 'Golden State Warriors',
                homeScore: 0,
                awayScore: 0,
                h2h: {
                    homeWins: 18, draws: 0, awayWins: 15,
                    lastMatches: [
                        { date: '22 Feb 2026', home: 'Warriors', away: 'Lakers', score: '128 - 110' },
                        { date: '15 Ene 2026', home: 'Lakers', away: 'Warriors', score: '145 - 144' },
                        { date: '16 Mar 2025', home: 'Lakers', away: 'Warriors', score: '121 - 128' },
                        { date: '27 Ene 2025', home: 'Warriors', away: 'Lakers', score: '144 - 145' }
                    ],
                    homeStreak: ['V', 'V', 'D', 'V', 'D'],
                    awayStreak: ['D', 'V', 'V', 'D', 'V'],
                    homeWinProb: 53, drawProb: 0, awayWinProb: 47,
                    avgGoals: 232.0, bttsProb: 90
                },
                odds: {
                    '1X2': { '1': 1.80, 'X': 14.0, '2': 2.10 },
                    'over_under': { 'Over 224.5': 1.90, 'Under 224.5': 1.90 },
                    'btts': { 'Handicap -3.5': 1.95, 'Handicap +3.5': 1.85 }
                }
            },

            // DÍA 1: MAÑANA
            {
                id: 'm5',
                dayOffset: 1,
                sport: 'futbol',
                league: '🏴󠁧󠁢󠁥󠁮󠁧󠁿 Premier League',
                isLive: false,
                startTime: 'Mañana 14:00',
                home: 'Arsenal FC',
                away: 'Chelsea FC',
                homeScore: 0,
                awayScore: 0,
                h2h: {
                    homeWins: 11, draws: 6, awayWins: 8,
                    lastMatches: [
                        { date: '23 Abr 2025', home: 'Arsenal', away: 'Chelsea', score: '5 - 0' },
                        { date: '21 Oct 2024', home: 'Chelsea', away: 'Arsenal', score: '2 - 2' },
                        { date: '02 May 2024', home: 'Arsenal', away: 'Chelsea', score: '3 - 1' },
                        { date: '06 Nov 2023', home: 'Chelsea', away: 'Arsenal', score: '0 - 1' }
                    ],
                    homeStreak: ['V', 'V', 'V', 'E', 'V'],
                    awayStreak: ['D', 'V', 'E', 'V', 'D'],
                    homeWinProb: 55, drawProb: 25, awayWinProb: 20,
                    avgGoals: 3.1, bttsProb: 60
                },
                odds: {
                    '1X2': { '1': 1.75, 'X': 3.85, '2': 4.30 },
                    'over_under': { 'Over 2.5': 1.72, 'Under 2.5': 2.08 },
                    'btts': { 'Ambos Si': 1.68, 'Ambos No': 2.10 }
                }
            },
            {
                id: 'm6',
                dayOffset: 1,
                sport: 'futbol',
                league: '🇨🇴 Liga BetPlay Dimayor',
                isLive: false,
                startTime: 'Mañana 18:10',
                home: 'Independiente Santa Fe',
                away: 'Deportivo Cali',
                homeScore: 0,
                awayScore: 0,
                h2h: {
                    homeWins: 8, draws: 9, awayWins: 6,
                    lastMatches: [
                        { date: '10 Feb 2026', home: 'Santa Fe', away: 'Cali', score: '1 - 0' },
                        { date: '20 Ago 2025', home: 'Cali', away: 'Santa Fe', score: '2 - 2' },
                        { date: '13 Feb 2025', home: 'Santa Fe', away: 'Cali', score: '1 - 1' }
                    ],
                    homeStreak: ['V', 'E', 'V', 'D', 'V'],
                    awayStreak: ['D', 'D', 'E', 'V', 'D'],
                    homeWinProb: 52, drawProb: 30, awayWinProb: 18,
                    avgGoals: 2.1, bttsProb: 48
                },
                odds: {
                    '1X2': { '1': 1.90, 'X': 3.20, '2': 4.10 },
                    'over_under': { 'Over 2.5': 2.25, 'Under 2.5': 1.60 },
                    'btts': { 'Ambos Si': 2.00, 'Ambos No': 1.75 }
                }
            },

            // DÍA 2
            {
                id: 'm7',
                dayOffset: 2,
                sport: 'futbol',
                league: '🇪🇺 UEFA Champions League',
                isLive: false,
                startTime: '14:00',
                home: 'Bayern Munich',
                away: 'Paris Saint-Germain',
                homeScore: 0,
                awayScore: 0,
                h2h: {
                    homeWins: 7, draws: 1, awayWins: 5,
                    lastMatches: [
                        { date: '08 Mar 2024', home: 'Bayern', away: 'PSG', score: '2 - 0' },
                        { date: '14 Feb 2024', home: 'PSG', away: 'Bayern', score: '0 - 1' },
                        { date: '13 Abr 2023', home: 'PSG', away: 'Bayern', score: '0 - 1' }
                    ],
                    homeStreak: ['V', 'V', 'E', 'V', 'V'],
                    awayStreak: ['V', 'V', 'D', 'V', 'E'],
                    homeWinProb: 50, drawProb: 24, awayWinProb: 26,
                    avgGoals: 3.4, bttsProb: 70
                },
                odds: {
                    '1X2': { '1': 1.95, 'X': 3.75, '2': 3.60 },
                    'over_under': { 'Over 2.5': 1.50, 'Under 2.5': 2.50 },
                    'btts': { 'Ambos Si': 1.50, 'Ambos No': 2.45 }
                }
            },

            // DÍA 3
            {
                id: 'm8',
                dayOffset: 3,
                sport: 'tenis',
                league: '🎾 ATP Masters 1000',
                isLive: false,
                startTime: '16:30',
                home: 'Carlos Alcaraz',
                away: 'Jannik Sinner',
                homeScore: 0,
                awayScore: 0,
                h2h: {
                    homeWins: 5, draws: 0, awayWins: 4,
                    lastMatches: [
                        { date: '07 Jun 2025', home: 'Alcaraz', away: 'Sinner', score: '3 - 2' },
                        { date: '17 Mar 2025', home: 'Alcaraz', away: 'Sinner', score: '2 - 1' },
                        { date: '03 Oct 2024', home: 'Sinner', away: 'Alcaraz', score: '2 - 0' }
                    ],
                    homeStreak: ['V', 'V', 'V', 'V', 'D'],
                    awayStreak: ['V', 'V', 'V', 'D', 'V'],
                    homeWinProb: 52, drawProb: 0, awayWinProb: 48,
                    avgGoals: 23.5, bttsProb: 80
                },
                odds: {
                    '1X2': { '1': 1.92, 'X': 18.0, '2': 1.90 },
                    'over_under': { 'Over 22.5 Games': 1.85, 'Under 22.5 Games': 1.95 },
                    'btts': { 'Set 1 Ganador 1': 1.90, 'Set 1 Ganador 2': 1.90 }
                }
            },

            // DÍA 4
            {
                id: 'm9',
                dayOffset: 4,
                sport: 'futbol',
                league: '🇪🇸 LaLiga EA Sports',
                isLive: false,
                startTime: '15:00',
                home: 'Barcelona FC',
                away: 'Atlético de Madrid',
                homeScore: 0,
                awayScore: 0,
                h2h: {
                    homeWins: 14, draws: 8, awayWins: 7,
                    lastMatches: [
                        { date: '17 Mar 2025', home: 'Atlético', away: 'Barcelona', score: '0 - 3' },
                        { date: '03 Dic 2024', home: 'Barcelona', away: 'Atlético', score: '1 - 0' },
                        { date: '23 Abr 2024', home: 'Barcelona', away: 'Atlético', score: '1 - 0' }
                    ],
                    homeStreak: ['V', 'V', 'E', 'V', 'V'],
                    awayStreak: ['V', 'E', 'D', 'V', 'V'],
                    homeWinProb: 51, drawProb: 26, awayWinProb: 23,
                    avgGoals: 2.8, bttsProb: 58
                },
                odds: {
                    '1X2': { '1': 1.85, 'X': 3.60, '2': 4.00 },
                    'over_under': { 'Over 2.5': 1.70, 'Under 2.5': 2.10 },
                    'btts': { 'Ambos Si': 1.65, 'Ambos No': 2.15 }
                }
            },

            // DÍA 5
            {
                id: 'm10',
                dayOffset: 5,
                sport: 'futbol',
                league: '🇨🇴 Liga BetPlay Dimayor',
                isLive: false,
                startTime: '20:30',
                home: 'Independiente Medellín',
                away: 'Deportes Tolima',
                homeScore: 0,
                awayScore: 0,
                h2h: {
                    homeWins: 10, draws: 11, awayWins: 9,
                    lastMatches: [
                        { date: '14 Sep 2025', home: 'Tolima', away: 'Medellín', score: '2 - 2' },
                        { date: '22 Mar 2025', home: 'Medellín', away: 'Tolima', score: '1 - 1' },
                        { date: '05 Mar 2025', home: 'Tolima', away: 'Medellín', score: '0 - 0' }
                    ],
                    homeStreak: ['V', 'E', 'E', 'V', 'D'],
                    awayStreak: ['V', 'V', 'D', 'E', 'V'],
                    homeWinProb: 40, drawProb: 35, awayWinProb: 25,
                    avgGoals: 1.9, bttsProb: 44
                },
                odds: {
                    '1X2': { '1': 2.20, 'X': 3.05, '2': 3.40 },
                    'over_under': { 'Over 2.5': 2.30, 'Under 2.5': 1.58 },
                    'btts': { 'Ambos Si': 2.10, 'Ambos No': 1.68 }
                }
            },

            // DÍA 6
            {
                id: 'm11',
                dayOffset: 6,
                sport: 'baloncesto',
                league: '🇺🇸 NBA Basketball',
                isLive: false,
                startTime: '20:00',
                home: 'Boston Celtics',
                away: 'Miami Heat',
                homeScore: 0,
                awayScore: 0,
                h2h: {
                    homeWins: 16, draws: 0, awayWins: 12,
                    lastMatches: [
                        { date: '01 May 2025', home: 'Celtics', away: 'Heat', score: '118 - 84' },
                        { date: '29 Abr 2025', home: 'Heat', away: 'Celtics', score: '88 - 102' }
                    ],
                    homeStreak: ['V', 'V', 'V', 'D', 'V'],
                    awayStreak: ['D', 'V', 'D', 'V', 'D'],
                    homeWinProb: 65, drawProb: 0, awayWinProb: 35,
                    avgGoals: 215.0, bttsProb: 88
                },
                odds: {
                    '1X2': { '1': 1.45, 'X': 16.0, '2': 2.85 },
                    'over_under': { 'Over 218.5': 1.90, 'Under 218.5': 1.90 },
                    'btts': { 'Handicap -6.5': 1.90, 'Handicap +6.5': 1.90 }
                }
            },

            // DÍA 7
            {
                id: 'm12',
                dayOffset: 7,
                sport: 'esports',
                league: '🎮 League of Legends - Worlds',
                isLive: false,
                startTime: '06:00',
                home: 'T1 Telecom',
                away: 'Gen.G Esports',
                homeScore: 0,
                awayScore: 0,
                h2h: {
                    homeWins: 12, draws: 0, awayWins: 14,
                    lastMatches: [
                        { date: '27 Oct 2025', home: 'T1', away: 'Gen.G', score: '3 - 1' },
                        { date: '08 Sep 2025', home: 'Gen.G', away: 'T1', score: '3 - 2' }
                    ],
                    homeStreak: ['V', 'V', 'V', 'V', 'D'],
                    awayStreak: ['V', 'V', 'D', 'V', 'V'],
                    homeWinProb: 50, drawProb: 0, awayWinProb: 50,
                    avgGoals: 31.0, bttsProb: 95
                },
                odds: {
                    '1X2': { '1': 1.85, 'X': 22.0, '2': 1.90 },
                    'over_under': { 'Over 28.5 Kills': 1.80, 'Under 28.5 Kills': 1.95 },
                    'btts': { 'Dragon Alma T1': 1.85, 'Dragon Alma GenG': 1.85 }
                }
            }
        ];

        let matches = JSON.parse(JSON.stringify(INITIAL_MATCHES));

        /* =========================================================================
           3. RENDER 8-DAY CALENDAR STRIP
           ========================================================================= */
        function renderDaysBar() {
            const container = document.getElementById('daysBarContainer');
            if (!container) return;

            let html = `
                <button onclick="filterDay('all')" class="day-tab px-3 py-1.5 rounded-xl border border-wpBorder transition whitespace-nowrap ${currentDayFilter === 'all' ? 'active' : 'bg-wpCard text-slate-300 hover:bg-wpCardHover'}">
                    📅 Todos (8 Días)
                </button>
            `;

            next8Days.forEach(day => {
                const matchCountForDay = matches.filter(m => m.dayOffset === day.offset).length;
                const isActive = currentDayFilter === day.offset;
                html += `
                    <button onclick="filterDay(${day.offset})" class="day-tab px-3.5 py-1.5 rounded-xl border border-wpBorder transition whitespace-nowrap flex items-center gap-1.5 ${isActive ? 'active' : 'bg-wpCard text-slate-300 hover:bg-wpCardHover'}">
                        <span class="font-bold font-outfit">${day.dayName}</span>
                        <span class="text-[10px] opacity-75">${day.dateString}</span>
                        ${matchCountForDay > 0 ? `<span class="text-[10px] px-1.5 py-0.2 rounded-full ${isActive ? 'bg-wpDark text-wpGreen' : 'bg-slate-700 text-slate-300'} font-black">${matchCountForDay}</span>` : ''}
                    </button>
                `;
            });

            container.innerHTML = html;
        }

        function filterDay(offset) {
            playSound('click');
            currentDayFilter = offset;
            renderDaysBar();
            
            const badge = document.getElementById('activeDateBadge');
            if (badge) {
                if (offset === 'all') {
                    badge.innerText = 'Próximos 8 Días';
                } else {
                    const found = next8Days.find(d => d.offset === offset);
                    badge.innerText = found ? `${found.dayName} (${found.dateString})` : 'Día ' + offset;
                }
            }

            renderMatches();
        }

        /* =========================================================================
           4. FORMATTING UTILITIES & BALANCE
           ========================================================================= */
        function formatCOP(amount) {
            return '$' + Math.round(amount).toLocaleString('es-CO') + ' COP';
        }

        function updateBalanceDisplay() {
            document.getElementById('userBalanceDisplay').innerText = formatCOP(balance);
            localStorage.setItem('wp_balance', balance);
        }

        /* =========================================================================
           5. RENDER MATCHES FEED
           ========================================================================= */
        function renderMatches() {
            const container = document.getElementById('matchesContainer');
            if (!container) return;

            let filtered = matches.filter(m => {
                // Filter by sport
                if (currentSportFilter === 'live' && !m.isLive) return false;
                if (currentSportFilter !== 'all' && currentSportFilter !== 'live' && m.sport !== currentSportFilter) return false;
                
                // Filter by day
                if (currentDayFilter !== 'all' && m.dayOffset !== currentDayFilter) return false;

                return true;
            });

            // Update live count badge
            const liveCount = matches.filter(m => m.isLive).length;
            const liveBadge = document.getElementById('liveMatchBadgeCount');
            if (liveBadge) liveBadge.innerText = liveCount;

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="bg-wpDark2 border border-wpBorder rounded-2xl p-12 text-center">
                        <span class="text-4xl mb-3 block">⚽</span>
                        <h4 class="font-bebas text-2xl text-white">NO HAY EVENTOS PARA ESTE DÍA / FILTRO</h4>
                        <p class="text-xs text-slate-400 mb-4 font-light">Prueba seleccionando otro día en el calendario superior de 8 días.</p>
                        <button onclick="filterDay('all')" class="px-4 py-2 bg-wpGreen text-wpDark font-black font-outfit text-xs rounded-xl">
                            Ver Todos los 8 Días
                        </button>
                    </div>
                `;
                return;
            }

            let html = '';
            filtered.forEach(m => {
                const isSelected1 = isBetSelected(m.id, '1X2', '1');
                const isSelectedX = isBetSelected(m.id, '1X2', 'X');
                const isSelected2 = isBetSelected(m.id, '1X2', '2');

                const dayObj = next8Days.find(d => d.offset === m.dayOffset);
                const dayLabel = dayObj ? `${dayObj.dayName} · ${dayObj.dateString}` : '';

                html += `
                    <div class="bg-wpDark2 hover:border-slate-700/80 border border-wpBorder rounded-3xl p-4 sm:p-5 transition shadow-lg relative overflow-hidden" id="match-card-${m.id}">
                        
                        <!-- Top League, Date & Live Info -->
                        <div class="flex items-center justify-between gap-2 mb-3 pb-2.5 border-b border-wpBorder/60">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-300 font-outfit">${m.league}</span>
                                <span class="text-[10px] text-slate-500 font-semibold bg-wpCard px-2 py-0.5 rounded-full border border-wpBorder/50 hidden sm:inline">
                                    📅 ${dayLabel}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <!-- H2H Stats Button -->
                                <button onclick="openStatsModal('${m.id}')" class="text-xs font-bold text-wpGreen hover:bg-wpGreen/20 bg-wpGreen/10 border border-wpGreen/30 px-2.5 py-0.5 rounded-full font-outfit transition flex items-center gap-1">
                                    <span>📊</span> <span class="hidden sm:inline">Ver H2H & Estadísticas</span><span class="sm:hidden">H2H</span>
                                </button>

                                ${m.isLive ? `
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-wpRed/15 border border-wpRed/30 text-wpRed text-[11px] font-black font-outfit">
                                        <span class="w-2 h-2 rounded-full bg-wpRed pulse-live"></span>
                                        VIVO ${m.minute}'
                                    </span>
                                ` : `
                                    <span class="text-[11px] font-semibold text-slate-400 font-outfit bg-wpCard px-2.5 py-0.5 rounded-full border border-wpBorder">
                                        ⏱️ ${m.startTime}
                                    </span>
                                `}
                            </div>
                        </div>

                        <!-- Teams & Clickable Banner for Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center mb-4 cursor-pointer group" onclick="openStatsModal('${m.id}')" title="Haz clic para ver estadísticas e historial de enfrentamientos">
                            
                            <!-- Team 1 & Score -->
                            <div class="md:col-span-5 flex items-center justify-between md:justify-start gap-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-wpCard border border-wpBorder group-hover:border-wpGreen flex items-center justify-center text-sm font-black text-white transition">
                                        ${m.home.charAt(0)}
                                    </div>
                                    <div>
                                        <span class="font-outfit font-extrabold text-sm sm:text-base text-white group-hover:text-wpGreen transition truncate max-w-[180px] sm:max-w-[220px] block">
                                            ${m.home}
                                        </span>
                                        ${m.h2h ? `
                                            <div class="flex items-center gap-1 mt-0.5">
                                                ${m.h2h.homeStreak.map(st => `
                                                    <span class="text-[9px] font-black px-1 rounded ${st === 'V' ? 'bg-emerald-500/20 text-emerald-400' : st === 'E' ? 'bg-amber-500/20 text-amber-400' : 'bg-rose-500/20 text-rose-400'}">${st}</span>
                                                `).join('')}
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                                <span class="font-bebas text-2xl sm:text-3xl text-wpGreen px-2.5 py-0.5 rounded-lg bg-wpCard border border-wpBorder md:ml-auto match-score-${m.id}-home">
                                    ${m.homeScore}
                                </span>
                            </div>

                            <!-- VS / Separator -->
                            <div class="hidden md:flex md:col-span-2 flex-col items-center justify-center text-xs font-black text-slate-500 font-bebas tracking-widest">
                                <span>VS</span>
                                <span class="text-[9px] text-slate-400 font-outfit">H2H 📊</span>
                            </div>

                            <!-- Team 2 & Score -->
                            <div class="md:col-span-5 flex items-center justify-between md:justify-end gap-3">
                                <span class="font-bebas text-2xl sm:text-3xl text-wpGreen px-2.5 py-0.5 rounded-lg bg-wpCard border border-wpBorder md:mr-auto match-score-${m.id}-away">
                                    ${m.awayScore}
                                </span>
                                <div class="flex items-center gap-2.5 text-right">
                                    <div>
                                        <span class="font-outfit font-extrabold text-sm sm:text-base text-white group-hover:text-wpGreen transition truncate max-w-[180px] sm:max-w-[220px] block">
                                            ${m.away}
                                        </span>
                                        ${m.h2h ? `
                                            <div class="flex items-center justify-end gap-1 mt-0.5">
                                                ${m.h2h.awayStreak.map(st => `
                                                    <span class="text-[9px] font-black px-1 rounded ${st === 'V' ? 'bg-emerald-500/20 text-emerald-400' : st === 'E' ? 'bg-amber-500/20 text-amber-400' : 'bg-rose-500/20 text-rose-400'}">${st}</span>
                                                `).join('')}
                                            </div>
                                        ` : ''}
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-wpCard border border-wpBorder group-hover:border-wpGreen flex items-center justify-center text-sm font-black text-white transition">
                                        ${m.away.charAt(0)}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 1X2 Main Betting Market Grid -->
                        <div class="grid grid-cols-3 gap-2 sm:gap-3 mb-3">
                            <!-- 1 (Home) -->
                            <button onclick="toggleBet('${m.id}', '1X2', '1', ${m.odds['1X2']['1']}, '${m.home}')" 
                                    class="odd-btn flex items-center justify-between p-2.5 sm:p-3 rounded-2xl bg-wpCard hover:bg-wpCardHover border border-wpBorder text-left ${isSelected1 ? 'selected' : ''}">
                                <div class="truncate mr-1">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block font-outfit">1 (${m.home.split(' ')[0]})</span>
                                </div>
                                <span class="odd-val font-bebas text-lg sm:text-xl text-wpGreen font-black">${m.odds['1X2']['1'].toFixed(2)}</span>
                            </button>

                            <!-- X (Draw) -->
                            <button onclick="toggleBet('${m.id}', '1X2', 'X', ${m.odds['1X2']['X']}, 'Empate')" 
                                    class="odd-btn flex items-center justify-between p-2.5 sm:p-3 rounded-2xl bg-wpCard hover:bg-wpCardHover border border-wpBorder text-left ${isSelectedX ? 'selected' : ''}">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block font-outfit">X (Empate)</span>
                                </div>
                                <span class="odd-val font-bebas text-lg sm:text-xl text-wpGreen font-black">${m.odds['1X2']['X'].toFixed(2)}</span>
                            </button>

                            <!-- 2 (Away) -->
                            <button onclick="toggleBet('${m.id}', '1X2', '2', ${m.odds['1X2']['2']}, '${m.away}')" 
                                    class="odd-btn flex items-center justify-between p-2.5 sm:p-3 rounded-2xl bg-wpCard hover:bg-wpCardHover border border-wpBorder text-left ${isSelected2 ? 'selected' : ''}">
                                <div class="truncate mr-1">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block font-outfit">2 (${m.away.split(' ')[0]})</span>
                                </div>
                                <span class="odd-val font-bebas text-lg sm:text-xl text-wpGreen font-black">${m.odds['1X2']['2'].toFixed(2)}</span>
                            </button>
                        </div>

                        <!-- Secondary Markets (Accordion Toggle) -->
                        <div class="pt-2 border-t border-wpBorder/40 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <button onclick="toggleMoreMarkets('${m.id}')" class="text-slate-400 hover:text-wpGreen font-outfit font-bold flex items-center gap-1 transition">
                                    <span>+ Mercados (Goles, Ambos Anotan)</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transform transition-transform" id="arrow-${m.id}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                            
                            ${m.isLive ? `
                                <button onclick="simulateSingleMatchGoal('${m.id}')" class="text-[11px] font-bold text-wpYellow hover:text-white bg-wpYellow/10 hover:bg-wpYellow/20 border border-wpYellow/30 px-2.5 py-1 rounded-xl font-outfit transition flex items-center gap-1">
                                    <span>⚽</span> Simular Gol
                                </button>
                            ` : ''}
                        </div>

                        <!-- Expandable Extra Markets Container -->
                        <div id="extra-markets-${m.id}" class="hidden pt-3 mt-3 border-t border-wpBorder/40 space-y-3">
                            <div>
                                <span class="text-[11px] font-bold uppercase text-slate-400 font-outfit block mb-1.5">Total de Goles / Puntos (+ / -)</span>
                                <div class="grid grid-cols-2 gap-2">
                                    ${Object.entries(m.odds['over_under']).map(([key, odd]) => {
                                        const isSel = isBetSelected(m.id, 'over_under', key);
                                        return `
                                            <button onclick="toggleBet('${m.id}', 'over_under', '${key}', ${odd}, '${key}')" 
                                                    class="odd-btn flex items-center justify-between p-2 rounded-xl bg-wpCard hover:bg-wpCardHover border border-wpBorder ${isSel ? 'selected' : ''}">
                                                <span class="text-xs text-slate-300 font-outfit">${key}</span>
                                                <span class="odd-val font-bebas text-base text-wpGreen">${odd.toFixed(2)}</span>
                                            </button>
                                        `;
                                    }).join('')}
                                </div>
                            </div>

                            <div>
                                <span class="text-[11px] font-bold uppercase text-slate-400 font-outfit block mb-1.5">Ambos Equipos Anotan / Especiales</span>
                                <div class="grid grid-cols-2 gap-2">
                                    ${Object.entries(m.odds['btts']).map(([key, odd]) => {
                                        const isSel = isBetSelected(m.id, 'btts', key);
                                        return `
                                            <button onclick="toggleBet('${m.id}', 'btts', '${key}', ${odd}, '${key}')" 
                                                    class="odd-btn flex items-center justify-between p-2 rounded-xl bg-wpCard hover:bg-wpCardHover border border-wpBorder ${isSel ? 'selected' : ''}">
                                                <span class="text-xs text-slate-300 font-outfit">${key}</span>
                                                <span class="odd-val font-bebas text-base text-wpGreen">${odd.toFixed(2)}</span>
                                            </button>
                                        `;
                                    }).join('')}
                                </div>
                            </div>
                        </div>

                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function toggleMoreMarkets(matchId) {
            const el = document.getElementById(`extra-markets-${matchId}`);
            const arrow = document.getElementById(`arrow-${matchId}`);
            if (el) {
                el.classList.toggle('hidden');
                if (arrow) arrow.classList.toggle('rotate-180');
            }
        }

        /* =========================================================================
           6. H2H & STATISTICS MODAL
           ========================================================================= */
        function openStatsModal(matchId) {
            playSound('click');
            const match = matches.find(m => m.id === matchId);
            if (!match) return;

            const modal = document.getElementById('statsModal');
            const content = document.getElementById('statsModalContent');
            const leagueLabel = document.getElementById('statsModalLeague');
            if (!modal || !content) return;

            if (leagueLabel) leagueLabel.innerText = `${match.league} · ${match.startTime || 'En Vivo'}`;

            const h2h = match.h2h || {
                homeWins: 5, draws: 3, awayWins: 4,
                lastMatches: [
                    { date: 'Último encuentro', home: match.home, away: match.away, score: '2 - 1' }
                ],
                homeStreak: ['V', 'E', 'V', 'D', 'V'],
                awayStreak: ['V', 'D', 'V', 'E', 'D'],
                homeWinProb: 45, drawProb: 25, awayWinProb: 30,
                avgGoals: 2.5, bttsProb: 55
            };

            const totalH2H = h2h.homeWins + h2h.draws + h2h.awayWins || 1;
            const homeH2HPercent = Math.round((h2h.homeWins / totalH2H) * 100);
            const drawH2HPercent = Math.round((h2h.draws / totalH2H) * 100);
            const awayH2HPercent = Math.round((h2h.awayWins / totalH2H) * 100);

            content.innerHTML = `
                <!-- Versus Header & Win Probabilities -->
                <div class="bg-wpCard rounded-2xl p-4 sm:p-5 border border-wpBorder">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-wpDark border border-wpBorder flex items-center justify-center font-black text-white text-lg">
                                ${match.home.charAt(0)}
                            </div>
                            <div>
                                <h4 class="font-outfit font-extrabold text-white text-base sm:text-lg">${match.home}</h4>
                                <div class="flex items-center gap-1 mt-1">
                                    <span class="text-[10px] text-slate-400 font-bold mr-1">Racha:</span>
                                    ${h2h.homeStreak.map(st => `
                                        <span class="w-4 h-4 rounded flex items-center justify-center text-[9px] font-black ${st === 'V' ? 'bg-emerald-500 text-wpDark' : st === 'E' ? 'bg-amber-400 text-wpDark' : 'bg-rose-500 text-white'}">${st}</span>
                                    `).join('')}
                                </div>
                            </div>
                        </div>

                        <div class="font-bebas text-2xl text-slate-500">VS</div>

                        <div class="flex items-center justify-end gap-3 text-right">
                            <div>
                                <h4 class="font-outfit font-extrabold text-white text-base sm:text-lg">${match.away}</h4>
                                <div class="flex items-center justify-end gap-1 mt-1">
                                    <span class="text-[10px] text-slate-400 font-bold mr-1">Racha:</span>
                                    ${h2h.awayStreak.map(st => `
                                        <span class="w-4 h-4 rounded flex items-center justify-center text-[9px] font-black ${st === 'V' ? 'bg-emerald-500 text-wpDark' : st === 'E' ? 'bg-amber-400 text-wpDark' : 'bg-rose-500 text-white'}">${st}</span>
                                    `).join('')}
                                </div>
                            </div>
                            <div class="w-10 h-10 rounded-2xl bg-wpDark border border-wpBorder flex items-center justify-center font-black text-white text-lg">
                                ${match.away.charAt(0)}
                            </div>
                        </div>
                    </div>

                    <!-- Win Probability Visual Bar -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-outfit font-bold">
                            <span class="text-emerald-400">${match.home.split(' ')[0]}: ${h2h.homeWinProb}%</span>
                            <span class="text-amber-400">Empate: ${h2h.drawProb}%</span>
                            <span class="text-cyan-400">${match.away.split(' ')[0]}: ${h2h.awayWinProb}%</span>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-wpDark flex overflow-hidden">
                            <div style="width: ${h2h.homeWinProb}%" class="bg-emerald-500 h-full"></div>
                            <div style="width: ${h2h.drawProb}%" class="bg-amber-400 h-full"></div>
                            <div style="width: ${h2h.awayWinProb}%" class="bg-cyan-400 h-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Metrics Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="bg-wpCard rounded-2xl p-3 border border-wpBorder text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 font-outfit block">Prom. Goles</span>
                        <span class="font-bebas text-2xl text-wpGreen font-black">${h2h.avgGoals || '2.5'}</span>
                    </div>
                    <div class="bg-wpCard rounded-2xl p-3 border border-wpBorder text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 font-outfit block">Ambos Marcan</span>
                        <span class="font-bebas text-2xl text-wpYellow font-black">${h2h.bttsProb || '50'}%</span>
                    </div>
                    <div class="bg-wpCard rounded-2xl p-3 border border-wpBorder text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 font-outfit block">Victorias ${match.home.split(' ')[0]}</span>
                        <span class="font-bebas text-2xl text-emerald-400 font-black">${h2h.homeWins}</span>
                    </div>
                    <div class="bg-wpCard rounded-2xl p-3 border border-wpBorder text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 font-outfit block">Victorias ${match.away.split(' ')[0]}</span>
                        <span class="font-bebas text-2xl text-cyan-400 font-black">${h2h.awayWins}</span>
                    </div>
                </div>

                <!-- Historial Cara a Cara (Direct Matches) -->
                <div>
                    <h5 class="font-bebas text-xl text-white tracking-wide mb-3 flex items-center gap-2">
                        <span>⚔️</span> HISTORIAL DIRECTO DE ENFRENTAMIENTOS (ÚLTIMOS JUEGOS)
                    </h5>
                    <div class="space-y-2">
                        ${h2h.lastMatches.map(lm => `
                            <div class="bg-wpCard border border-wpBorder rounded-2xl p-3 flex items-center justify-between text-xs">
                                <span class="text-slate-400 font-outfit text-[11px]">${lm.date}</span>
                                <div class="flex items-center gap-2 font-bold">
                                    <span class="${lm.score.split('-')[0] > lm.score.split('-')[1] ? 'text-white' : 'text-slate-400'}">${lm.home}</span>
                                    <span class="font-bebas text-base px-2 py-0.5 bg-wpDark rounded-lg text-wpGreen border border-wpBorder">${lm.score}</span>
                                    <span class="${lm.score.split('-')[1] > lm.score.split('-')[0] ? 'text-white' : 'text-slate-400'}">${lm.away}</span>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>

                <!-- Quick Bet Section inside Modal -->
                <div class="bg-wpCard/70 border border-wpBorder rounded-2xl p-4">
                    <span class="text-xs font-bold text-slate-300 font-outfit block mb-2">⚡ Pronosticar Cuota de este Partido:</span>
                    <div class="grid grid-cols-3 gap-2">
                        <button onclick="toggleBet('${match.id}', '1X2', '1', ${match.odds['1X2']['1']}, '${match.home}'); closeStatsModal();" 
                                class="p-2 rounded-xl bg-wpDark hover:bg-wpGreen hover:text-wpDark border border-wpBorder text-xs font-bold font-outfit transition text-center">
                            1 (${match.odds['1X2']['1'].toFixed(2)})
                        </button>
                        <button onclick="toggleBet('${match.id}', '1X2', 'X', ${match.odds['1X2']['X']}, 'Empate'); closeStatsModal();" 
                                class="p-2 rounded-xl bg-wpDark hover:bg-wpGreen hover:text-wpDark border border-wpBorder text-xs font-bold font-outfit transition text-center">
                            X (${match.odds['1X2']['X'].toFixed(2)})
                        </button>
                        <button onclick="toggleBet('${match.id}', '1X2', '2', ${match.odds['1X2']['2']}, '${match.away}'); closeStatsModal();" 
                                class="p-2 rounded-xl bg-wpDark hover:bg-wpGreen hover:text-wpDark border border-wpBorder text-xs font-bold font-outfit transition text-center">
                            2 (${match.odds['1X2']['2'].toFixed(2)})
                        </button>
                    </div>
                </div>
            `;

            modal.classList.remove('hidden');
        }

        function closeStatsModal() {
            document.getElementById('statsModal').classList.add('hidden');
        }

        /* =========================================================================
           7. BET SELECTION & BETSLIP COMPONENT
           ========================================================================= */
        function isBetSelected(matchId, marketId, pick) {
            return selectedBets.some(b => b.matchId === matchId && b.marketId === marketId && b.pick === pick);
        }

        function toggleBet(matchId, marketId, pick, odds, label) {
            playSound('click');
            const match = matches.find(m => m.id === matchId);
            if (!match) return;

            const existingIndex = selectedBets.findIndex(b => b.matchId === matchId && b.marketId === marketId && b.pick === pick);

            if (existingIndex > -1) {
                selectedBets.splice(existingIndex, 1);
            } else {
                const sameMarketIndex = selectedBets.findIndex(b => b.matchId === matchId && b.marketId === marketId);
                if (sameMarketIndex > -1) {
                    selectedBets.splice(sameMarketIndex, 1);
                }

                selectedBets.push({
                    matchId: match.id,
                    marketId: marketId,
                    pick: pick,
                    label: label,
                    odds: parseFloat(odds),
                    home: match.home,
                    away: match.away,
                    league: match.league,
                    sport: match.sport
                });
            }

            renderMatches();
            renderBetSlip();
        }

        function removeBet(index) {
            playSound('click');
            selectedBets.splice(index, 1);
            renderMatches();
            renderBetSlip();
        }

        function clearAllBets() {
            playSound('click');
            selectedBets = [];
            renderMatches();
            renderBetSlip();
        }

        function setBetMode(mode) {
            playSound('click');
            betMode = mode;
            renderBetSlip();
        }

        function calculateCombinedOdds() {
            if (selectedBets.length === 0) return 1.0;
            let total = selectedBets.reduce((acc, bet) => acc * bet.odds, 1.0);
            
            let bonusMultiplier = 1.0;
            if (selectedBets.length === 2) bonusMultiplier = 1.03;
            else if (selectedBets.length === 3) bonusMultiplier = 1.05;
            else if (selectedBets.length === 4) bonusMultiplier = 1.10;
            else if (selectedBets.length >= 5) bonusMultiplier = 1.15;

            return total * bonusMultiplier;
        }

        function getParlayBonusPercent() {
            if (selectedBets.length === 2) return 3;
            if (selectedBets.length === 3) return 5;
            if (selectedBets.length === 4) return 10;
            if (selectedBets.length >= 5) return 15;
            return 0;
        }

        let currentStakeInput = 10000;

        function updateStake(amount) {
            currentStakeInput = Math.max(1000, parseInt(amount) || 0);
            updatePayoutCalculations();
        }

        function addStakeAmount(add) {
            playSound('click');
            if (add === 'max') {
                currentStakeInput = balance;
            } else {
                currentStakeInput += add;
            }
            const input = document.getElementById('stakeAmountInput');
            if (input) input.value = currentStakeInput;
            const mobileInput = document.getElementById('mobileStakeAmountInput');
            if (mobileInput) mobileInput.value = currentStakeInput;
            updatePayoutCalculations();
        }

        function updatePayoutCalculations() {
            const combinedOdds = calculateCombinedOdds();
            const bonus = getParlayBonusPercent();

            let potentialPayout = 0;
            if (betMode === 'parlay') {
                potentialPayout = currentStakeInput * combinedOdds;
            } else {
                potentialPayout = selectedBets.reduce((acc, b) => acc + (currentStakeInput * b.odds), 0);
            }

            const payoutEls = document.querySelectorAll('.potentialPayoutDisplay');
            payoutEls.forEach(el => el.innerText = formatCOP(potentialPayout));

            const oddsEls = document.querySelectorAll('.combinedOddsDisplay');
            oddsEls.forEach(el => el.innerText = combinedOdds.toFixed(2) + 'x');

            const bonusEls = document.querySelectorAll('.parlayBonusDisplay');
            bonusEls.forEach(el => {
                if (bonus > 0 && betMode === 'parlay') {
                    el.innerText = `🔥 +${bonus}% Bonificador Parlay Activo`;
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            });
        }

        function renderBetSlip() {
            const count = selectedBets.length;
            
            document.getElementById('mobileSlipCount').innerText = count;
            document.getElementById('mobileDrawerCountBadge').innerText = count;

            const desktopContainer = document.getElementById('desktopBetslipContainer');
            const mobileContainer = document.getElementById('mobileBetslipContainer');

            const combinedOdds = calculateCombinedOdds();
            const bonus = getParlayBonusPercent();

            const betslipHTML = `
                <div class="p-4 sm:p-5 flex flex-col h-full">
                    
                    <!-- Header with Mode Selector -->
                    <div class="flex items-center justify-between pb-4 border-b border-wpBorder">
                        <div class="flex items-center gap-2">
                            <span class="font-bebas text-2xl text-white tracking-wide">BOLETO</span>
                            <span class="px-2 py-0.5 bg-wpGreen text-wpDark font-black text-xs rounded-full font-outfit">${count}</span>
                        </div>
                        ${count > 0 ? `
                            <button onclick="clearAllBets()" class="text-[11px] text-slate-400 hover:text-rose-400 font-outfit transition">
                                Vaciar
                            </button>
                        ` : ''}
                    </div>

                    <!-- Single vs Parlay Tabs -->
                    <div class="grid grid-cols-2 gap-2 my-3 p-1 bg-wpCard rounded-2xl border border-wpBorder">
                        <button onclick="setBetMode('single')" class="py-2 text-xs font-black font-outfit rounded-xl transition ${betMode === 'single' ? 'bg-wpGreen text-wpDark shadow' : 'text-slate-400 hover:text-white'}">
                            Sencilla
                        </button>
                        <button onclick="setBetMode('parlay')" class="py-2 text-xs font-black font-outfit rounded-xl transition ${betMode === 'parlay' ? 'bg-wpGreen text-wpDark shadow' : 'text-slate-400 hover:text-white'}">
                            Combinada (Parlay)
                        </button>
                    </div>

                    ${count === 0 ? `
                        <!-- Empty State -->
                        <div class="py-12 text-center text-slate-400 flex flex-col items-center justify-center">
                            <div class="w-14 h-14 rounded-2xl bg-wpCard border border-wpBorder flex items-center justify-center text-2xl mb-3">
                                🎟️
                            </div>
                            <h5 class="font-bebas text-xl text-slate-300">BOLETO VACÍO</h5>
                            <p class="text-xs text-slate-500 max-w-[200px]">Haz clic en cualquier cuota o estadística de los partidos para agregar pronósticos.</p>
                        </div>
                    ` : `
                        <!-- Selected Bets List -->
                        <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1 mb-4">
                            ${selectedBets.map((b, idx) => `
                                <div class="bg-wpCard border border-wpBorder hover:border-slate-600 rounded-2xl p-3 relative group transition">
                                    <div class="flex items-start justify-between gap-2 mb-1">
                                        <span class="text-[10px] font-bold text-wpGreen uppercase font-outfit truncate max-w-[170px]">${b.league}</span>
                                        <button onclick="removeBet(${idx})" class="text-slate-500 hover:text-rose-400 text-xs transition">✕</button>
                                    </div>
                                    <div class="text-xs font-bold text-white truncate mb-1">${b.home} vs ${b.away}</div>
                                    <div class="flex items-center justify-between text-xs pt-1 border-t border-wpBorder/40">
                                        <span class="text-slate-300 font-semibold">${b.label}</span>
                                        <span class="font-bebas text-base text-wpGreen font-black">${b.odds.toFixed(2)}</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>

                        <!-- Multiplier & Bonus Box -->
                        <div class="bg-wpCard/70 border border-wpBorder rounded-2xl p-3 mb-4 space-y-1.5">
                            <div class="flex items-center justify-between text-xs font-outfit">
                                <span class="text-slate-400">Cuota Total:</span>
                                <span class="font-bebas text-xl text-wpGreen combinedOddsDisplay">${combinedOdds.toFixed(2)}x</span>
                            </div>
                            <div class="text-[11px] font-bold text-wpYellow parlayBonusDisplay ${bonus > 0 && betMode === 'parlay' ? '' : 'hidden'}">
                                🔥 +${bonus}% Bonificador Parlay Activo
                            </div>
                        </div>

                        <!-- Stake Input -->
                        <div class="mb-4">
                            <label class="text-[10px] uppercase font-bold text-slate-400 font-outfit block mb-1.5">Monto de la Apuesta ($ COP)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">$</span>
                                <input type="number" id="stakeAmountInput" value="${currentStakeInput}" min="1000" step="1000" oninput="updateStake(this.value)" 
                                       class="w-full bg-wpCard border border-wpBorder focus:border-wpGreen text-white font-bebas text-xl pl-8 pr-3 py-2 rounded-2xl outline-none transition">
                            </div>
                            <!-- Quick Stake Amount Buttons -->
                            <div class="grid grid-cols-4 gap-1.5 mt-2">
                                <button onclick="addStakeAmount(5000)" class="py-1 bg-wpCard hover:bg-wpCardHover border border-wpBorder rounded-xl text-[10px] font-bold text-slate-300 transition">+5K</button>
                                <button onclick="addStakeAmount(10000)" class="py-1 bg-wpCard hover:bg-wpCardHover border border-wpBorder rounded-xl text-[10px] font-bold text-slate-300 transition">+10K</button>
                                <button onclick="addStakeAmount(50000)" class="py-1 bg-wpCard hover:bg-wpCardHover border border-wpBorder rounded-xl text-[10px] font-bold text-slate-300 transition">+50K</button>
                                <button onclick="addStakeAmount('max')" class="py-1 bg-wpCard hover:bg-wpCardHover border border-wpBorder rounded-xl text-[10px] font-bold text-wpYellow transition">TODO</button>
                            </div>
                        </div>

                        <!-- Potential Gain Box -->
                        <div class="p-3.5 rounded-2xl bg-gradient-to-r from-wpGreen/15 via-wpGreen/10 to-transparent border border-wpGreen/30 mb-4">
                            <span class="text-[10px] uppercase font-bold text-slate-400 font-outfit block">Ganancia Potencial</span>
                            <span class="font-bebas text-2xl text-wpGreen font-black potentialPayoutDisplay tracking-wide">
                                ${formatCOP(betMode === 'parlay' ? currentStakeInput * combinedOdds : selectedBets.reduce((a, b) => a + (currentStakeInput * b.odds), 0))}
                            </span>
                        </div>

                        <!-- Place Bet Action Button -->
                        <button onclick="placeBet()" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-wpGreen to-wpGreenDark text-wpDark font-outfit font-black text-base uppercase tracking-wider hover:brightness-110 active:scale-95 transition glow-green shadow-xl flex items-center justify-center gap-2">
                            <span>⚡</span>
                            <span>Apostar Ahora</span>
                        </button>
                    `}

                </div>
            `;

            if (desktopContainer) desktopContainer.innerHTML = betslipHTML;
            if (mobileContainer) mobileContainer.innerHTML = betslipHTML;
        }

        function toggleMobileBetslip() {
            const drawer = document.getElementById('betslipDrawer');
            if (drawer) drawer.classList.toggle('open');
        }

        /* =========================================================================
           8. PLACE BET & RECORD IN HISTORY
           ========================================================================= */
        function placeBet() {
            if (selectedBets.length === 0) return;

            if (currentStakeInput <= 0) {
                alert('Ingresa un monto válido para apostar.');
                return;
            }

            const totalCost = betMode === 'single' ? (currentStakeInput * selectedBets.length) : currentStakeInput;

            if (balance < totalCost) {
                playSound('click');
                rechargeBalance();
                return;
            }

            balance -= totalCost;
            updateBalanceDisplay();
            playSound('bet_placed');

            const combinedOdds = calculateCombinedOdds();
            const potentialWin = betMode === 'parlay' 
                ? (totalCost * combinedOdds) 
                : selectedBets.reduce((a, b) => a + (currentStakeInput * b.odds), 0);

            const ticketId = 'ARL-' + Math.floor(100000 + Math.random() * 900000);
            const ticket = {
                id: ticketId,
                date: new Date().toLocaleString('es-CO', { dateStyle: 'short', timeStyle: 'short' }),
                mode: betMode,
                selections: JSON.parse(JSON.stringify(selectedBets)),
                totalStake: totalCost,
                odds: combinedOdds,
                potentialWin: potentialWin,
                status: 'pending',
                wonAmount: 0
            };

            betHistory.unshift(ticket);
            localStorage.setItem('wp_history', JSON.stringify(betHistory));
            updateHistoryCountBadge();

            selectedBets = [];
            renderMatches();
            renderBetSlip();

            const drawer = document.getElementById('betslipDrawer');
            if (drawer) drawer.classList.remove('open');

            showSimToast(`🎉 ¡Boleto #${ticketId} colocado exitosamente por ${formatCOP(totalCost)}!`);
            
            setTimeout(() => {
                resolveTicketSimulation(ticket.id);
            }, 1200);
        }

        /* =========================================================================
           9. LIVE MATCH SIMULATOR & TICKET RESOLUTION
           ========================================================================= */
        function simulateAllLiveTick() {
            playSound('goal');
            matches.forEach(m => {
                if (m.isLive) {
                    if (typeof m.minute === 'number') {
                        m.minute = Math.min(90, m.minute + Math.floor(Math.random() * 4) + 1);
                    }
                    if (Math.random() > 0.45) {
                        if (Math.random() > 0.5) {
                            m.homeScore++;
                            triggerGoalToast(m.home, m.homeScore, m.away, m.awayScore);
                        } else {
                            m.awayScore++;
                            triggerGoalToast(m.away, m.awayScore, m.home, m.homeScore);
                        }
                    }
                }
            });

            renderMatches();
        }

        function simulateSingleMatchGoal(matchId) {
            playSound('goal');
            const match = matches.find(m => m.id === matchId);
            if (!match) return;

            if (Math.random() > 0.5) {
                match.homeScore++;
                triggerGoalToast(match.home, match.homeScore, match.away, match.awayScore);
            } else {
                match.awayScore++;
                triggerGoalToast(match.away, match.awayScore, match.home, match.homeScore);
            }

            renderMatches();
        }

        function triggerGoalToast(scoringTeam, s1, otherTeam, s2) {
            const toast = document.getElementById('simToast');
            const msg = document.getElementById('simToastMessage');
            if (toast && msg) {
                msg.innerText = `¡Gol de ${scoringTeam}! Marcador actual: ${s1} - ${s2}`;
                toast.classList.remove('hidden');
                toast.classList.remove('translate-y-4');
                setTimeout(() => {
                    toast.classList.add('translate-y-4');
                    setTimeout(() => toast.classList.add('hidden'), 300);
                }, 3500);
            }
        }

        function resolveTicketSimulation(ticketId) {
            const ticket = betHistory.find(t => t.id === ticketId);
            if (!ticket || ticket.status !== 'pending') return;

            const won = Math.random() > 0.35;

            if (won) {
                ticket.status = 'won';
                ticket.wonAmount = ticket.potentialWin;
                balance += ticket.wonAmount;
                updateBalanceDisplay();
                playSound('win');

                if (typeof confetti === 'function') {
                    confetti({
                        particleCount: 120,
                        spread: 80,
                        origin: { y: 0.6 }
                    });
                }

                showSimToast(`🏆 ¡BOLETO GANADOR #${ticket.id}! Cobraste ${formatCOP(ticket.wonAmount)}`);
            } else {
                ticket.status = 'lost';
                ticket.wonAmount = 0;
                showSimToast(`❌ Boleto #${ticket.id} no acertó los resultados esta vez.`);
            }

            localStorage.setItem('wp_history', JSON.stringify(betHistory));
            updateHistoryCountBadge();
        }

        function showSimToast(message) {
            const toast = document.getElementById('simToast');
            const msg = document.getElementById('simToastMessage');
            if (toast && msg) {
                msg.innerText = message;
                toast.classList.remove('hidden');
                toast.classList.remove('translate-y-4');
                setTimeout(() => {
                    toast.classList.add('translate-y-4');
                    setTimeout(() => toast.classList.add('hidden'), 300);
                }, 4000);
            }
        }

        function quickAddCombo() {
            playSound('click');
            selectedBets = [
                { matchId: 'm1', marketId: '1X2', pick: '1', label: 'Atlético Nacional', odds: 1.62, home: 'Atlético Nacional', away: 'Millonarios FC', league: '🇨🇴 Liga BetPlay', sport: 'futbol' },
                { matchId: 'm2', marketId: '1X2', pick: '1', label: 'Real Madrid', odds: 2.40, home: 'Real Madrid', away: 'Manchester City', league: '🇪🇺 Champions League', sport: 'futbol' },
                { matchId: 'm5', marketId: '1X2', pick: '1', label: 'Arsenal FC', odds: 1.75, home: 'Arsenal FC', away: 'Chelsea FC', league: '🏴󠁧󠁢󠁥󠁮󠁧󠁿 Premier League', sport: 'futbol' },
                { matchId: 'm4', marketId: '1X2', pick: '1', label: 'LA Lakers', odds: 1.80, home: 'LA Lakers', away: 'Golden State', league: '🇺🇸 NBA', sport: 'baloncesto' },
            ];
            betMode = 'parlay';
            renderMatches();
            renderBetSlip();
            showSimToast('🚀 ¡Parlay de 4 selecciones cargado con +10% de bonificación acumulada!');
        }

        /* =========================================================================
           10. RECHARGE MODAL & HISTORIAL
           ========================================================================= */
        function rechargeBalance() {
            playSound('click');
            document.getElementById('rechargeModal').classList.remove('hidden');
        }

        function closeRechargeModal() {
            document.getElementById('rechargeModal').classList.add('hidden');
        }

        function applyRecharge(amount) {
            balance += amount;
            updateBalanceDisplay();
            playSound('bet_placed');
            closeRechargeModal();
            showSimToast(`💰 ¡Recarga demo de ${formatCOP(amount)} aplicada exitosamente!`);
        }

        function resetAllMatches() {
            playSound('click');
            matches = JSON.parse(JSON.stringify(INITIAL_MATCHES));
            renderMatches();
            showSimToast('🔄 Todos los marcadores y cuotas han sido reiniciados.');
        }

        function updateHistoryCountBadge() {
            const badge = document.getElementById('ticketCountBadge');
            if (badge) badge.innerText = betHistory.length;
        }

        function switchView(view) {
            if (view === 'history') {
                openHistoryModal();
            }
        }

        function openHistoryModal() {
            playSound('click');
            const modal = document.getElementById('historyModal');
            const list = document.getElementById('historyListContainer');
            if (!modal || !list) return;

            if (betHistory.length === 0) {
                list.innerHTML = `
                    <div class="py-12 text-center text-slate-400">
                        <span class="text-4xl mb-2 block">📭</span>
                        <h4 class="font-bebas text-2xl text-white">NO HAY BOLETOS EN EL HISTORIAL</h4>
                        <p class="text-xs text-slate-500">Realiza tu primera apuesta en el simulador para registrarla aquí.</p>
                    </div>
                `;
            } else {
                list.innerHTML = betHistory.map(ticket => `
                    <div class="bg-wpCard border ${ticket.status === 'won' ? 'border-wpGreen/60' : ticket.status === 'lost' ? 'border-rose-500/40' : 'border-wpBorder'} rounded-2xl p-4 transition shadow-md">
                        <div class="flex items-center justify-between gap-2 mb-2 pb-2 border-b border-wpBorder/60">
                            <div class="flex items-center gap-2">
                                <span class="font-bebas text-lg text-white">#${ticket.id}</span>
                                <span class="text-[10px] uppercase font-black px-2 py-0.5 rounded-full font-outfit ${ticket.mode === 'parlay' ? 'bg-wpBlue/20 text-wpBlue' : 'bg-slate-700 text-slate-300'}">
                                    ${ticket.mode === 'parlay' ? 'Combinada' : 'Sencilla'}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-slate-400 font-outfit">${ticket.date}</span>
                                ${ticket.status === 'won' ? `
                                    <span class="px-2.5 py-0.5 rounded-full bg-wpGreen/20 text-wpGreen font-black text-xs font-outfit">GANADA</span>
                                ` : ticket.status === 'lost' ? `
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-500/20 text-rose-400 font-black text-xs font-outfit">PERDIDA</span>
                                ` : `
                                    <span class="px-2.5 py-0.5 rounded-full bg-wpYellow/20 text-wpYellow font-black text-xs font-outfit">PENDIENTE</span>
                                `}
                            </div>
                        </div>

                        <div class="space-y-1 mb-3">
                            ${ticket.selections.map(s => `
                                <div class="flex items-center justify-between text-xs text-slate-300">
                                    <span class="font-semibold">${s.home} vs ${s.away}</span>
                                    <span class="text-wpGreen font-bold font-outfit">${s.label} (${s.odds.toFixed(2)})</span>
                                </div>
                            `).join('')}
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-wpBorder/40 text-xs font-outfit">
                            <div>
                                <span class="text-slate-400">Apostado: </span>
                                <span class="font-bold text-white">${formatCOP(ticket.totalStake)}</span>
                            </div>
                            <div>
                                <span class="text-slate-400">Cuota: </span>
                                <span class="font-bold text-wpGreen font-bebas text-base">${ticket.odds.toFixed(2)}x</span>
                            </div>
                            <div>
                                <span class="text-slate-400">Premio: </span>
                                <span class="font-black ${ticket.status === 'won' ? 'text-wpGreen' : 'text-slate-400'}">
                                    ${ticket.status === 'won' ? formatCOP(ticket.wonAmount) : formatCOP(ticket.potentialWin)}
                                </span>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            modal.classList.remove('hidden');
        }

        function closeHistoryModal() {
            document.getElementById('historyModal').classList.add('hidden');
        }

        function clearHistory() {
            if (confirm('¿Deseas vaciar el historial de boletos?')) {
                betHistory = [];
                localStorage.removeItem('wp_history');
                updateHistoryCountBadge();
                openHistoryModal();
            }
        }

        /* =========================================================================
           11. SPORTS FILTER
           ========================================================================= */
        function filterSport(sport) {
            playSound('click');
            currentSportFilter = sport;

            document.querySelectorAll('.sport-tab').forEach(tab => {
                if (tab.dataset.sport === sport) {
                    tab.classList.remove('bg-wpCard', 'text-slate-200');
                    tab.classList.add('bg-wpGreen', 'text-wpDark');
                } else {
                    tab.classList.remove('bg-wpGreen', 'text-wpDark');
                    tab.classList.add('bg-wpCard', 'text-slate-200');
                }
            });

            const titles = {
                all: '🏆 Partidos Destacados & En Vivo',
                live: '🔴 Partidos Transmitiéndose En Vivo',
                futbol: '⚽ Fútbol Nacional e Internacional',
                baloncesto: '🏀 Baloncesto NBA & Euroliga',
                tenis: '🎾 Torneos ATP & WTA',
                esports: '🎮 Competiciones eSports Worlds'
            };
            const titleEl = document.getElementById('currentLeagueTitle');
            if (titleEl) titleEl.innerText = titles[sport] || 'Eventos Deportivos';

            renderMatches();
        }

        /* =========================================================================
           12. INITIALIZATION ON DOM READY
           ========================================================================= */
        document.addEventListener('DOMContentLoaded', () => {
            const storedSound = localStorage.getItem('wp_sound');
            if (storedSound !== null) {
                soundEnabled = storedSound === '1';
                document.getElementById('soundIcon').innerText = soundEnabled ? '🔊' : '🔇';
            }
            updateBalanceDisplay();
            updateHistoryCountBadge();
            renderDaysBar();
            renderMatches();
            renderBetSlip();

            setInterval(() => {
                matches.forEach(m => {
                    if (m.isLive && typeof m.minute === 'number' && m.minute < 90) {
                        m.minute++;
                    }
                });
                renderMatches();
            }, 25000);
        });
    </script>

</body>
</html>
