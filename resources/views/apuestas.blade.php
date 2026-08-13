<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArleySoftX Play · Pronósticos y Apuestas Deportivas en Vivo</title>
    <meta name="description" content="Plataforma de pronósticos y apuestas deportivas en tiempo real con datos de ligas oficiales, calendario de 8 días, estadísticas H2H y simulación demo.">
    
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
        
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #070c14; }
        ::-webkit-scrollbar-thumb { background: #1f304b; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #00e676; }

        .glow-green { box-shadow: 0 0 20px rgba(0, 230, 118, 0.3); }
        .glow-green-sm { box-shadow: 0 0 10px rgba(0, 230, 118, 0.25); }
        .glow-blue { box-shadow: 0 0 20px rgba(0, 176, 255, 0.3); }
        
        .pulse-live {
            animation: pulse-live 1.6s infinite;
        }
        @keyframes pulse-live {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.5; }
        }

        .odd-btn { transition: all 0.18s ease-in-out; }
        .odd-btn:hover { transform: translateY(-2px); }
        .odd-btn.selected {
            background: linear-gradient(135deg, #00e676 0%, #00b843 100%) !important;
            color: #070c14 !important;
            border-color: #00e676 !important;
            box-shadow: 0 0 14px rgba(0, 230, 118, 0.5);
            font-weight: 800;
        }
        .odd-btn.selected span.odd-val { color: #070c14 !important; }

        .day-tab.active {
            background: linear-gradient(135deg, #00e676 0%, #00b843 100%);
            color: #070c14;
            font-weight: 800;
            border-color: #00e676;
            box-shadow: 0 0 12px rgba(0, 230, 118, 0.3);
        }

        @keyframes scoreFlash {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); color: #00e676; background: rgba(0,230,118,0.2); }
            100% { transform: scale(1); }
        }
        .score-updated { animation: scoreFlash 1.2s ease; }

        @media (max-width: 1023px) {
            #betslipDrawer.open { transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-wpDark text-slate-100 font-jakarta min-h-screen flex flex-col justify-between selection:bg-wpGreen selection:text-wpDark">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 bg-wpDark2/95 backdrop-blur-md border-b border-wpBorder">
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
                    <span id="apiStatusBadge" class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-black uppercase tracking-wider bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 rounded font-outfit">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        API EN VIVO
                    </span>
                </div>
            </div>

            <!-- Virtual Balance Bar & Actions -->
            <div class="flex items-center gap-2 sm:gap-4">
                
                <!-- Polling Sync Indicator -->
                <button onclick="refreshCurrentDateFixtures(true)" class="p-2 rounded-xl bg-wpCard hover:bg-wpCardHover border border-wpBorder text-slate-300 hover:text-wpGreen transition" title="Sincronizar Marcadores en Vivo">
                    <span id="syncSpinner" class="inline-block transition-transform">🔄</span>
                </button>

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

        <!-- 8-Day Calendar Navigation Strip -->
        <div class="bg-wpDark border-t border-wpBorder/80 overflow-x-auto scrollbar-none py-2 px-4">
            <div class="max-w-7xl mx-auto flex items-center gap-2 text-xs font-outfit" id="daysBarContainer">
                <!-- Injected dynamically by JS -->
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
                    <span class="text-[10px] px-1.5 py-0.2 bg-wpRed/20 text-wpRed rounded-full" id="liveMatchBadgeCount">0</span>
                </button>
                <button onclick="filterSport('beisbol')" class="sport-tab px-3 py-1.5 rounded-xl bg-wpCard hover:bg-wpCardHover text-slate-200 border border-wpBorder transition flex items-center gap-1.5" data-sport="beisbol">
                    <span>⚾</span>
                    <span>Béisbol (MLB)</span>
                </button>
                <button onclick="filterSport('futbol')" class="sport-tab px-3 py-1.5 rounded-xl bg-wpCard hover:bg-wpCardHover text-slate-200 border border-wpBorder transition flex items-center gap-1.5" data-sport="futbol">
                    <span>⚽</span>
                    <span>Fútbol</span>
                </button>
                <button onclick="filterSport('baloncesto')" class="sport-tab px-3 py-1.5 rounded-xl bg-wpCard hover:bg-wpCardHover text-slate-200 border border-wpBorder transition flex items-center gap-1.5" data-sport="baloncesto">
                    <span>🏀</span>
                    <span>Baloncesto (WNBA/NBA)</span>
                </button>
                <button onclick="filterSport('futbol_americano')" class="sport-tab px-3 py-1.5 rounded-xl bg-wpCard hover:bg-wpCardHover text-slate-200 border border-wpBorder transition flex items-center gap-1.5" data-sport="futbol_americano">
                    <span>🏈</span>
                    <span>Fútbol Americano (NFL)</span>
                </button>
                
                <div class="h-4 w-px bg-slate-800 mx-1"></div>

                <div class="text-[11px] text-slate-400 flex items-center gap-1 font-normal">
                    <span>⏱️ Auto-actualización:</span>
                    <span class="font-bold text-wpGreen" id="pollingCountdown">30s</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 w-full flex-grow">
        
        <!-- API Notice / Live Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-wpCard via-[#122238] to-wpCard border border-wpBorder p-6 sm:p-8 mb-6">
            <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-wpGreen/10 to-transparent pointer-events-none"></div>
            
            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-wpGreen/10 border border-wpGreen/30 text-wpGreen text-xs font-bold font-outfit uppercase tracking-wider mb-3">
                    <span class="w-2 h-2 rounded-full bg-wpGreen animate-ping"></span>
                    API Deportiva Conectada · Partidos y Marcadores en Tiempo Real
                </div>
                <h1 class="font-bebas text-4xl sm:text-5xl md:text-6xl text-white tracking-wide leading-none mb-2">
                    PARTIDOS REALES & <span class="text-wpGreen">CUOTAS EN VIVO</span>
                </h1>
                <p class="text-slate-300 text-sm sm:text-base font-light leading-relaxed mb-4">
                    Conexión directa con partidos del mundo real en los próximos 8 días. Consulta estadísticas H2H y haz seguimiento de los marcadores minuto a minuto sin riesgo real.
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
            <section class="lg:col-span-8 space-y-4">
                
                <!-- Active Filter & League Switcher Header -->
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center justify-between bg-wpDark2 border border-wpBorder rounded-2xl p-4 gap-2">
                        <div class="flex items-center gap-2">
                            <span id="currentLeagueTitle" class="font-outfit font-black text-base sm:text-lg text-white">
                                🏆 Partidos de Hoy & En Vivo
                            </span>
                            <span id="activeDateBadge" class="text-xs px-2.5 py-0.5 bg-wpCard border border-wpBorder rounded-full text-slate-400 font-bold font-outfit">
                                Hoy
                            </span>
                            <span id="activeLeagueBadge" class="hidden text-xs px-2.5 py-0.5 bg-wpGreen/15 border border-wpGreen/40 rounded-full text-wpGreen font-black font-outfit">
                                Todas las Ligas
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="apiLoadingIndicator" class="hidden text-xs text-wpGreen font-bold flex items-center gap-1">
                                <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>Actualizando API...</span>
                            </span>
                        </div>
                    </div>

                    <!-- 🌐 Dynamic League Sub-Filters Bar -->
                    <div class="bg-wpDark2/95 border border-wpBorder rounded-2xl p-3 shadow-md">
                        <div class="flex items-center justify-between gap-2 mb-2 px-1 text-[11px] font-bold text-slate-400 font-outfit">
                            <div class="flex items-center gap-1.5">
                                <span class="text-wpGreen">🏆</span>
                                <span class="text-slate-200 uppercase font-black tracking-wide">Ligas & Torneos:</span>
                                <span id="leagueCountIndicator" class="text-wpGreen font-extrabold text-[10px] bg-wpGreen/10 px-2 py-0.5 rounded-full border border-wpGreen/30">0 ligas</span>
                            </div>
                            <span class="text-[10px] text-slate-500 hidden sm:inline font-light">Selecciona una liga para filtrar los partidos</span>
                        </div>
                        <div id="leaguesFilterContainer" class="flex items-center gap-2 overflow-x-auto scrollbar-none py-1">
                            <!-- Injected dynamically by JS -->
                        </div>
                    </div>
                </div>

                <!-- Matches Container -->
                <div id="matchesContainer" class="space-y-4 pt-1">
                    <!-- Dynamic Matches injected by JS -->
                </div>


            </section>

            <!-- Right: BetSlip Sidebar (4 cols on lg, Desktop Sticky) -->
            <aside class="hidden lg:block lg:col-span-4 sticky top-24">
                <div class="bg-wpDark2 border border-wpBorder rounded-3xl overflow-hidden shadow-2xl">
                    <div id="desktopBetslipContainer">
                        <!-- Rendered by JS -->
                    </div>
                </div>
            </aside>

        </div>

    </main>

    <!-- Mobile Drawer BetSlip -->
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
            <div id="mobileBetslipContainer" class="overflow-y-auto p-4 flex-grow"></div>
        </div>
    </div>

    <!-- Modal: Estadísticas & Histórico H2H -->
    <div id="statsModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4">
        <div class="bg-wpDark2 border border-wpBorder rounded-3xl w-full max-w-2xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="p-4 sm:p-5 border-b border-wpBorder flex items-center justify-between bg-wpCard">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-wpGreen/15 border border-wpGreen/30 text-wpGreen flex items-center justify-center text-xl">
                        📊
                    </div>
                    <div>
                        <h2 class="font-bebas text-2xl text-white tracking-wide">ESTADÍSTICAS & HISTÓRICO H2H</h2>
                        <p class="text-xs text-slate-400" id="statsModalLeague">Liga de Fútbol</p>
                    </div>
                </div>
                <button onclick="closeStatsModal()" class="w-9 h-9 rounded-full bg-wpCardHover flex items-center justify-center text-slate-300 hover:text-white font-bold transition">
                    ✕
                </button>
            </div>
            <div id="statsModalContent" class="p-4 sm:p-6 overflow-y-auto space-y-6 flex-grow"></div>
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
                        <p class="text-xs text-slate-400">Consulta tus boletos en el simulador</p>
                    </div>
                </div>
                <button onclick="closeHistoryModal()" class="w-9 h-9 rounded-full bg-wpCardHover flex items-center justify-center text-slate-300 hover:text-white font-bold transition">
                    ✕
                </button>
            </div>
            <div id="historyListContainer" class="p-6 overflow-y-auto space-y-4 flex-grow"></div>
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

    <!-- All Leagues Explorer Modal -->
    <div id="leaguesModal" class="fixed inset-0 z-50 hidden bg-black/85 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-wpDark2 border border-wpBorder rounded-3xl w-full max-w-2xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden animate-fadeIn">
            <!-- Header -->
            <div class="p-5 border-b border-wpBorder bg-wpCard/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-wpGreen/20 border border-wpGreen/40 text-wpGreen flex items-center justify-center text-xl">
                        🏆
                    </div>
                    <div>
                        <h3 class="font-bebas text-2xl text-white tracking-wide">EXPLORADOR DE LIGAS & TORNEOS</h3>
                        <p class="text-xs text-slate-400 font-outfit font-light">Toca una liga para filtrar o marca la estrella ⭐ para añadirla a tus favoritas.</p>
                    </div>
                </div>
                <button onclick="closeLeaguesModal()" class="w-8 h-8 rounded-full bg-wpCard hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
                    ✕
                </button>
            </div>

            <!-- Search and Action Bar inside Modal -->
            <div class="p-4 border-b border-wpBorder/60 bg-wpDark/80 space-y-3">
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🔍</span>
                    <input type="text" id="leagueSearchInput" placeholder="Buscar liga o torneo (ej: Premier, Champions, Colombia, MLB)..." oninput="filterLeaguesModalList(this.value)"
                           class="w-full bg-wpCard border border-wpBorder focus:border-wpGreen text-white font-outfit text-xs pl-10 pr-4 py-2.5 rounded-xl outline-none transition placeholder:text-slate-500">
                </div>
                <div class="flex flex-wrap items-center justify-between gap-2 text-xs font-outfit">
                    <div class="flex items-center gap-2">
                        <button onclick="filterLeague('all'); closeLeaguesModal();" class="px-3 py-1.5 rounded-xl bg-wpGreen/15 border border-wpGreen/30 text-wpGreen font-bold hover:bg-wpGreen hover:text-wpDark transition flex items-center gap-1">
                            <span>🔥</span> <span>Ver Todas las Ligas</span>
                        </button>
                        <button onclick="closeLeaguesModal(); openFavoritesModal();" class="px-3 py-1.5 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-400 font-bold hover:bg-amber-400 hover:text-slate-950 transition flex items-center gap-1">
                            <span>⭐</span> <span>Mis Favoritas</span>
                        </button>
                    </div>
                    <span id="modalLeaguesCountLabel" class="text-slate-400 text-[11px] font-bold">0 ligas disponibles</span>
                </div>
            </div>

            <!-- Scrollable Leagues List -->
            <div id="modalLeaguesListContainer" class="p-4 overflow-y-auto space-y-2 flex-grow max-h-[50vh]">
                <!-- Populated dynamically by JS -->
            </div>

            <!-- Footer -->
            <div class="p-4 border-t border-wpBorder bg-wpCard/40 flex items-center justify-between text-xs font-outfit text-slate-400">
                <span>Tip: Las ligas favoritas se guardan en tu dispositivo para acceso rápido.</span>
                <button onclick="closeLeaguesModal()" class="px-5 py-2 rounded-xl bg-wpCardHover text-white font-bold hover:bg-slate-700 transition">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Favorites Manager Modal -->
    <div id="favoritesModal" class="fixed inset-0 z-50 hidden bg-black/85 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-wpDark2 border border-amber-500/40 rounded-3xl w-full max-w-xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden animate-fadeIn">
            <!-- Header -->
            <div class="p-5 border-b border-wpBorder bg-wpCard/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center text-xl">
                        ⭐
                    </div>
                    <div>
                        <h3 class="font-bebas text-2xl text-white tracking-wide">MIS LIGAS FAVORITAS</h3>
                        <p class="text-xs text-slate-400 font-outfit font-light">Toca la estrella ⭐ para quitar de favoritas o pulsa para ver sus partidos.</p>
                    </div>
                </div>
                <button onclick="closeFavoritesModal()" class="w-8 h-8 rounded-full bg-wpCard hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
                    ✕
                </button>
            </div>

            <!-- Actions Header -->
            <div id="favoritesModalActions" class="p-4 border-b border-wpBorder/60 bg-wpDark/80 flex items-center justify-between gap-2">
                <button onclick="filterLeague('favorites'); closeFavoritesModal();" class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-950 font-black font-outfit text-xs hover:brightness-110 transition flex items-center gap-1.5 shadow">
                    <span>⚡</span> <span>Ver Partidos de Todas Mis Favoritas</span>
                </button>
                <button onclick="closeFavoritesModal(); openLeaguesModal();" class="text-xs text-wpGreen font-bold font-outfit hover:underline flex items-center gap-1">
                    <span>+ Agregar más ligas</span>
                </button>
            </div>

            <!-- Scrollable Favorites List -->
            <div id="modalFavoritesListContainer" class="p-4 overflow-y-auto space-y-2 flex-grow max-h-[50vh]">
                <!-- Populated dynamically by JS -->
            </div>

            <!-- Footer -->
            <div class="p-4 border-t border-wpBorder bg-wpCard/40 flex items-center justify-between text-xs font-outfit text-slate-400">
                <span id="modalFavoritesCountSummary">0 ligas en tu lista de favoritas</span>
                <button onclick="closeFavoritesModal()" class="px-5 py-2 rounded-xl bg-wpCardHover text-white font-bold hover:bg-slate-700 transition">
                    Listo
                </button>
            </div>
        </div>
    </div>

    <!-- Favorite Teams Explorer & Manager Modal -->
    <div id="teamsModal" class="fixed inset-0 z-50 hidden bg-black/85 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-wpDark2 border border-rose-500/40 rounded-3xl w-full max-w-2xl max-h-[88vh] flex flex-col shadow-2xl overflow-hidden animate-fadeIn">
            <!-- Header -->
            <div class="p-5 border-b border-wpBorder bg-wpCard/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-rose-500/20 border border-rose-500/40 text-rose-400 flex items-center justify-center text-xl">
                        ❤️
                    </div>
                    <div>
                        <h3 class="font-bebas text-2xl text-white tracking-wide">BUSCADOR DE EQUIPOS FAVORITOS</h3>
                        <p class="text-xs text-slate-400 font-outfit font-light">Selecciona varios equipos favoritos para ver su calendario, cuándo juegan y resultados.</p>
                    </div>
                </div>
                <button onclick="closeTeamsModal()" class="w-8 h-8 rounded-full bg-wpCard hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
                    ✕
                </button>
            </div>

            <!-- Search and Action Bar inside Modal -->
            <div class="p-4 border-b border-wpBorder/60 bg-wpDark/90 space-y-3">
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🔍</span>
                    <input type="text" id="teamSearchInput" placeholder="Buscar equipo (ej: Real Madrid, Millonarios, Inter Miami, Barcelona, City, Boca)..." oninput="filterTeamsModalList(this.value)"
                           class="w-full bg-wpCard border border-wpBorder focus:border-rose-500 text-white font-outfit text-xs pl-10 pr-4 py-2.5 rounded-xl outline-none transition placeholder:text-slate-500">
                </div>

                <!-- Chips of Selected Favorite Teams -->
                <div id="modalSelectedTeamsChipsContainer" class="flex flex-wrap items-center gap-1.5 max-h-20 overflow-y-auto scrollbar-none py-0.5">
                    <!-- Populated dynamically by JS -->
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2 text-xs font-outfit pt-1">
                    <div class="flex items-center gap-2">
                        <button onclick="filterLeague('favorite_teams'); closeTeamsModal();" class="px-4 py-2 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-black hover:brightness-110 transition flex items-center gap-1.5 shadow">
                            <span>⚡</span> <span>Ver Partidos de Mis Equipos</span>
                        </button>
                        <button onclick="clearAllFavoriteTeams()" class="px-3 py-1.5 rounded-xl bg-wpCard text-slate-400 hover:text-rose-400 text-xs font-bold border border-wpBorder transition">
                            Limpiar Selección
                        </button>
                    </div>
                    <span id="modalTeamsCountLabel" class="text-slate-400 text-[11px] font-bold">0 equipos encontrados</span>
                </div>
            </div>

            <!-- Scrollable Teams List -->
            <div id="modalTeamsListContainer" class="p-4 overflow-y-auto space-y-2.5 flex-grow max-h-[50vh]">
                <!-- Populated dynamically by JS -->
            </div>

            <!-- Footer -->
            <div class="p-4 border-t border-wpBorder bg-wpCard/40 flex items-center justify-between text-xs font-outfit text-slate-400">
                <span id="modalTeamsSummaryFooter">0 equipos en favoritos</span>
                <button onclick="closeTeamsModal()" class="px-5 py-2 rounded-xl bg-wpCardHover text-white font-bold hover:bg-slate-700 transition">
                    Listo
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
                Recarga saldo virtual para seguir pronosticando partidos reales del mundo.
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

    <!-- Ticker Toast -->
    <div id="simToast" class="fixed bottom-6 right-6 z-50 bg-wpCard border border-wpGreen/50 text-white rounded-2xl p-4 shadow-2xl max-w-sm hidden transition-all duration-300 transform translate-y-4">
        <div class="flex items-center gap-3">
            <span class="text-2xl animate-bounce">⚽</span>
            <div class="flex-1">
                <div class="text-[10px] font-bold text-wpGreen uppercase font-outfit tracking-wider">¡ACTUALIZACIÓN EN VIVO!</div>
                <div id="simToastMessage" class="text-xs font-semibold">Marcador actualizado</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-wpBorder bg-wpDark2 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 font-light">
            <div class="flex items-center gap-2">
                <span class="font-bebas text-lg text-white">ARLEYSOFTX <span class="text-wpGreen">PLAY</span></span>
                <span>· Datos Oficiales en Vivo & Simulador</span>
            </div>
            <p>Datos en tiempo real integrados con feeds deportivos de alta disponibilidad.</p>
            <div class="flex items-center gap-4 text-slate-400">
                <a href="{{ route('puntico') }}" class="hover:text-wpGreen transition font-outfit font-semibold">📍 Panel de Rutas</a>
                <a href="{{ route('home') }}" class="hover:text-wpGreen transition font-outfit font-semibold">🏠 Home</a>
            </div>
        </div>
    </footer>

    <!-- Betting App Logic & API Integration -->
    <script>
        /* =========================================================================
           1. AUDIO SYNTHESIZER
           ========================================================================= */
        let audioCtx = null;
        let soundEnabled = true;

        function getAudioContext() {
            if (!audioCtx) {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                audioCtx = new AudioContextClass();
            }
            if (audioCtx.state === 'suspended') audioCtx.resume();
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
            } catch (e) {}
        }

        function toggleSound() {
            soundEnabled = !soundEnabled;
            localStorage.setItem('wp_sound', soundEnabled ? '1' : '0');
            document.getElementById('soundIcon').innerText = soundEnabled ? '🔊' : '🔇';
        }

        /* =========================================================================
           2. 8-DAY CALENDAR & STATE
           ========================================================================= */
        const DEFAULT_BALANCE = 100000;
        let balance = parseInt(localStorage.getItem('wp_balance')) || DEFAULT_BALANCE;
        let selectedBets = [];
        let betMode = 'single';
        let currentSportFilter = 'all';
        let currentLeagueFilter = 'all';
        let favoriteLeagues = JSON.parse(localStorage.getItem('wp_favorite_leagues') || '[]');
        let favoriteTeams = JSON.parse(localStorage.getItem('wp_favorite_teams') || '[]');
        let currentDayOffset = 0;
        let betHistory = JSON.parse(localStorage.getItem('wp_history') || '[]');

        let matches = [];
        let pollingTimer = null;
        let countdownSeconds = 30;

        // Build 8 days
        const next8Days = [];
        const dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        const monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        for (let i = 0; i < 8; i++) {
            const d = new Date();
            d.setDate(d.getDate() + i);
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            next8Days.push({
                offset: i,
                isoDate: `${yyyy}-${mm}-${dd}`,
                dayName: i === 0 ? 'Hoy' : i === 1 ? 'Mañana' : dayNames[d.getDay()],
                dateString: `${d.getDate()} ${monthNames[d.getMonth()]}`
            });
        }

        /* =========================================================================
           3. API INTEGRATION & REAL TIME POLLING
           ========================================================================= */
        async function fetchFixturesFromApi(offset = 0) {
            const targetDay = next8Days.find(d => d.offset === offset) || next8Days[0];
            const spinner = document.getElementById('apiLoadingIndicator');
            if (spinner) spinner.classList.remove('hidden');

            try {
                const response = await fetch(`/api/sports/fixtures?date=${targetDay.isoDate}&offset=${offset}`);
                const result = await response.json();

                if (result.success && Array.isArray(result.data) && result.data.length > 0) {
                    matches = result.data;
                    updateApiBadge('online');
                } else {
                    updateApiBadge('fallback');
                }
            } catch (err) {
                console.warn('API fetch warning, using fallback dataset', err);
                updateApiBadge('fallback');
            } finally {
                if (spinner) spinner.classList.add('hidden');
                renderLeaguesBar();
                renderMatches();
                evaluateRealTimeTickets();
            }
        }

        async function pollLiveScores() {
            try {
                const response = await fetch('/api/sports/live');
                const result = await response.json();

                if (result.success && Array.isArray(result.data)) {
                    let updatedAny = false;
                    result.data.forEach(liveGame => {
                        const existing = matches.find(m => m.id === liveGame.id || (m.home.includes(liveGame.home) && m.away.includes(liveGame.away)));
                        if (existing) {
                            if (existing.homeScore !== liveGame.homeScore || existing.awayScore !== liveGame.awayScore) {
                                existing.homeScore = liveGame.homeScore;
                                existing.awayScore = liveGame.awayScore;
                                existing.minute = liveGame.minute;
                                updatedAny = true;
                                playSound('goal');
                                showSimToast(`⚽ ¡GOL REAL EN VIVO! ${liveGame.home} ${liveGame.homeScore} - ${liveGame.awayScore} ${liveGame.away}`);
                            }
                        }
                    });

                    if (updatedAny) {
                        renderMatches();
                    }
                    evaluateRealTimeTickets();
                }
            } catch (e) {}
        }

        function startPollingEngine() {
            if (pollingTimer) clearInterval(pollingTimer);

            countdownSeconds = 30;
            const countEl = document.getElementById('pollingCountdown');

            setInterval(() => {
                countdownSeconds--;
                if (countEl) countEl.innerText = `${countdownSeconds}s`;

                if (countdownSeconds <= 0) {
                    countdownSeconds = 30;
                    pollLiveScores();
                }
            }, 1000);
        }

        function refreshCurrentDateFixtures(manual = false) {
            if (manual) {
                playSound('click');
                const spin = document.getElementById('syncSpinner');
                if (spin) spin.classList.add('rotate-180');
                setTimeout(() => spin && spin.classList.remove('rotate-180'), 600);
            }
            fetchFixturesFromApi(currentDayOffset);
            pollLiveScores();
        }

        function updateApiBadge(status) {
            const badge = document.getElementById('apiStatusBadge');
            if (!badge) return;
            if (status === 'online') {
                badge.className = 'hidden sm:inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-black uppercase tracking-wider bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 rounded font-outfit';
                badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> API EN VIVO';
            } else {
                badge.className = 'hidden sm:inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-black uppercase tracking-wider bg-amber-500/15 text-amber-400 border border-amber-500/30 rounded font-outfit';
                badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> MODO RESPALDO';
            }
        }

        /* =========================================================================
           4. RENDER 8-DAY CALENDAR STRIP
           ========================================================================= */
        function renderDaysBar() {
            const container = document.getElementById('daysBarContainer');
            if (!container) return;

            let html = '';
            next8Days.forEach(day => {
                const isActive = currentDayOffset === day.offset;
                html += `
                    <button onclick="filterDay(${day.offset})" class="day-tab px-3.5 py-1.5 rounded-xl border border-wpBorder transition whitespace-nowrap flex items-center gap-1.5 ${isActive ? 'active' : 'bg-wpCard text-slate-300 hover:bg-wpCardHover'}">
                        <span class="font-bold font-outfit">${day.dayName}</span>
                        <span class="text-[10px] opacity-75">${day.dateString}</span>
                    </button>
                `;
            });

            container.innerHTML = html;
        }

        function filterDay(offset) {
            playSound('click');
            currentDayOffset = offset;
            renderDaysBar();
            
            const badge = document.getElementById('activeDateBadge');
            const found = next8Days.find(d => d.offset === offset);
            if (badge && found) {
                badge.innerText = `${found.dayName} (${found.dateString})`;
            }

            fetchFixturesFromApi(offset);
        }

        /* =========================================================================
           4.5. FAVORITE LEAGUES & FAVORITE TEAMS HELPERS (ROBUST & NORMALIZED)
           ========================================================================= */
        let simToastTimer = null;
        function showSimToast(message, title = '¡ACTUALIZACIÓN EN VIVO!', icon = '⚽') {
            const toast = document.getElementById('simToast');
            const msgEl = document.getElementById('simToastMessage');
            if (!toast || !msgEl) return;

            msgEl.innerText = message;
            const titleEl = toast.querySelector('.font-outfit');
            if (titleEl) titleEl.innerText = title;
            const iconEl = toast.querySelector('.animate-bounce');
            if (iconEl) iconEl.innerText = icon;

            toast.classList.remove('hidden', 'translate-y-4');
            toast.classList.add('translate-y-0');

            if (simToastTimer) clearTimeout(simToastTimer);
            simToastTimer = setTimeout(() => {
                toast.classList.add('translate-y-4');
                setTimeout(() => toast.classList.add('hidden'), 300);
            }, 3000);
        }

        // --- LEAGUES FAVORITES ---
        function normalizeLeague(name) {
            if (!name) return '';
            return name.toString().trim().replace(/\s+/g, ' ');
        }

        function isLeagueFavorite(name) {
            const target = normalizeLeague(name).toLowerCase();
            if (!target) return false;
            return favoriteLeagues.some(f => normalizeLeague(f).toLowerCase() === target);
        }

        function toggleFavoriteLeague(event, leagueName) {
            if (event) {
                if (typeof event.stopPropagation === 'function') event.stopPropagation();
                if (typeof event.preventDefault === 'function') event.preventDefault();
            }
            playSound('click');
            const cleanName = normalizeLeague(leagueName);
            if (!cleanName) return;

            const existingIdx = favoriteLeagues.findIndex(f => normalizeLeague(f).toLowerCase() === cleanName.toLowerCase());
            let isNowFavorite = false;
            if (existingIdx > -1) {
                favoriteLeagues.splice(existingIdx, 1);
                isNowFavorite = false;
            } else {
                favoriteLeagues.push(cleanName);
                isNowFavorite = true;
            }
            localStorage.setItem('wp_favorite_leagues', JSON.stringify(favoriteLeagues));

            if (isNowFavorite) {
                showSimToast(`"${cleanName}" se agregó a tus ligas favoritas`, '⭐ LIGAS FAVORITAS', '⭐');
            } else {
                showSimToast(`"${cleanName}" se eliminó de ligas favoritas`, '⭐ LIGAS FAVORITAS', '🗑️');
            }

            renderLeaguesBar();
            renderMatches();
        }

        function toggleFavoriteLeagueFromModal(event, leagueName, modalSource = 'leagues') {
            toggleFavoriteLeague(event, leagueName);
            if (modalSource === 'leagues') {
                renderModalLeaguesList(modalLeaguesSearchQuery);
            } else {
                renderModalFavoritesList();
            }
        }

        // --- TEAMS FAVORITES (MULTI-SELECT & SEARCH) ---
        function normalizeTeam(name) {
            if (!name) return '';
            return name.toString().trim().replace(/\s+/g, ' ');
        }

        function isTeamFavorite(name) {
            const target = normalizeTeam(name).toLowerCase();
            if (!target) return false;
            return favoriteTeams.some(t => {
                const fav = normalizeTeam(t).toLowerCase();
                return fav === target || target.includes(fav) || fav.includes(target);
            });
        }

        function toggleFavoriteTeam(event, teamName) {
            if (event) {
                if (typeof event.stopPropagation === 'function') event.stopPropagation();
                if (typeof event.preventDefault === 'function') event.preventDefault();
            }
            playSound('click');
            const cleanName = normalizeTeam(teamName);
            if (!cleanName) return;

            const existingIdx = favoriteTeams.findIndex(t => normalizeTeam(t).toLowerCase() === cleanName.toLowerCase());
            let isNowFav = false;
            if (existingIdx > -1) {
                favoriteTeams.splice(existingIdx, 1);
                isNowFav = false;
            } else {
                favoriteTeams.push(cleanName);
                isNowFav = true;
            }
            localStorage.setItem('wp_favorite_teams', JSON.stringify(favoriteTeams));

            if (isNowFav) {
                showSimToast(`"${cleanName}" añadido a tus equipos favoritos`, '❤️ EQUIPOS FAVORITOS', '❤️');
            } else {
                showSimToast(`"${cleanName}" eliminado de favoritos`, '❤️ EQUIPOS FAVORITOS', '🗑️');
            }

            renderLeaguesBar();
            renderMatches();
            if (!document.getElementById('teamsModal')?.classList.contains('hidden')) {
                renderModalTeamsList(modalTeamsSearchQuery);
            }
        }

        function clearAllFavoriteTeams() {
            playSound('click');
            favoriteTeams = [];
            localStorage.setItem('wp_favorite_teams', JSON.stringify([]));
            showSimToast('Se han limpiado todos tus equipos favoritos', '❤️ EQUIPOS FAVORITOS', '🗑️');
            renderLeaguesBar();
            renderMatches();
            renderModalTeamsList(modalTeamsSearchQuery);
        }

        function filterLeague(leagueName) {
            playSound('click');
            currentLeagueFilter = leagueName;
            renderLeaguesBar();
            renderMatches();
        }

        function selectLeagueAndClose(leagueName) {
            filterLeague(leagueName);
            closeLeaguesModal();
            closeFavoritesModal();
            closeTeamsModal();
        }

        function getSportMatches() {
            return matches.filter(m => {
                if (currentSportFilter === 'live' && !m.isLive) return false;
                if (currentSportFilter !== 'all' && currentSportFilter !== 'live' && m.sport !== currentSportFilter) return false;
                return true;
            });
        }

        function getLeagueCounts(sportMatches) {
            const leagueCounts = {};
            sportMatches.forEach(m => {
                const lName = normalizeLeague(m.league) || 'Otras Ligas';
                leagueCounts[lName] = (leagueCounts[lName] || 0) + 1;
            });
            return leagueCounts;
        }

        /* =========================================================================
           4.6. RENDER DYNAMIC SUB-FILTER BAR
           ========================================================================= */
        function renderLeaguesBar() {
            const container = document.getElementById('leaguesFilterContainer');
            const indicator = document.getElementById('leagueCountIndicator');
            const activeBadge = document.getElementById('activeLeagueBadge');
            if (!container) return;

            const sportMatches = getSportMatches();
            const leagueCounts = getLeagueCounts(sportMatches);
            const uniqueLeagues = Object.keys(leagueCounts).filter(lName => (leagueCounts[lName] || 0) > 0);
            const favLeaguesMatchesCount = sportMatches.filter(m => isLeagueFavorite(m.league)).length;
            const favTeamsMatchesCount = sportMatches.filter(m => isTeamFavorite(m.home) || isTeamFavorite(m.away)).length;

            if (indicator) {
                indicator.innerText = `${uniqueLeagues.length} ligas · ${sportMatches.length} partidos`;
            }

            if (activeBadge) {
                if (currentLeagueFilter === 'favorites') {
                    activeBadge.innerText = `⭐ Ligas Favoritas (${favLeaguesMatchesCount})`;
                    activeBadge.classList.remove('hidden');
                } else if (currentLeagueFilter === 'favorite_teams') {
                    activeBadge.innerText = `❤️ Mis Equipos (${favTeamsMatchesCount})`;
                    activeBadge.classList.remove('hidden');
                } else if (currentLeagueFilter !== 'all') {
                    activeBadge.innerText = currentLeagueFilter;
                    activeBadge.classList.remove('hidden');
                } else {
                    activeBadge.classList.add('hidden');
                }
            }

            // Clear container and render the 3 clean popup buttons and active filter indicator
            container.innerHTML = '';

            // 1. Button "Todas las Ligas"
            const allBtn = document.createElement('button');
            const isAllActive = currentLeagueFilter === 'all';
            allBtn.className = `px-3.5 sm:px-4 py-2 rounded-xl border text-xs font-bold font-outfit transition whitespace-nowrap flex items-center gap-1.5 flex-shrink-0 ${isAllActive ? 'bg-gradient-to-r from-wpGreen to-wpGreenDark text-wpDark font-black border-wpGreen shadow-lg' : 'bg-wpCard text-slate-200 hover:bg-wpCardHover border-wpBorder'}`;
            allBtn.innerHTML = `<span>🔥</span><span>Todas las Ligas</span><span class="text-[10px] px-2 py-0.5 rounded-full ${isAllActive ? 'bg-wpDark/25 text-wpDark font-black' : 'bg-wpDark text-wpGreen font-black'}">${sportMatches.length}</span><span class="text-[10px] opacity-75">▾</span>`;
            allBtn.title = 'Abrir explorador de ligas y torneos';
            allBtn.onclick = () => openLeaguesModal();
            container.appendChild(allBtn);

            // 2. Button "Ligas Favoritas"
            const isFavLeaguesActive = currentLeagueFilter === 'favorites';
            const favBtn = document.createElement('button');
            favBtn.className = `px-3.5 sm:px-4 py-2 rounded-xl border text-xs font-bold font-outfit transition whitespace-nowrap flex items-center gap-1.5 flex-shrink-0 ${isFavLeaguesActive ? 'bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-950 font-black border-amber-400 shadow-lg' : (favLeaguesMatchesCount > 0 ? 'bg-wpCard text-amber-300 hover:bg-wpCardHover border-amber-500/50' : 'bg-wpCard text-slate-400 hover:bg-wpCardHover border-wpBorder')}`;
            favBtn.innerHTML = `<span>⭐</span><span>Ligas Favoritas</span><span class="text-[10px] px-2 py-0.5 rounded-full ${isFavLeaguesActive ? 'bg-slate-950/30 text-slate-950 font-black' : (favLeaguesMatchesCount > 0 ? 'bg-wpDark text-amber-400 font-black' : 'bg-wpDark text-slate-500')}">${favLeaguesMatchesCount}</span><span class="text-[10px] opacity-75">▾</span>`;
            favBtn.title = 'Abrir ventana de ligas favoritas';
            favBtn.onclick = () => openFavoritesModal();
            container.appendChild(favBtn);

            // 3. Button "Mis Equipos" (Buscador y multi-selección de equipos favoritos)
            const isFavTeamsActive = currentLeagueFilter === 'favorite_teams';
            const teamsBtn = document.createElement('button');
            teamsBtn.className = `px-3.5 sm:px-4 py-2 rounded-xl border text-xs font-bold font-outfit transition whitespace-nowrap flex items-center gap-1.5 flex-shrink-0 ${isFavTeamsActive ? 'bg-gradient-to-r from-rose-500 to-pink-600 text-white font-black border-rose-500 shadow-lg' : (favoriteTeams.length > 0 ? 'bg-wpCard text-rose-300 hover:bg-wpCardHover border-rose-500/50' : 'bg-wpCard text-slate-400 hover:bg-wpCardHover border-wpBorder')}`;
            teamsBtn.innerHTML = `<span>❤️</span><span>Mis Equipos</span><span class="text-[10px] px-2 py-0.5 rounded-full ${isFavTeamsActive ? 'bg-slate-950/30 text-white font-black' : (favTeamsMatchesCount > 0 ? 'bg-wpDark text-rose-400 font-black' : 'bg-wpDark text-slate-500')}">${favTeamsMatchesCount}</span><span class="text-[10px] opacity-75">▾</span>`;
            teamsBtn.title = 'Buscar y gestionar equipos favoritos';
            teamsBtn.onclick = () => openTeamsModal();
            container.appendChild(teamsBtn);

            // 4. Píldora de Filtro Activo
            if (currentLeagueFilter !== 'all') {
                const filterIndicator = document.createElement('div');
                filterIndicator.className = 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-wpGreen/15 border border-wpGreen/40 text-wpGreen text-xs font-bold font-outfit flex-shrink-0 animate-fadeIn';
                
                if (currentLeagueFilter === 'favorites') {
                    filterIndicator.innerHTML = `
                        <span>⭐ Filtrando: <strong>Ligas Favoritas</strong> (${favLeaguesMatchesCount})</span>
                        <button onclick="filterLeague('all')" class="ml-1 w-4 h-4 rounded-full bg-wpGreen/20 hover:bg-rose-500 hover:text-white flex items-center justify-center text-[10px] transition" title="Quitar filtro">✕</button>
                    `;
                } else if (currentLeagueFilter === 'favorite_teams') {
                    filterIndicator.className = 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-rose-500/15 border border-rose-500/40 text-rose-400 text-xs font-bold font-outfit flex-shrink-0 animate-fadeIn';
                    filterIndicator.innerHTML = `
                        <span>❤️ Filtrando: <strong>Mis Equipos Favoritos</strong> (${favTeamsMatchesCount} partidos)</span>
                        <button onclick="filterLeague('all')" class="ml-1 w-4 h-4 rounded-full bg-rose-500/20 hover:bg-rose-500 hover:text-white flex items-center justify-center text-[10px] transition" title="Quitar filtro">✕</button>
                    `;
                } else {
                    const count = leagueCounts[currentLeagueFilter] || 0;
                    filterIndicator.innerHTML = `
                        <span>🏆 Liga: <strong>${currentLeagueFilter}</strong> (${count})</span>
                        <button onclick="filterLeague('all')" class="ml-1 w-4 h-4 rounded-full bg-wpGreen/20 hover:bg-rose-500 hover:text-white flex items-center justify-center text-[10px] transition" title="Quitar filtro">✕</button>
                    `;
                }
                container.appendChild(filterIndicator);
            }
        }

        /* =========================================================================
           4.7. LEAGUES & FAVORITES MODAL LOGIC
           ========================================================================= */
        let modalLeaguesSearchQuery = '';

        function openLeaguesModal() {
            playSound('click');
            modalLeaguesSearchQuery = '';
            const searchInput = document.getElementById('leagueSearchInput');
            if (searchInput) searchInput.value = '';

            renderModalLeaguesList('');
            const modal = document.getElementById('leaguesModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeLeaguesModal() {
            const modal = document.getElementById('leaguesModal');
            if (modal) modal.classList.add('hidden');
        }

        function filterLeaguesModalList(query) {
            modalLeaguesSearchQuery = query || '';
            renderModalLeaguesList(modalLeaguesSearchQuery);
        }

        function renderModalLeaguesList(filterText = '') {
            const container = document.getElementById('modalLeaguesListContainer');
            const countLabel = document.getElementById('modalLeaguesCountLabel');
            if (!container) return;

            const sportMatches = getSportMatches();
            const leagueCounts = getLeagueCounts(sportMatches);
            let uniqueLeagues = Object.keys(leagueCounts).filter(lName => (leagueCounts[lName] || 0) > 0);

            // Sort: Favorites first, then match count
            uniqueLeagues.sort((a, b) => {
                const isFavA = isLeagueFavorite(a);
                const isFavB = isLeagueFavorite(b);
                if (isFavA && !isFavB) return -1;
                if (!isFavA && isFavB) return 1;
                return leagueCounts[b] - leagueCounts[a];
            });

            // Filter by search query
            const q = (filterText || '').trim().toLowerCase();
            if (q) {
                uniqueLeagues = uniqueLeagues.filter(l => l.toLowerCase().includes(q));
            }

            if (countLabel) {
                countLabel.innerText = `${uniqueLeagues.length} ligas encontradas`;
            }

            if (uniqueLeagues.length === 0) {
                container.innerHTML = `
                    <div class="py-10 text-center text-slate-400">
                        <span class="text-3xl block mb-2">🔍</span>
                        <h5 class="font-bebas text-xl text-white">NO SE ENCONTRARON LIGAS</h5>
                        <p class="text-xs text-slate-500">No hay torneos que coincidan con "${filterText}".</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = uniqueLeagues.map(leagueName => {
                const count = leagueCounts[leagueName] || 0;
                const isFav = isLeagueFavorite(leagueName);
                const isSelected = currentLeagueFilter === leagueName;
                const escapedName = leagueName.replace(/'/g, "\\'").replace(/"/g, '&quot;');

                return `
                    <div class="bg-wpCard hover:border-slate-600 border ${isSelected ? 'border-wpGreen bg-wpGreen/10' : (isFav ? 'border-amber-500/40 bg-amber-500/5' : 'border-wpBorder')} rounded-2xl p-3 flex items-center justify-between gap-3 transition">
                        <div class="flex items-center gap-3 flex-grow cursor-pointer" onclick="selectLeagueAndClose('${escapedName}')">
                            <button onclick="toggleFavoriteLeagueFromModal(event, '${escapedName}', 'leagues')" class="text-base hover:scale-125 transition p-1.5 rounded-lg hover:bg-wpDark" title="${isFav ? 'Quitar de favoritas' : 'Añadir a favoritas'}">
                                ${isFav ? '<span class="text-amber-400">⭐</span>' : '<span class="text-slate-500 hover:text-amber-400">☆</span>'}
                            </button>
                            <div>
                                <span class="text-xs sm:text-sm font-bold text-white block font-outfit hover:text-wpGreen transition">${leagueName}</span>
                                <span class="text-[10px] text-slate-400 font-outfit">${count} ${count === 1 ? 'partido disponible' : 'partidos disponibles'}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="selectLeagueAndClose('${escapedName}')" class="px-3.5 py-1.5 rounded-xl ${isSelected ? 'bg-wpGreen text-wpDark font-black' : 'bg-wpDark hover:bg-wpGreen hover:text-wpDark text-slate-200'} border border-wpBorder text-xs font-bold font-outfit transition flex items-center gap-1">
                                <span>${isSelected ? '✓ Activa' : 'Ver Partidos'}</span>
                                <span class="text-[10px] font-black font-bebas px-1.5 py-0.2 rounded ${isSelected ? 'bg-wpDark/20 text-wpDark' : 'bg-wpCard text-wpGreen'}">${count}</span>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function openFavoritesModal() {
            playSound('click');
            renderModalFavoritesList();
            const modal = document.getElementById('favoritesModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeFavoritesModal() {
            const modal = document.getElementById('favoritesModal');
            if (modal) modal.classList.add('hidden');
        }

        function renderModalFavoritesList() {
            const container = document.getElementById('modalFavoritesListContainer');
            const summaryLabel = document.getElementById('modalFavoritesCountSummary');
            const actionsHeader = document.getElementById('favoritesModalActions');
            if (!container) return;

            const sportMatches = getSportMatches();
            const leagueCounts = getLeagueCounts(sportMatches);
            const favs = favoriteLeagues.map(f => normalizeLeague(f)).filter(Boolean);

            if (summaryLabel) {
                summaryLabel.innerText = `${favs.length} ${favs.length === 1 ? 'liga guardada' : 'ligas guardadas'} en favoritas`;
            }

            if (favs.length === 0) {
                if (actionsHeader) actionsHeader.classList.add('hidden');
                container.innerHTML = `
                    <div class="py-10 text-center space-y-4">
                        <span class="text-4xl block animate-pulse">⭐</span>
                        <div>
                            <h4 class="font-bebas text-2xl text-white">NO TIENES LIGAS FAVORITAS AÚN</h4>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto font-light mt-1">
                                Abre el explorador de ligas y toca la estrella <span class="text-amber-400 font-bold">☆</span> para agregar tus torneos preferidos aquí.
                            </p>
                        </div>
                        <button onclick="closeFavoritesModal(); openLeaguesModal();" class="px-5 py-2.5 bg-gradient-to-r from-wpGreen to-wpGreenDark text-wpDark font-black font-outfit text-xs rounded-xl shadow-lg hover:brightness-110 transition">
                            🔍 Explorar Todas las Ligas
                        </button>
                    </div>
                `;
                return;
            }

            if (actionsHeader) actionsHeader.classList.remove('hidden');

            container.innerHTML = favs.map(leagueName => {
                const count = leagueCounts[leagueName] || 0;
                const isSelected = currentLeagueFilter === leagueName;
                const escapedName = leagueName.replace(/'/g, "\\'").replace(/"/g, '&quot;');

                return `
                    <div class="bg-wpCard hover:border-slate-600 border ${isSelected ? 'border-amber-400 bg-amber-500/10' : 'border-wpBorder'} rounded-2xl p-3 flex items-center justify-between gap-3 transition">
                        <div class="flex items-center gap-3 flex-grow cursor-pointer" onclick="selectLeagueAndClose('${escapedName}')">
                            <button onclick="toggleFavoriteLeagueFromModal(event, '${escapedName}', 'favorites')" class="text-base hover:scale-125 transition p-1.5 rounded-lg hover:bg-rose-500/20 text-amber-400 hover:text-rose-400" title="Quitar de favoritas">
                                ⭐
                            </button>
                            <div>
                                <span class="text-xs sm:text-sm font-bold text-white block font-outfit hover:text-amber-400 transition">${leagueName}</span>
                                <span class="text-[10px] ${count > 0 ? 'text-wpGreen font-bold' : 'text-slate-500'} font-outfit">
                                    ${count > 0 ? `⚽ ${count} ${count === 1 ? 'partido hoy' : 'partidos hoy'}` : 'Sin partidos programados hoy'}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="selectLeagueAndClose('${escapedName}')" class="px-3.5 py-1.5 rounded-xl ${isSelected ? 'bg-amber-400 text-slate-950 font-black' : 'bg-wpDark hover:bg-amber-400 hover:text-slate-950 text-slate-200'} border border-wpBorder text-xs font-bold font-outfit transition flex items-center gap-1">
                                <span>${isSelected ? '✓ Activa' : 'Ver'}</span>
                                ${count > 0 ? `<span class="text-[10px] font-black font-bebas px-1.5 py-0.2 rounded bg-wpCard text-amber-400">${count}</span>` : ''}
                            </button>
                            <button onclick="toggleFavoriteLeagueFromModal(event, '${escapedName}', 'favorites')" class="p-1.5 text-xs text-slate-500 hover:text-rose-400 transition" title="Eliminar de favoritas">
                                🗑️
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        /* =========================================================================
           4.8. TEAMS MODAL LOGIC (SEARCH & MULTI-SELECTION)
           ========================================================================= */
        let modalTeamsSearchQuery = '';

        function openTeamsModal() {
            playSound('click');
            modalTeamsSearchQuery = '';
            const input = document.getElementById('teamSearchInput');
            if (input) input.value = '';

            renderModalTeamsList('');
            const modal = document.getElementById('teamsModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeTeamsModal() {
            const modal = document.getElementById('teamsModal');
            if (modal) modal.classList.add('hidden');
        }

        function filterTeamsModalList(query) {
            modalTeamsSearchQuery = query || '';
            renderModalTeamsList(modalTeamsSearchQuery);
        }

        function renderModalTeamsList(filterText = '') {
            const container = document.getElementById('modalTeamsListContainer');
            const chipsContainer = document.getElementById('modalSelectedTeamsChipsContainer');
            const countLabel = document.getElementById('modalTeamsCountLabel');
            const summaryFooter = document.getElementById('modalTeamsSummaryFooter');
            if (!container) return;

            // 1. Render Selected Favorite Chips
            if (chipsContainer) {
                if (favoriteTeams.length === 0) {
                    chipsContainer.innerHTML = `<span class="text-[11px] text-slate-500 italic">No tienes equipos favoritos seleccionados todavía.</span>`;
                } else {
                    chipsContainer.innerHTML = favoriteTeams.map(teamName => {
                        const escaped = teamName.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                        return `
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-500/20 border border-rose-500/40 text-rose-300 text-xs font-bold font-outfit shadow-sm">
                                <span>❤️ ${teamName}</span>
                                <button onclick="toggleFavoriteTeam(event, '${escaped}')" class="w-3.5 h-3.5 rounded-full hover:bg-rose-500 hover:text-white flex items-center justify-center text-[9px] transition">✕</button>
                            </span>
                        `;
                    }).join('');
                }
            }

            // 2. Extract all unique teams from current fixtures + popular clubs
            const teamsMap = new Map();
            
            // Popular reference list
            const popularTeams = [
                { name: 'Real Madrid', league: '🌍 La Liga · España', logo: null },
                { name: 'Barcelona', league: '🌍 La Liga · España', logo: null },
                { name: 'Manchester City', league: '🌍 Premier League · Inglaterra', logo: null },
                { name: 'Liverpool', league: '🌍 Premier League · Inglaterra', logo: null },
                { name: 'Arsenal', league: '🌍 Premier League · Inglaterra', logo: null },
                { name: 'Paris Saint Germain', league: '🌍 Ligue 1 · Francia', logo: null },
                { name: 'Bayern Munich', league: '🌍 Bundesliga · Alemania', logo: null },
                { name: 'Inter Miami', league: '🌍 MLS · USA', logo: null },
                { name: 'Millonarios', league: '🌍 Liga BetPlay · Colombia', logo: null },
                { name: 'Atlético Nacional', league: '🌍 Liga BetPlay · Colombia', logo: null },
                { name: 'América de Cali', league: '🌍 Liga BetPlay · Colombia', logo: null },
                { name: 'Junior', league: '🌍 Liga BetPlay · Colombia', logo: null },
                { name: 'Santa Fe', league: '🌍 Liga BetPlay · Colombia', logo: null },
                { name: 'Boca Juniors', league: '🌍 Liga Profesional · Argentina', logo: null },
                { name: 'River Plate', league: '🌍 Liga Profesional · Argentina', logo: null },
                { name: 'Flamengo', league: '🌍 Brasileirao · Brasil', logo: null },
                { name: 'Palmeiras', league: '🌍 Brasileirao · Brasil', logo: null },
                { name: 'Juventus', league: '🌍 Serie A · Italia', logo: null },
                { name: 'Inter Milan', league: '🌍 Serie A · Italia', logo: null },
                { name: 'Chelsea', league: '🌍 Premier League · Inglaterra', logo: null }
            ];

            popularTeams.forEach(pt => {
                teamsMap.set(normalizeTeam(pt.name), {
                    name: pt.name,
                    league: pt.league,
                    logo: pt.logo,
                    match: null
                });
            });

            // Populate with real matches in current schedule
            matches.forEach(m => {
                const hName = normalizeTeam(m.home);
                const aName = normalizeTeam(m.away);

                teamsMap.set(hName, {
                    name: m.home,
                    league: m.league,
                    logo: m.homeLogo,
                    match: {
                        isLive: m.isLive,
                        isFinished: m.isFinished,
                        minute: m.minute,
                        period: m.period,
                        startTime: m.startTime,
                        opponent: m.away,
                        isHome: true,
                        score: `${m.homeScore} - ${m.awayScore}`
                    }
                });

                teamsMap.set(aName, {
                    name: m.away,
                    league: m.league,
                    logo: m.awayLogo,
                    match: {
                        isLive: m.isLive,
                        isFinished: m.isFinished,
                        minute: m.minute,
                        period: m.period,
                        startTime: m.startTime,
                        opponent: m.home,
                        isHome: false,
                        score: `${m.awayScore} - ${m.homeScore}`
                    }
                });
            });

            let allTeams = Array.from(teamsMap.values());

            // Filter by search text
            const q = (filterText || '').trim().toLowerCase();
            if (q) {
                allTeams = allTeams.filter(t => t.name.toLowerCase().includes(q) || t.league.toLowerCase().includes(q));
            }

            // Sort: Favorites first, then matches currently playing / today, then alphabetical
            allTeams.sort((a, b) => {
                const isFavA = isTeamFavorite(a.name);
                const isFavB = isTeamFavorite(b.name);
                if (isFavA && !isFavB) return -1;
                if (!isFavA && isFavB) return 1;

                const hasLiveA = a.match && a.match.isLive;
                const hasLiveB = b.match && b.match.isLive;
                if (hasLiveA && !hasLiveB) return -1;
                if (!hasLiveA && hasLiveB) return 1;

                const hasMatchA = a.match ? 1 : 0;
                const hasMatchB = b.match ? 1 : 0;
                if (hasMatchA !== hasMatchB) return hasMatchB - hasMatchA;

                return a.name.localeCompare(b.name);
            });

            if (countLabel) {
                countLabel.innerText = `${allTeams.length} equipos encontrados`;
            }
            if (summaryFooter) {
                summaryFooter.innerText = `${favoriteTeams.length} ${favoriteTeams.length === 1 ? 'equipo favorito seleccionado' : 'equipos favoritos seleccionados'}`;
            }

            if (allTeams.length === 0) {
                container.innerHTML = `
                    <div class="py-10 text-center text-slate-400 space-y-2">
                        <span class="text-3xl block">🔍</span>
                        <h5 class="font-bebas text-xl text-white">NO SE ENCONTRARON EQUIPOS</h5>
                        <p class="text-xs text-slate-500">No encontramos ningún equipo que coincida con "${filterText}".</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = allTeams.map(t => {
                const isFav = isTeamFavorite(t.name);
                const escaped = t.name.replace(/'/g, "\\'").replace(/"/g, '&quot;');

                let scheduleBadge = '';
                if (t.match) {
                    if (t.match.isLive) {
                        scheduleBadge = `<span class="px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-400 border border-rose-500/30 text-[10px] font-black animate-pulse">🔴 EN VIVO (${t.match.period || t.match.minute}) vs ${t.match.opponent} [${t.match.score}]</span>`;
                    } else if (t.match.isFinished) {
                        scheduleBadge = `<span class="px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 border border-slate-700 text-[10px] font-bold">🏁 Finalizado: ${t.match.score} vs ${t.match.opponent}</span>`;
                    } else {
                        scheduleBadge = `<span class="px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold">📅 ${t.match.startTime} vs ${t.match.opponent}</span>`;
                    }
                } else {
                    scheduleBadge = `<span class="text-[10px] text-slate-500">⚽ Próximamente en cartelera</span>`;
                }

                return `
                    <div class="bg-wpCard hover:border-slate-600 border ${isFav ? 'border-rose-500/50 bg-rose-500/5' : 'border-wpBorder'} rounded-2xl p-3 flex items-center justify-between gap-3 transition">
                        <div class="flex items-center gap-3 flex-grow cursor-pointer" onclick="toggleFavoriteTeam(event, '${escaped}')">
                            <button onclick="toggleFavoriteTeam(event, '${escaped}')" class="text-lg hover:scale-125 transition p-1.5 rounded-lg hover:bg-wpDark" title="${isFav ? 'Quitar de favoritos' : 'Añadir a favoritos'}">
                                ${isFav ? '<span class="text-rose-500">❤️</span>' : '<span class="text-slate-600 hover:text-rose-400">🤍</span>'}
                            </button>
                            ${t.logo ? `<img src="${t.logo}" class="w-8 h-8 object-contain">` : `
                                <div class="w-8 h-8 rounded-full bg-wpDark border border-wpBorder flex items-center justify-center font-black text-xs text-white">
                                    ${t.name.charAt(0)}
                                </div>
                            `}
                            <div>
                                <span class="text-xs sm:text-sm font-extrabold text-white block font-outfit ${isFav ? 'text-rose-300' : ''}">${t.name}</span>
                                <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                    <span class="text-[10px] text-slate-400 font-outfit truncate max-w-[150px]">${t.league}</span>
                                    ${scheduleBadge}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="toggleFavoriteTeam(event, '${escaped}')" class="px-3 py-1.5 rounded-xl ${isFav ? 'bg-rose-500/20 text-rose-300 border border-rose-500/40 font-black' : 'bg-wpDark hover:bg-rose-500 hover:text-white text-slate-300 border border-wpBorder font-bold'} text-xs font-outfit transition flex items-center gap-1">
                                <span>${isFav ? '❤️ Favorito' : '+ Seleccionar'}</span>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }



        /* =========================================================================
           5. RENDER MATCHES FEED
           ========================================================================= */
        function renderMatches() {
            const container = document.getElementById('matchesContainer');

            if (!container) return;

            let filtered = matches.filter(m => {
                if (currentSportFilter === 'live' && !m.isLive) return false;
                if (currentSportFilter !== 'all' && currentSportFilter !== 'live' && m.sport !== currentSportFilter) return false;
                const cleanLeague = normalizeLeague(m.league);
                if (currentLeagueFilter === 'favorites') {
                    return isLeagueFavorite(cleanLeague);
                }
                if (currentLeagueFilter === 'favorite_teams') {
                    return isTeamFavorite(m.home) || isTeamFavorite(m.away);
                }
                if (currentLeagueFilter !== 'all' && normalizeLeague(currentLeagueFilter).toLowerCase() !== cleanLeague.toLowerCase()) return false;
                return true;
            });

            const liveCount = matches.filter(m => m.isLive).length;
            const liveBadge = document.getElementById('liveMatchBadgeCount');
            if (liveBadge) liveBadge.innerText = liveCount;

            if (filtered.length === 0) {
                if (currentLeagueFilter === 'favorite_teams') {
                    container.innerHTML = `
                        <div class="bg-wpDark2 border border-rose-500/30 rounded-3xl p-10 text-center space-y-4">
                            <span class="text-4xl block animate-pulse">❤️</span>
                            <div>
                                <h4 class="font-bebas text-2xl text-white">NO HAY PARTIDOS DE TUS EQUIPOS FAVORITOS</h4>
                                <p class="text-xs text-slate-400 max-w-md mx-auto font-light mt-1">
                                    No se encontraron partidos para la fecha seleccionada en tus equipos favoritos ${favoriteTeams.length > 0 ? `(${favoriteTeams.join(', ')})` : '(ninguno seleccionado aún)'}.
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                                <button onclick="openTeamsModal()" class="px-5 py-2.5 bg-gradient-to-r from-rose-500 to-pink-600 text-white font-black font-outfit text-xs rounded-xl shadow-lg hover:brightness-110 transition flex items-center gap-2">
                                    <span>🔍</span> <span>Buscar y Seleccionar Equipos Favoritos</span>
                                </button>
                                <button onclick="filterLeague('all')" class="px-5 py-2.5 bg-wpCard text-slate-200 hover:text-white font-bold font-outfit text-xs rounded-xl border border-wpBorder transition">
                                    Ver Todos los Partidos
                                </button>
                            </div>
                        </div>
                    `;
                } else if (currentLeagueFilter === 'favorites') {
                    container.innerHTML = `
                        <div class="bg-wpDark2 border border-amber-500/30 rounded-3xl p-10 text-center space-y-4">
                            <span class="text-4xl block animate-pulse">⭐</span>
                            <div>
                                <h4 class="font-bebas text-2xl text-white">NO TIENES LIGAS FAVORITAS SELECCIONADAS</h4>
                                <p class="text-xs text-slate-400 max-w-md mx-auto font-light mt-1">
                                    Haz clic en la estrella <span class="text-amber-400 font-bold">☆</span> al lado de cualquier liga o partido para agregarla a tu lista de favoritas.
                                </p>
                            </div>
                            <div class="pt-2">
                                <button onclick="filterLeague('all')" class="px-5 py-2.5 bg-gradient-to-r from-wpGreen to-wpGreenDark text-wpDark font-black font-outfit text-xs rounded-xl shadow-lg hover:brightness-110 transition">
                                    Explorar Todas las Ligas (${matches.length} partidos)
                                </button>
                            </div>
                        </div>
                    `;
                } else if (currentLeagueFilter !== 'all') {
                    container.innerHTML = `
                        <div class="bg-wpDark2 border border-wpBorder rounded-2xl p-10 text-center">
                            <span class="text-3xl mb-2 block">🏆</span>
                            <h4 class="font-bebas text-2xl text-white">NO HAY PARTIDOS EN ESTA LIGA</h4>
                            <p class="text-xs text-slate-400 mb-4 font-light">No se encontraron encuentros programados en "${currentLeagueFilter}" para esta fecha.</p>
                            <button onclick="filterLeague('all')" class="px-4 py-2 bg-wpGreen text-wpDark font-black font-outfit text-xs rounded-xl shadow">
                                Ver Todas las Ligas (${currentSportFilter.toUpperCase()})
                            </button>
                        </div>
                    `;
                } else {
                    container.innerHTML = `
                        <div class="bg-wpDark2 border border-wpBorder rounded-2xl p-12 text-center">
                            <span class="text-4xl mb-3 block">⚽</span>
                            <h4 class="font-bebas text-2xl text-white">NO HAY PARTIDOS PROGRAMADOS</h4>
                            <p class="text-xs text-slate-400 mb-4 font-light">No se encontraron eventos para esta fecha en la API deportiva.</p>
                            <button onclick="filterDay(0)" class="px-4 py-2 bg-wpGreen text-wpDark font-black font-outfit text-xs rounded-xl">
                                Ver Partidos de Hoy
                            </button>
                        </div>
                    `;
                }
                return;
            }

            let html = '';
            filtered.forEach(m => {
                const isSelected1 = isBetSelected(m.id, '1X2', '1');
                const isSelectedX = isBetSelected(m.id, '1X2', 'X');
                const isSelected2 = isBetSelected(m.id, '1X2', '2');

                const mScorePreds = getOrComputeScorePredictions(m);
                const cleanLeague = normalizeLeague(m.league);
                const isFav = isLeagueFavorite(cleanLeague);
                const isHomeFav = isTeamFavorite(m.home);
                const isAwayFav = isTeamFavorite(m.away);
                const escapedLeagueAttr = cleanLeague.replace(/"/g, '&quot;');
                const escapedHome = m.home.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                const escapedAway = m.away.replace(/'/g, "\\'").replace(/"/g, '&quot;');

                html += `
                    <div class="bg-wpDark2 hover:border-slate-700/80 border ${(isHomeFav || isAwayFav) ? 'border-rose-500/40 bg-gradient-to-b from-rose-500/5 to-wpDark2' : 'border-wpBorder'} rounded-3xl p-4 sm:p-5 transition shadow-lg relative overflow-hidden" id="match-card-${m.id}">
                        
                        <!-- Top League, Date & Live Info -->
                        <div class="flex items-center justify-between gap-2 mb-3 pb-2.5 border-b border-wpBorder/60">
                            <div class="flex items-center gap-1.5">
                                <button onclick="toggleFavoriteLeague(event, this.getAttribute('data-league'))" data-league="${escapedLeagueAttr}" class="text-xs hover:scale-125 transition p-1" title="${isFav ? 'Quitar de favoritas' : 'Marcar liga como favorita'}">
                                    ${isFav ? '<span class="text-amber-400 text-sm">⭐</span>' : '<span class="text-slate-500 hover:text-amber-400 text-sm">☆</span>'}
                                </button>
                                <span class="text-xs font-bold text-slate-300 font-outfit cursor-pointer hover:text-wpGreen transition" onclick="filterLeague(this.getAttribute('data-league'))" data-league="${escapedLeagueAttr}">${cleanLeague}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="openStatsModal('${m.id}')" class="text-xs font-bold text-wpGreen hover:bg-wpGreen/20 bg-wpGreen/10 border border-wpGreen/30 px-2.5 py-0.5 rounded-full font-outfit transition flex items-center gap-1">
                                    <span>🎯</span> <span class="hidden sm:inline">Pronóstico & H2H</span><span class="sm:hidden">IA & H2H</span>
                                </button>

                                ${m.isLive ? `
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-500/15 border border-rose-500/30 text-rose-400 text-[11px] font-black font-outfit">
                                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                                            ${m.period || ('VIVO ' + m.minute)}
                                        </span>
                                        ${m.partialScore ? `
                                            <span class="text-[10px] font-black text-emerald-300 font-outfit bg-emerald-500/15 border border-emerald-500/30 px-2 py-0.5 rounded-full shadow-sm" title="Marcador parcial">
                                                ⏱️ ${m.partialScore}
                                            </span>
                                        ` : ''}
                                    </div>
                                ` : `
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[11px] font-semibold text-slate-400 font-outfit bg-wpCard px-2.5 py-0.5 rounded-full border border-wpBorder">
                                            ⏱️ ${m.startTime}
                                        </span>
                                        ${(m.isFinished && m.partialScore) ? `
                                            <span class="text-[10px] font-bold text-slate-300 font-outfit bg-wpDark px-2 py-0.5 rounded-full border border-slate-700">
                                                ${m.partialScore}
                                            </span>
                                        ` : ''}
                                    </div>
                                `}
                            </div>
                        </div>

                        <!-- Pill Badge de Sugerencia Rápida de Marcador Adaptativa -->
                        ${mScorePreds ? `
                            ${(m.isFinished || m.minute === 'FT' || m.minute === 'Finalizado') ? `
                                <div class="mb-3.5 px-3 py-1.5 rounded-2xl bg-slate-800/50 border border-slate-700/60 flex items-center justify-between cursor-pointer hover:border-slate-500 transition group" onclick="openStatsModal('${m.id}')">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black uppercase text-slate-400 font-outfit tracking-wider">🏁 Marcador Final Oficial:</span>
                                        <span class="text-sm font-black text-wpGreen font-bebas tracking-wide">${m.homeScore} - ${m.awayScore}</span>
                                        ${m.partialScore ? `<span class="text-[10px] text-slate-400 font-outfit font-semibold">(${m.partialScore})</span>` : '<span class="text-[10px] text-slate-400 font-outfit font-semibold">(Concluido)</span>'}
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-bold font-outfit flex items-center gap-1 group-hover:text-white transition">
                                        <span>Estadísticas</span> <span>→</span>
                                    </span>
                                </div>
                            ` : m.isLive ? `
                                <div class="mb-3.5 px-3 py-1.5 rounded-2xl bg-gradient-to-r from-emerald-500/20 via-wpGreen/15 to-wpCard border border-emerald-500/40 flex items-center justify-between cursor-pointer hover:border-emerald-400 transition group shadow-sm" onclick="openStatsModal('${m.id}')" title="Proyección en vivo ajustada al marcador actual en tiempo real">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                        <span class="text-[10px] font-black uppercase text-emerald-400 font-outfit tracking-wider">🟢 Proyección Final en Vivo (${m.period || m.minute}):</span>
                                        <span class="text-sm font-black text-white font-bebas tracking-wide group-hover:text-emerald-400 transition">${mScorePreds.recommendedScore}</span>
                                        <span class="text-[10px] text-emerald-300 font-outfit font-bold">(${mScorePreds.recommendedProb}% prob. @${mScorePreds.recommendedOdds.toFixed(2)})</span>
                                    </div>
                                    <span class="text-[10px] text-emerald-400 font-bold font-outfit flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                                        <span>Ver proyección</span> <span>→</span>
                                    </span>
                                </div>
                            ` : `
                                <div class="mb-3.5 px-3 py-1.5 rounded-2xl bg-gradient-to-r from-wpGreen/15 via-emerald-500/10 to-wpCard border border-wpGreen/30 flex items-center justify-between cursor-pointer hover:border-wpGreen transition group" onclick="openStatsModal('${m.id}')" title="Haz clic para ver el cálculo completo de marcadores más probables">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-wpGreen animate-pulse"></span>
                                        <span class="text-[10px] font-black uppercase text-wpGreen font-outfit tracking-wider">🎯 Sugerencia IA (Poisson):</span>
                                        <span class="text-sm font-black text-white font-bebas tracking-wide group-hover:text-wpGreen transition">${mScorePreds.recommendedScore}</span>
                                        <span class="text-[10px] text-slate-300 font-outfit font-bold">(${mScorePreds.recommendedProb}% prob. @${mScorePreds.recommendedOdds.toFixed(2)})</span>
                                    </div>
                                    <span class="text-[10px] text-wpGreen font-bold font-outfit flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                                        <span>Ver análisis</span> <span>→</span>
                                    </span>
                                </div>
                            `}
                        ` : ''}

                        <!-- Teams & Live Scores Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center mb-4 cursor-pointer group" onclick="openStatsModal('${m.id}')" title="Haz clic para ver estadísticas y sugerencia de marcadores">
                            
                            <!-- Team 1 & Score -->
                            <div class="md:col-span-5 flex items-center justify-between md:justify-start gap-3">
                                <div class="flex items-center gap-2.5">
                                    ${m.homeLogo ? `
                                        <img src="${m.homeLogo}" alt="${m.home}" class="w-8 h-8 object-contain">
                                    ` : `
                                        <div class="w-8 h-8 rounded-full bg-wpCard border border-wpBorder group-hover:border-wpGreen flex items-center justify-center text-sm font-black text-white transition">
                                            ${m.home.charAt(0)}
                                        </div>
                                    `}
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-outfit font-extrabold text-sm sm:text-base text-white group-hover:text-wpGreen transition truncate max-w-[170px] sm:max-w-[210px] block ${isHomeFav ? 'text-rose-300' : ''}">
                                                ${m.home}
                                            </span>
                                            <button onclick="toggleFavoriteTeam(event, '${escapedHome}')" class="text-xs hover:scale-125 transition p-0.5" title="${isHomeFav ? 'Quitar de favoritos' : 'Agregar a equipos favoritos'}">
                                                ${isHomeFav ? '<span class="text-rose-500 text-sm">❤️</span>' : '<span class="text-slate-600 hover:text-rose-400 text-sm">🤍</span>'}
                                            </button>
                                        </div>
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

                            <!-- VS / Center Status & Partial Score Box -->
                            <div class="hidden md:flex md:col-span-2 flex-col items-center justify-center text-center p-1.5 rounded-2xl bg-wpDark border border-wpBorder/80 shadow-inner">
                                ${m.isLive ? `
                                    <span class="text-[10px] font-black text-rose-400 font-outfit uppercase flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                        ${m.period || ('VIVO ' + m.minute)}
                                    </span>
                                    ${m.partialScore ? `
                                        <span class="text-[10px] font-black text-emerald-300 font-outfit bg-wpCard px-2 py-0.5 rounded-md border border-emerald-500/30 mt-0.5">
                                            ${m.partialScore}
                                        </span>
                                    ` : ''}
                                ` : m.isFinished ? `
                                    <span class="text-[10px] font-black text-slate-400 font-outfit uppercase">FINAL</span>
                                    ${m.partialScore ? `
                                        <span class="text-[10px] font-bold text-slate-300 font-outfit bg-wpCard px-1.5 py-0.5 rounded border border-slate-700 mt-0.5">
                                            ${m.partialScore}
                                        </span>
                                    ` : ''}
                                ` : `
                                    <span class="font-bebas text-lg text-slate-400 tracking-widest leading-none">VS</span>
                                    <span class="text-[9px] text-slate-500 font-outfit">H2H & IA 📊</span>
                                `}
                            </div>

                            <!-- Team 2 & Score -->
                            <div class="md:col-span-5 flex items-center justify-between md:justify-end gap-3">
                                <span class="font-bebas text-2xl sm:text-3xl text-wpGreen px-2.5 py-0.5 rounded-lg bg-wpCard border border-wpBorder md:mr-auto match-score-${m.id}-away">
                                    ${m.awayScore}
                                </span>
                                <div class="flex items-center gap-2.5 text-right">
                                    <div>
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button onclick="toggleFavoriteTeam(event, '${escapedAway}')" class="text-xs hover:scale-125 transition p-0.5" title="${isAwayFav ? 'Quitar de favoritos' : 'Agregar a equipos favoritos'}">
                                                ${isAwayFav ? '<span class="text-rose-500 text-sm">❤️</span>' : '<span class="text-slate-600 hover:text-rose-400 text-sm">🤍</span>'}
                                            </button>
                                            <span class="font-outfit font-extrabold text-sm sm:text-base text-white group-hover:text-wpGreen transition truncate max-w-[170px] sm:max-w-[210px] block ${isAwayFav ? 'text-rose-300' : ''}">
                                                ${m.away}
                                            </span>
                                        </div>
                                        ${m.h2h ? `
                                            <div class="flex items-center justify-end gap-1 mt-0.5">
                                                ${m.h2h.awayStreak.map(st => `
                                                    <span class="text-[9px] font-black px-1 rounded ${st === 'V' ? 'bg-emerald-500/20 text-emerald-400' : st === 'E' ? 'bg-amber-500/20 text-amber-400' : 'bg-rose-500/20 text-rose-400'}">${st}</span>
                                                `).join('')}
                                            </div>
                                        ` : ''}
                                    </div>
                                    ${m.awayLogo ? `
                                        <img src="${m.awayLogo}" alt="${m.away}" class="w-8 h-8 object-contain">
                                    ` : `
                                        <div class="w-8 h-8 rounded-full bg-wpCard border border-wpBorder group-hover:border-wpGreen flex items-center justify-center text-sm font-black text-white transition">
                                            ${m.away.charAt(0)}
                                        </div>
                                    `}
                                </div>
                            </div>

                            <!-- Mobile Partial Score Bar -->
                            ${(m.isLive || m.isFinished) && m.partialScore ? `
                                <div class="md:hidden col-span-1 flex items-center justify-between px-3 py-1.5 rounded-xl bg-wpDark border border-wpBorder/70 text-[11px] font-outfit">
                                    <span class="text-slate-400 font-bold flex items-center gap-1">
                                        <span class="${m.isLive ? 'text-rose-400 font-black' : 'text-slate-400'}">${m.period || (m.isLive ? 'VIVO' : 'FINAL')}:</span>
                                    </span>
                                    <span class="text-emerald-300 font-black px-2 py-0.2 rounded bg-wpCard border border-wpBorder">${m.partialScore}</span>
                                </div>
                            ` : ''}
                        </div>



                        <!-- Main Betting Market Grid (2-Way Moneyline for Baseball/Basketball/NFL vs 3-Way 1X2 for Soccer) -->
                        ${m.hasDraw ? `
                            <div class="grid grid-cols-3 gap-2 sm:gap-3 mb-3">
                                <button onclick="toggleBet('${m.id}', '1X2', '1', ${m.odds['1X2']['1']}, '${m.home}')" 
                                        class="odd-btn flex items-center justify-between p-2.5 sm:p-3 rounded-2xl bg-wpCard hover:bg-wpCardHover border border-wpBorder text-left ${isSelected1 ? 'selected' : ''}">
                                    <div class="truncate mr-1">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block font-outfit">1 (${m.home.split(' ')[0]})</span>
                                    </div>
                                    <span class="odd-val font-bebas text-lg sm:text-xl text-wpGreen font-black">${m.odds['1X2']['1'].toFixed(2)}</span>
                                </button>

                                <button onclick="toggleBet('${m.id}', '1X2', 'X', ${m.odds['1X2']['X'] || 3.30}, 'Empate')" 
                                        class="odd-btn flex items-center justify-between p-2.5 sm:p-3 rounded-2xl bg-wpCard hover:bg-wpCardHover border border-wpBorder text-left ${isSelectedX ? 'selected' : ''}">
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block font-outfit">X (Empate)</span>
                                    </div>
                                    <span class="odd-val font-bebas text-lg sm:text-xl text-wpGreen font-black">${m.odds['1X2']['X'] ? m.odds['1X2']['X'].toFixed(2) : '3.30'}</span>
                                </button>

                                <button onclick="toggleBet('${m.id}', '1X2', '2', ${m.odds['1X2']['2']}, '${m.away}')" 
                                        class="odd-btn flex items-center justify-between p-2.5 sm:p-3 rounded-2xl bg-wpCard hover:bg-wpCardHover border border-wpBorder text-left ${isSelected2 ? 'selected' : ''}">
                                    <div class="truncate mr-1">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block font-outfit">2 (${m.away.split(' ')[0]})</span>
                                    </div>
                                    <span class="odd-val font-bebas text-lg sm:text-xl text-wpGreen font-black">${m.odds['1X2']['2'].toFixed(2)}</span>
                                </button>
                            </div>
                        ` : `
                            <div class="grid grid-cols-2 gap-2 sm:gap-3 mb-3">
                                <button onclick="toggleBet('${m.id}', '1X2', '1', ${m.odds['1X2']['1']}, '${m.home}')" 
                                        class="odd-btn flex items-center justify-between p-3 rounded-2xl bg-wpCard hover:bg-wpCardHover border border-wpBorder text-left ${isSelected1 ? 'selected' : ''}">
                                    <div class="truncate mr-1">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block font-outfit">GANADOR LOCAL (${m.home.split(' ')[0]})</span>
                                    </div>
                                    <span class="odd-val font-bebas text-xl text-wpGreen font-black">${m.odds['1X2']['1'].toFixed(2)}</span>
                                </button>

                                <button onclick="toggleBet('${m.id}', '1X2', '2', ${m.odds['1X2']['2']}, '${m.away}')" 
                                        class="odd-btn flex items-center justify-between p-3 rounded-2xl bg-wpCard hover:bg-wpCardHover border border-wpBorder text-left ${isSelected2 ? 'selected' : ''}">
                                    <div class="truncate mr-1">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block font-outfit">GANADOR VISITANTE (${m.away.split(' ')[0]})</span>
                                    </div>
                                    <span class="odd-val font-bebas text-xl text-wpGreen font-black">${m.odds['1X2']['2'].toFixed(2)}</span>
                                </button>
                            </div>
                        `}

                        <!-- Secondary Markets Toggle -->
                        <div class="pt-2 border-t border-wpBorder/40 flex items-center justify-between text-xs">
                            <button onclick="toggleMoreMarkets('${m.id}')" class="text-slate-400 hover:text-wpGreen font-outfit font-bold flex items-center gap-1.5 transition">
                                <span>+ Mercados (🎯 Marcador Exacto, 🚩 Córners, 👟 Goleador, 🟨 Tarjetas)</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transform transition-transform" id="arrow-${m.id}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <span class="text-[10px] text-slate-500 font-semibold font-outfit">Poisson AI & API-Sports</span>
                        </div>

                        <!-- Expandable Extra Markets -->
                        <div id="extra-markets-${m.id}" class="hidden pt-3 mt-3 border-t border-wpBorder/40 space-y-3.5">
                            
                            <!-- 🎯 Marcadores Exactos Más Probables (Poisson AI) -->
                            ${(mScorePreds && mScorePreds.topScores && mScorePreds.topScores.length > 0) ? `
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-[11px] font-bold uppercase text-wpGreen font-outfit flex items-center gap-1">🎯 Marcador Exacto Sugerido (Poisson AI)</span>
                                        <span class="text-[10px] text-slate-400 font-outfit">Confianza: ${mScorePreds.confidencePercent}%</span>
                                    </div>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                        ${mScorePreds.topScores.map(ts => {
                                            const isSel = isBetSelected(m.id, 'exact_score', ts.score);
                                            return `
                                                <button onclick="toggleBet('${m.id}', 'exact_score', '${ts.score}', ${ts.odds}, 'Marcador: ${ts.score}')" 
                                                        class="odd-btn flex items-center justify-between p-2 rounded-xl bg-wpCard hover:bg-wpCardHover border border-wpBorder ${isSel ? 'selected' : ''}">
                                                    <div class="text-left truncate mr-1">
                                                        <span class="text-xs font-bold text-white block truncate">${ts.score}</span>
                                                        <span class="text-[9px] text-slate-400 block font-outfit">${ts.probability}% prob</span>
                                                    </div>
                                                    <span class="odd-val font-bebas text-base text-wpGreen font-black">${ts.odds.toFixed(2)}</span>
                                                </button>
                                            `;
                                        }).join('')}
                                    </div>
                                </div>
                            ` : ''}

                            <!-- 🚩 Tiros de Esquina (Corners) -->
                            ${m.odds['corners'] ? `
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-[11px] font-bold uppercase text-emerald-400 font-outfit flex items-center gap-1">🚩 Tiros de Esquina (Córners)</span>
                                        <span class="text-[10px] text-slate-400 font-outfit">Histórico: ~${m.h2h?.avgCorners || '9.2 Córners'}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        ${Object.entries(m.odds['corners']).map(([key, odd]) => {
                                            const isSel = isBetSelected(m.id, 'corners', key);
                                            return `
                                                <button onclick="toggleBet('${m.id}', 'corners', '${key}', ${odd}, '${key}')" 
                                                        class="odd-btn flex items-center justify-between p-2 rounded-xl bg-wpCard hover:bg-wpCardHover border border-wpBorder ${isSel ? 'selected' : ''}">
                                                    <span class="text-xs text-slate-300 font-outfit">${key}</span>
                                                    <span class="odd-val font-bebas text-base text-wpGreen">${odd.toFixed(2)}</span>
                                                </button>
                                            `;
                                        }).join('')}
                                    </div>
                                </div>
                            ` : ''}

                            <!-- 👟 Goles por Jugador (Goleador en Cualquier Momento / Player Props) -->
                            ${(m.odds['scorers'] && m.odds['scorers'].length > 0) ? `
                                <div>
                                    <span class="text-[11px] font-bold uppercase text-amber-400 font-outfit block mb-1.5">👟 Goleador / Jugador Anota en Cualquier Momento</span>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        ${m.odds['scorers'].map(p => {
                                            const pKey = `Gol: ${p.name}`;
                                            const isSel = isBetSelected(m.id, 'scorers', pKey);
                                            return `
                                                <button onclick="toggleBet('${m.id}', 'scorers', '${pKey}', ${p.odd}, '${p.name} (${p.team})')" 
                                                        class="odd-btn flex items-center justify-between p-2.5 rounded-xl bg-wpCard hover:bg-wpCardHover border border-wpBorder ${isSel ? 'selected' : ''}">
                                                    <div class="text-left truncate mr-2">
                                                        <span class="text-xs font-bold text-white block truncate">${p.name}</span>
                                                        <span class="text-[10px] text-slate-400 block">${p.team} · ${p.goals || ''}</span>
                                                    </div>
                                                    <span class="odd-val font-bebas text-base text-wpGreen font-black">${p.odd.toFixed(2)}</span>
                                                </button>
                                            `;
                                        }).join('')}
                                    </div>
                                </div>
                            ` : ''}

                            <!-- 🟨 Tarjetas & Totales -->
                            <div>
                                <span class="text-[11px] font-bold uppercase text-slate-400 font-outfit block mb-1.5">${m.sport === 'beisbol' ? 'Total de Carreras (+ / -)' : (m.sport === 'baloncesto' ? 'Total de Puntos (+ / -)' : 'Total de Goles (+ / -)')}</span>
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
                                <span class="text-[11px] font-bold uppercase text-slate-400 font-outfit block mb-1.5">${m.sport === 'futbol' ? 'Ambos Equipos Anotan' : 'Hándicap / Spread Oficial'}</span>
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
           6. POISSON PREDICTION ALGORITHM (CLIENT/FALLBACK HELPER) & STATS MODAL
           ========================================================================= */
        function getOrComputeScorePredictions(match) {
            const isFinished = match.isFinished || (match.minute === 'FT' || match.minute === 'Finalizado');
            const isLive = match.isLive && !isFinished;
            const curH = parseInt(match.homeScore) || 0;
            const curA = parseInt(match.awayScore) || 0;

            // 1. Si el partido ya FINALIZÓ: El marcador es 100% el resultado final oficial
            if (isFinished) {
                const finalScore = `${curH} - ${curA}`;
                return {
                    expectedGoalsHome: curH,
                    expectedGoalsAway: curA,
                    totalExpectedGoals: curH + curA,
                    topScores: [{
                        score: finalScore,
                        homeGoals: curH,
                        awayGoals: curA,
                        probability: 100.0,
                        odds: 1.01,
                        tag: '🏁 Marcador Final Oficial',
                        type: curH > curA ? 'home_win' : (curH === curA ? 'draw' : 'away_win'),
                        typeLabel: curH > curA ? `Victoria ${match.home}` : (curH === curA ? 'Empate' : `Victoria ${match.away}`),
                        isTopRecommendation: true
                    }],
                    recommendedScore: finalScore,
                    recommendedOdds: 1.01,
                    recommendedProb: 100.0,
                    confidencePercent: 100,
                    isLive: false,
                    isFinished: true,
                    analysis: `Partido concluido con resultado oficial definitivo de ${finalScore}.`
                };
            }

            // Si es pre-partido y ya viene calculado del backend, usarlo
            if (!isLive && match.score_predictions && match.score_predictions.topScores && match.score_predictions.topScores.length > 0) {
                return match.score_predictions;
            }

            const avgGoals = parseFloat(match.h2h?.avgGoals) || 2.6;
            const probHome = match.h2h?.homeWinProb || 48;
            const probDraw = match.h2h?.drawProb || 26;
            const probAway = match.h2h?.awayWinProb || 26;

            const homeRatio = Math.max(0.25, Math.min(0.75, (probHome + (probDraw * 0.35)) / 100));
            const awayRatio = 1 - homeRatio;

            const baseLambdaHome = Math.max(0.65, Math.min(3.8, parseFloat(((avgGoals * homeRatio) * 1.12).toFixed(2))));
            const baseLambdaAway = Math.max(0.45, Math.min(3.4, parseFloat(((avgGoals * awayRatio) * 0.92).toFixed(2))));

            function fact(n) { return n <= 1 ? 1 : n * fact(n - 1); }
            function poisson(lambda, k) { return (Math.pow(lambda, k) * Math.exp(-lambda)) / fact(k); }

            // 2. Si está EN VIVO, calcular únicamente la expectativa de goles RESTANTES en los minutos que quedan
            let timeRemainingRatio = 1.0;
            let elapsedMin = 45;
            if (isLive) {
                const numMatch = (match.minute || '').toString().replace(/[^0-9]/g, '');
                elapsedMin = parseInt(numMatch) || 45;
                timeRemainingRatio = Math.max(0.04, Math.min(1.0, (90 - elapsedMin) / 90));
            }

            const remLambdaH = isLive ? Math.max(0.05, baseLambdaHome * timeRemainingRatio) : baseLambdaHome;
            const remLambdaA = isLive ? Math.max(0.05, baseLambdaAway * timeRemainingRatio) : baseLambdaAway;

            const startH = isLive ? curH : 0;
            const startA = isLive ? curA : 0;
            const maxExtra = isLive ? 4 : 5;

            const scores = [];
            let totalSum = 0;
            for (let kH = 0; kH <= maxExtra; kH++) {
                for (let kA = 0; kA <= maxExtra; kA++) {
                    const p = poisson(remLambdaH, kH) * poisson(remLambdaA, kA);
                    totalSum += p;

                    const finalH = startH + kH;
                    const finalA = startA + kA;

                    scores.push({
                        score: `${finalH} - ${finalA}`,
                        homeGoals: finalH,
                        awayGoals: finalA,
                        rawProb: p,
                        type: finalH > finalA ? 'home_win' : (finalH === finalA ? 'draw' : 'away_win'),
                        typeLabel: finalH > finalA ? `Victoria ${match.home}` : (finalH === finalA ? 'Empate' : `Victoria ${match.away}`)
                    });
                }
            }

            scores.forEach(s => {
                const norm = (s.rawProb / Math.max(0.001, totalSum)) * 100;
                s.probability = parseFloat(norm.toFixed(1));
                s.odds = parseFloat(Math.max(1.12, Math.min(85.0, (100 / Math.max(0.5, norm)) * 1.08)).toFixed(2));
            });

            scores.sort((a, b) => b.rawProb - a.rawProb);
            const topScores = scores.slice(0, 6);
            const tags = [
                isLive ? '🟢 Proyección Final Más Probable' : '🔥 Marcador Más Probable',
                isLive ? '⚡ Segunda Opción en Vivo' : '⚡ Segunda Opción Fuerte',
                isLive ? '💡 Opción con Gol Adicional' : '💡 Opción de Alto Valor',
                '🛡️ Pronóstico Cerrado',
                '🎯 Alternativa Táctica',
                '🎲 Posible Sorpresa'
            ];
            topScores.forEach((ts, idx) => {
                ts.tag = tags[idx] || 'Pronóstico';
                ts.isTopRecommendation = (idx === 0);
            });

            const bestScore = topScores[0].score;
            const bestProb = topScores[0].probability;
            const bestOdds = topScores[0].odds;
            const confidence = Math.min(96, Math.round(48 + (bestProb * 1.9)));

            const analysis = isLive 
                ? `Con el marcador actual (${curH} - ${curA}) al minuto ${match.minute || elapsedMin + "'"}, el modelo proyecta ${remLambdaH.toFixed(2)} goles esperados adicionales para ${match.home} y ${remLambdaA.toFixed(2)} para ${match.away}. El marcador final proyectado más probable es ${bestScore} (${bestProb}% prob. cuota @${bestOdds.toFixed(2)}).`
                : `El modelo predictivo de Poisson proyecta una expectativa de ${baseLambdaHome} goles para ${match.home} y ${baseLambdaAway} para ${match.away}. Basado en estadísticas previas y factor de localía, el marcador más probable es ${bestScore} (${bestProb}% prob. cuota @${bestOdds.toFixed(2)}).`;

            return {
                expectedGoalsHome: isLive ? parseFloat((curH + remLambdaH).toFixed(2)) : baseLambdaHome,
                expectedGoalsAway: isLive ? parseFloat((curA + remLambdaA).toFixed(2)) : baseLambdaAway,
                totalExpectedGoals: isLive ? parseFloat((curH + curA + remLambdaH + remLambdaA).toFixed(2)) : parseFloat((baseLambdaHome + baseLambdaAway).toFixed(2)),
                topScores: topScores,
                recommendedScore: bestScore,
                recommendedOdds: bestOdds,
                recommendedProb: bestProb,
                confidencePercent: confidence,
                isLive: isLive,
                isFinished: false,
                analysis: analysis
            };
        }


        function openStatsModal(matchId) {
            playSound('click');
            const match = matches.find(m => m.id === matchId);
            if (!match) return;

            const modal = document.getElementById('statsModal');
            const content = document.getElementById('statsModalContent');
            const leagueLabel = document.getElementById('statsModalLeague');
            if (!modal || !content) return;

            if (leagueLabel) leagueLabel.innerText = `${match.league} · ${match.startTime || 'Programado'}`;

            const h2h = match.h2h || {
                homeWins: 5, draws: 0, awayWins: 4,
                lastMatches: [{ date: 'Historial previo', home: match.home, away: match.away, score: (match.sport === 'beisbol' ? '5 - 3' : '2 - 1') }],
                homeStreak: ['V', 'E', 'V', 'D', 'V'],
                awayStreak: ['V', 'D', 'V', 'E', 'D'],
                homeWinProb: 55, drawProb: 0, awayWinProb: 45,
                avgGoals: (match.sport === 'beisbol' ? '8.4 Carreras' : '2.5 Goles'),
                avgCorners: '9.4 Córners',
                avgCards: '4.8 Tarjetas',
                bttsProb: 55,
                topScorers: []
            };

            const scorePreds = getOrComputeScorePredictions(match);

            content.innerHTML = `
                <!-- Encabezado de Equipos y Racha -->
                <div class="bg-wpCard rounded-2xl p-4 sm:p-5 border border-wpBorder">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-3">
                            ${match.homeLogo ? `<img src="${match.homeLogo}" class="w-10 h-10 object-contain">` : `
                                <div class="w-10 h-10 rounded-2xl bg-wpDark border border-wpBorder flex items-center justify-center font-black text-white text-lg">
                                    ${match.home.charAt(0)}
                                </div>
                            `}
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
                            ${match.awayLogo ? `<img src="${match.awayLogo}" class="w-10 h-10 object-contain">` : `
                                <div class="w-10 h-10 rounded-2xl bg-wpDark border border-wpBorder flex items-center justify-center font-black text-white text-lg">
                                    ${match.away.charAt(0)}
                                </div>
                            `}
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-outfit font-bold">
                            <span class="text-emerald-400">${match.home.split(' ')[0]}: ${h2h.homeWinProb}%</span>
                            ${match.hasDraw ? `<span class="text-amber-400">Empate: ${h2h.drawProb}%</span>` : ''}
                            <span class="text-cyan-400">${match.away.split(' ')[0]}: ${h2h.awayWinProb}%</span>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-wpDark flex overflow-hidden">
                            <div style="width: ${h2h.homeWinProb}%" class="bg-emerald-500 h-full"></div>
                            ${match.hasDraw ? `<div style="width: ${h2h.drawProb}%" class="bg-amber-400 h-full"></div>` : ''}
                            <div style="width: ${h2h.awayWinProb}%" class="bg-cyan-400 h-full"></div>
                        </div>
                    </div>

                    ${(match.isLive || match.isFinished || match.partialScore) ? `
                        <div class="mt-3 pt-3 border-t border-wpBorder/60 flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black uppercase ${match.isLive ? 'text-rose-400' : 'text-slate-300'} font-outfit flex items-center gap-1.5">
                                    ${match.isLive ? '<span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>' : '🏁'}
                                    <span>${match.period || (match.isLive ? 'EN VIVO ' + match.minute : 'FINALIZADO')}</span>
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-outfit">
                                <span class="text-slate-400 font-bold">⏱️ Tiempo Parcial:</span>
                                <span class="px-2.5 py-0.5 rounded-lg bg-wpDark border border-wpBorder text-emerald-400 font-black">
                                    ${match.partialScore || `${match.homeScore} - ${match.awayScore}`}
                                </span>
                            </div>
                        </div>
                    ` : ''}
                </div>


                <!-- 🔥 MÓDULO INTELIGENTE DE SUGERENCIA DE MARCADORES (POISSON AI) -->
                <div class="bg-gradient-to-br from-wpCard via-[#132235] to-wpCard border border-wpGreen/30 rounded-3xl p-5 sm:p-6 shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-36 h-36 bg-wpGreen/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4 pb-3 border-b border-wpBorder/60">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-wpGreen/20 border border-wpGreen/40 text-wpGreen flex items-center justify-center text-lg glow-green-sm">
                                🎯
                            </div>
                            <div>
                                <h4 class="font-bebas text-2xl text-white tracking-wide">SUGERENCIA DE MARCADORES (DISTRIBUCIÓN DE POISSON)</h4>
                                <p class="text-[11px] text-slate-400 font-outfit">Probabilidades calculadas en base al promedio de goles de los últimos partidos</p>
                            </div>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-xs font-bold font-outfit">
                            <span>Índice de Confianza:</span>
                            <span class="font-black text-white">${scorePreds.confidencePercent}%</span>
                        </div>
                    </div>

                    <!-- Tarjeta del Marcador Recomendado Principal -->
                    <div class="bg-wpDark/90 border-2 border-wpGreen/50 rounded-2xl p-4 mb-5 relative overflow-hidden glow-green-sm">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-4 text-center sm:text-left">
                                <div class="px-4 py-2 rounded-2xl bg-gradient-to-r from-wpGreen to-wpGreenDark text-wpDark font-bebas text-3xl sm:text-4xl font-black shadow-lg">
                                    ${scorePreds.recommendedScore}
                                </div>
                                <div>
                                    <div class="inline-flex items-center gap-1 text-[11px] font-black uppercase text-wpGreen font-outfit">
                                        <span>🔥 MARCADOR MÁS PROBABLE</span>
                                    </div>
                                    <div class="text-xs text-slate-300 font-outfit">
                                        Probabilidad estimada: <span class="font-bold text-white">${scorePreds.recommendedProb}%</span> · Cuota: <span class="font-bold text-wpGreen">@${scorePreds.recommendedOdds.toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>
                            <button onclick="toggleBet('${match.id}', 'exact_score', '${scorePreds.recommendedScore}', ${scorePreds.recommendedOdds}, 'Marcador: ${scorePreds.recommendedScore}'); closeStatsModal();" 
                                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-wpGreen to-wpGreenDark text-wpDark font-outfit font-black text-xs uppercase tracking-wider hover:brightness-110 active:scale-95 transition shadow flex items-center justify-center gap-1.5">
                                <span>⚡</span>
                                <span>Apostar a ${scorePreds.recommendedScore} (@${scorePreds.recommendedOdds.toFixed(2)})</span>
                            </button>
                        </div>
                    </div>

                    <!-- Ranking de Marcadores Más Probables con Barras -->
                    <div class="mb-5">
                        <span class="text-xs font-bold uppercase text-slate-300 font-outfit block mb-3">📊 Top 6 Marcadores Proyectados:</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            ${scorePreds.topScores.map((ts, idx) => {
                                const isSelected = isBetSelected(match.id, 'exact_score', ts.score);
                                return `
                                    <div class="bg-wpCard hover:border-slate-600 border ${isSelected ? 'border-wpGreen bg-wpGreen/10' : 'border-wpBorder'} rounded-2xl p-3 flex flex-col justify-between gap-2 transition">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="w-5 h-5 rounded-full bg-wpDark text-wpGreen border border-wpBorder flex items-center justify-center text-[10px] font-black font-bebas">
                                                    #${idx + 1}
                                                </span>
                                                <span class="font-bebas text-xl text-white tracking-wider">${ts.score}</span>
                                                <span class="text-[10px] text-slate-400 font-outfit">${ts.typeLabel}</span>
                                            </div>
                                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-md ${idx === 0 ? 'bg-wpGreen/20 text-wpGreen' : idx === 1 ? 'bg-cyan-500/20 text-cyan-300' : 'bg-slate-700/60 text-slate-300'} font-outfit">
                                                ${ts.tag}
                                            </span>
                                        </div>

                                        <div class="space-y-1">
                                            <div class="flex justify-between text-[11px] font-outfit">
                                                <span class="text-slate-400">Probabilidad: <strong class="text-white">${ts.probability}%</strong></span>
                                                <span class="text-wpGreen font-bold font-bebas text-sm">Cuota @${ts.odds.toFixed(2)}</span>
                                            </div>
                                            <div class="w-full h-2 rounded-full bg-wpDark overflow-hidden">
                                                <div style="width: ${Math.min(100, ts.probability * 3.8)}%" class="h-full rounded-full ${idx === 0 ? 'bg-wpGreen' : idx === 1 ? 'bg-cyan-400' : 'bg-blue-500'}"></div>
                                            </div>
                                        </div>

                                        <button onclick="toggleBet('${match.id}', 'exact_score', '${ts.score}', ${ts.odds}, 'Marcador: ${ts.score}'); closeStatsModal();" 
                                                class="w-full mt-1 py-1.5 rounded-xl border ${isSelected ? 'bg-wpGreen text-wpDark font-black border-wpGreen' : 'bg-wpDark hover:bg-wpGreen hover:text-wpDark border-wpBorder text-slate-300'} text-xs font-bold font-outfit transition flex items-center justify-center gap-1">
                                            <span>${isSelected ? '✓ Seleccionado' : '+ Elegir Marcador'}</span>
                                            <span class="font-bebas text-sm font-black">(${ts.odds.toFixed(2)})</span>
                                        </button>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>

                    <!-- Desglose de Expectativa de Goles (xG) y Análisis -->
                    <div class="bg-wpDark/70 border border-wpBorder rounded-2xl p-4 text-xs font-outfit space-y-2">
                        <div class="flex items-center gap-2 text-amber-400 font-bold">
                            <span>💡</span>
                            <span>Análisis Probabilístico de Poisson:</span>
                        </div>
                        <p class="text-slate-300 leading-relaxed font-light">
                            ${scorePreds.analysis}
                        </p>
                        <div class="grid grid-cols-3 gap-2 pt-2.5 border-t border-wpBorder/40 text-center">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase block">xG Local (${match.home.split(' ')[0]})</span>
                                <span class="font-bebas text-lg text-emerald-400">${scorePreds.expectedGoalsHome}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase block">Total Goles Estimado</span>
                                <span class="font-bebas text-lg text-wpYellow">${scorePreds.totalExpectedGoals}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase block">xG Visita (${match.away.split(' ')[0]})</span>
                                <span class="font-bebas text-lg text-cyan-400">${scorePreds.expectedGoalsAway}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metrics Grid: Goles, Córners, Tarjetas, Ambos Anotan -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="bg-wpCard rounded-2xl p-3 border border-wpBorder text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 font-outfit block">${match.sport === 'beisbol' ? 'Prom. Carreras' : (match.sport === 'baloncesto' ? 'Prom. Puntos' : 'Prom. Goles')}</span>
                        <span class="font-bebas text-2xl text-wpGreen font-black">${h2h.avgGoals || '2.6'}</span>
                    </div>
                    <div class="bg-wpCard rounded-2xl p-3 border border-wpBorder text-center">
                        <span class="text-[10px] uppercase font-bold text-emerald-400 font-outfit block">🚩 Prom. Córners</span>
                        <span class="font-bebas text-2xl text-emerald-400 font-black">${h2h.avgCorners || '9.4'}</span>
                    </div>
                    <div class="bg-wpCard rounded-2xl p-3 border border-wpBorder text-center">
                        <span class="text-[10px] uppercase font-bold text-amber-400 font-outfit block">🟨 Prom. Tarjetas</span>
                        <span class="font-bebas text-2xl text-amber-400 font-black">${h2h.avgCards || '4.8'}</span>
                    </div>
                    <div class="bg-wpCard rounded-2xl p-3 border border-wpBorder text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 font-outfit block">Ambos Anotan</span>
                        <span class="font-bebas text-2xl text-wpYellow font-black">${h2h.bttsProb || '55'}%</span>
                    </div>
                </div>

                <!-- Goleadores y Figuras a Seguir -->
                ${(h2h.topScorers && h2h.topScorers.length > 0) ? `
                    <div class="bg-wpCard rounded-2xl p-4 border border-wpBorder">
                        <h5 class="font-bebas text-xl text-amber-400 tracking-wide mb-3 flex items-center gap-2">
                            <span>⭐</span> FIGURAS Y GOLEADORES DESTACADOS (PRONÓSTICO INDIVIDUAL)
                        </h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            ${h2h.topScorers.map(p => `
                                <div class="bg-wpDark/80 border border-wpBorder rounded-xl p-2.5 flex items-center justify-between">
                                    <div>
                                        <span class="font-outfit font-extrabold text-white text-xs block">${p.name}</span>
                                        <span class="text-[10px] text-slate-400 font-outfit">${p.team} · ${p.pos} (${p.goals})</span>
                                    </div>
                                    <button onclick="toggleBet('${match.id}', 'scorers', 'Gol: ${p.name}', ${p.odd}, '${p.name}'); closeStatsModal();" 
                                            class="px-3 py-1 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-wpDark font-bebas text-sm font-black transition">
                                        Gol @ ${p.odd.toFixed(2)}
                                    </button>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}

                <!-- Historial Directo -->
                <div>
                    <h5 class="font-bebas text-xl text-white tracking-wide mb-3 flex items-center gap-2">
                        <span>⚔️</span> HISTORIAL DIRECTO DE ENFRENTAMIENTOS
                    </h5>
                    <div class="space-y-2">
                        ${h2h.lastMatches.map(lm => `
                            <div class="bg-wpCard border border-wpBorder rounded-2xl p-3 flex items-center justify-between text-xs">
                                <span class="text-slate-400 font-outfit text-[11px]">${lm.date}</span>
                                <div class="flex items-center gap-2 font-bold">
                                    <span class="text-white">${lm.home}</span>
                                    <span class="font-bebas text-base px-2 py-0.5 bg-wpDark rounded-lg text-wpGreen border border-wpBorder">${lm.score}</span>
                                    <span class="text-white">${lm.away}</span>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>

                <div class="bg-wpCard/70 border border-wpBorder rounded-2xl p-4">
                    <span class="text-xs font-bold text-slate-300 font-outfit block mb-2">⚡ Pronosticar Resultado 1X2 de este Partido:</span>
                    <div class="grid grid-cols-${match.hasDraw ? '3' : '2'} gap-2">
                        <button onclick="toggleBet('${match.id}', '1X2', '1', ${match.odds['1X2']['1']}, '${match.home}'); closeStatsModal();" 
                                class="p-2 rounded-xl bg-wpDark hover:bg-wpGreen hover:text-wpDark border border-wpBorder text-xs font-bold font-outfit transition text-center">
                            1 (${match.odds['1X2']['1'].toFixed(2)})
                        </button>
                        ${match.hasDraw ? `
                            <button onclick="toggleBet('${match.id}', '1X2', 'X', ${match.odds['1X2']['X']}, 'Empate'); closeStatsModal();" 
                                    class="p-2 rounded-xl bg-wpDark hover:bg-wpGreen hover:text-wpDark border border-wpBorder text-xs font-bold font-outfit transition text-center">
                                X (${match.odds['1X2']['X'].toFixed(2)})
                            </button>
                        ` : ''}
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
                if (sameMarketIndex > -1) selectedBets.splice(sameMarketIndex, 1);

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

                    <div class="grid grid-cols-2 gap-2 my-3 p-1 bg-wpCard rounded-2xl border border-wpBorder">
                        <button onclick="setBetMode('single')" class="py-2 text-xs font-black font-outfit rounded-xl transition ${betMode === 'single' ? 'bg-wpGreen text-wpDark shadow' : 'text-slate-400 hover:text-white'}">
                            Sencilla
                        </button>
                        <button onclick="setBetMode('parlay')" class="py-2 text-xs font-black font-outfit rounded-xl transition ${betMode === 'parlay' ? 'bg-wpGreen text-wpDark shadow' : 'text-slate-400 hover:text-white'}">
                            Combinada (Parlay)
                        </button>
                    </div>

                    ${count === 0 ? `
                        <div class="py-12 text-center text-slate-400 flex flex-col items-center justify-center">
                            <div class="w-14 h-14 rounded-2xl bg-wpCard border border-wpBorder flex items-center justify-center text-2xl mb-3">
                                🎟️
                            </div>
                            <h5 class="font-bebas text-xl text-slate-300">BOLETO VACÍO</h5>
                            <p class="text-xs text-slate-500 max-w-[200px]">Haz clic en cualquier cuota de los partidos reales para agregar tus pronósticos.</p>
                        </div>
                    ` : `
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

                        <div class="bg-wpCard/70 border border-wpBorder rounded-2xl p-3 mb-4 space-y-1.5">
                            <div class="flex items-center justify-between text-xs font-outfit">
                                <span class="text-slate-400">Cuota Total:</span>
                                <span class="font-bebas text-xl text-wpGreen combinedOddsDisplay">${combinedOdds.toFixed(2)}x</span>
                            </div>
                            <div class="text-[11px] font-bold text-wpYellow parlayBonusDisplay ${bonus > 0 && betMode === 'parlay' ? '' : 'hidden'}">
                                🔥 +${bonus}% Bonificador Parlay Activo
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="text-[10px] uppercase font-bold text-slate-400 font-outfit block mb-1.5">Monto de la Apuesta ($ COP)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">$</span>
                                <input type="number" id="stakeAmountInput" value="${currentStakeInput}" min="1000" step="1000" oninput="updateStake(this.value)" 
                                       class="w-full bg-wpCard border border-wpBorder focus:border-wpGreen text-white font-bebas text-xl pl-8 pr-3 py-2 rounded-2xl outline-none transition">
                            </div>
                            <div class="grid grid-cols-4 gap-1.5 mt-2">
                                <button onclick="addStakeAmount(5000)" class="py-1 bg-wpCard hover:bg-wpCardHover border border-wpBorder rounded-xl text-[10px] font-bold text-slate-300 transition">+5K</button>
                                <button onclick="addStakeAmount(10000)" class="py-1 bg-wpCard hover:bg-wpCardHover border border-wpBorder rounded-xl text-[10px] font-bold text-slate-300 transition">+10K</button>
                                <button onclick="addStakeAmount(50000)" class="py-1 bg-wpCard hover:bg-wpCardHover border border-wpBorder rounded-xl text-[10px] font-bold text-slate-300 transition">+50K</button>
                                <button onclick="addStakeAmount('max')" class="py-1 bg-wpCard hover:bg-wpCardHover border border-wpBorder rounded-xl text-[10px] font-bold text-wpYellow transition">TODO</button>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-gradient-to-r from-wpGreen/15 via-wpGreen/10 to-transparent border border-wpGreen/30 mb-4">
                            <span class="text-[10px] uppercase font-bold text-slate-400 font-outfit block">Ganancia Potencial</span>
                            <span class="font-bebas text-2xl text-wpGreen font-black potentialPayoutDisplay tracking-wide">
                                ${formatCOP(betMode === 'parlay' ? currentStakeInput * combinedOdds : selectedBets.reduce((a, b) => a + (currentStakeInput * b.odds), 0))}
                            </span>
                        </div>

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
           8. REAL-TIME BETTING & EVALUATION ENGINE (NO FAKE SIMULATION)
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
                selections: selectedBets.map(b => ({
                    matchId: b.matchId,
                    home: b.home,
                    away: b.away,
                    league: b.league,
                    sport: b.sport,
                    marketId: b.marketId,
                    pick: b.pick,
                    label: b.label,
                    odds: b.odds,
                    stake: betMode === 'single' ? currentStakeInput : 0,
                    status: 'pending', // 'pending', 'won', 'lost'
                    liveScore: '0 - 0',
                    isLive: false,
                    isFinished: false
                })),
                totalStake: totalCost,
                odds: combinedOdds,
                potentialWin: potentialWin,
                status: 'pending', // 'pending', 'won', 'lost'
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

            showSimToast(`🎉 ¡Boleto #${ticketId} registrado en tiempo real! Puedes seguir tus partidos en directo en "Mis Boletos".`, '✅ APUESTA EN TIEMPO REAL', '🎟️');

            // Evaluar inmediatamente con los marcadores reales actuales
            evaluateRealTimeTickets();
        }

        /**
         * Evalúa todas las selecciones de los boletos abiertos con los marcadores reales de la API
         */
        function evaluateRealTimeTickets() {
            if (!Array.isArray(betHistory) || betHistory.length === 0) return;

            let historyChanged = false;

            betHistory.forEach(ticket => {
                if (ticket.status !== 'pending') return;

                let allSelectionsFinished = true;
                let anySelectionLost = false;
                let allSelectionsWon = true;
                let singleModeWonAmount = 0;

                ticket.selections.forEach(sel => {
                    // Buscar el partido real en el feed actual de matches
                    const match = matches.find(m => m.id === sel.matchId || 
                        (m.home && sel.home && m.home.toLowerCase().includes(sel.home.toLowerCase())) ||
                        (m.away && sel.away && m.away.toLowerCase().includes(sel.away.toLowerCase())));

                    if (match) {
                        sel.isLive = match.isLive || false;
                        sel.isFinished = match.isFinished || (match.minute === 'FT' || match.minute === 'Finalizado');
                        sel.minute = match.minute || '';
                        sel.liveScore = `${match.homeScore ?? 0} - ${match.awayScore ?? 0}`;

                        if (sel.isFinished) {
                            // Evaluar resultado real
                            const hScore = parseInt(match.homeScore) || 0;
                            const aScore = parseInt(match.awayScore) || 0;
                            let won = false;

                            if (sel.marketId === '1X2') {
                                if (sel.pick === '1') won = hScore > aScore;
                                else if (sel.pick === 'X') won = hScore === aScore;
                                else if (sel.pick === '2') won = aScore > hScore;
                            } else if (sel.marketId === 'exact_score') {
                                const parts = (sel.pick || '').split('-').map(p => parseInt(p.trim()));
                                if (parts.length === 2) {
                                    won = (hScore === parts[0] && aScore === parts[1]);
                                }
                            } else if (sel.marketId === 'over_under' || (sel.pick && sel.pick.includes('2.5'))) {
                                const isOver = sel.pick.includes('+') || sel.pick.toLowerCase().includes('más') || sel.pick.toLowerCase().includes('over');
                                won = isOver ? ((hScore + aScore) > 2.5) : ((hScore + aScore) < 2.5);
                            } else if (sel.marketId === 'btts') {
                                const isYes = sel.pick.toLowerCase().includes('sí') || sel.pick.toLowerCase().includes('si') || sel.pick.toLowerCase().includes('yes');
                                won = isYes ? (hScore > 0 && aScore > 0) : (hScore === 0 || aScore === 0);
                            } else if (sel.marketId === 'double_chance') {
                                if (sel.pick === '1X') won = hScore >= aScore;
                                else if (sel.pick === '12') won = hScore !== aScore;
                                else if (sel.pick === 'X2') won = aScore >= hScore;
                            } else {
                                won = hScore > aScore;
                            }

                            sel.status = won ? 'won' : 'lost';

                            if (won) {
                                if (ticket.mode === 'single') {
                                    singleModeWonAmount += (sel.stake * sel.odds);
                                }
                            } else {
                                anySelectionLost = true;
                                allSelectionsWon = false;
                            }
                        } else {
                            // Sigue en juego o programado
                            allSelectionsFinished = false;
                            allSelectionsWon = false;
                        }
                    } else {
                        // Si no se encuentra en el snapshot actual, se mantiene pendiente
                        allSelectionsFinished = false;
                        allSelectionsWon = false;
                    }
                });

                // Resolver estado del boleto según modalidad
                if (ticket.mode === 'parlay') {
                    if (anySelectionLost) {
                        ticket.status = 'lost';
                        ticket.wonAmount = 0;
                        historyChanged = true;
                        showSimToast(`❌ Boleto Combinado #${ticket.id} finalizó (no acertó uno de los partidos).`, 'RESULTADO EN TIEMPO REAL', '❌');
                    } else if (allSelectionsFinished && allSelectionsWon) {
                        ticket.status = 'won';
                        ticket.wonAmount = ticket.potentialWin;
                        balance += ticket.wonAmount;
                        updateBalanceDisplay();
                        playSound('win');
                        historyChanged = true;

                        if (typeof confetti === 'function') {
                            confetti({ particleCount: 150, spread: 90, origin: { y: 0.6 } });
                        }
                        showSimToast(`🏆 ¡BOLETO GANADOR #${ticket.id}! Acertaste todos los partidos reales y cobraste ${formatCOP(ticket.wonAmount)}`, '¡PREMIO COBRADO!', '💰');
                    }
                } else {
                    // Modalidad Sencilla
                    if (allSelectionsFinished) {
                        ticket.status = singleModeWonAmount > 0 ? 'won' : 'lost';
                        ticket.wonAmount = singleModeWonAmount;
                        if (singleModeWonAmount > 0) {
                            balance += singleModeWonAmount;
                            updateBalanceDisplay();
                            playSound('win');
                            showSimToast(`🏆 Boleto Sencillo #${ticket.id} finalizado. Cobraste ${formatCOP(singleModeWonAmount)}`, '¡PREMIO COBRADO!', '💰');
                        } else {
                            showSimToast(`❌ Boleto Sencillo #${ticket.id} finalizó sin aciertos.`, 'RESULTADO EN TIEMPO REAL', '❌');
                        }
                        historyChanged = true;
                    }
                }
            });

            if (historyChanged) {
                localStorage.setItem('wp_history', JSON.stringify(betHistory));
                updateHistoryCountBadge();
            }
        }

        function quickAddCombo() {
            playSound('click');
            if (matches.length > 0) {
                const sampleMatches = matches.slice(0, 3);
                selectedBets = sampleMatches.map(m => ({
                    matchId: m.id,
                    marketId: '1X2',
                    pick: '1',
                    label: m.home,
                    odds: m.odds['1X2']['1'],
                    home: m.home,
                    away: m.away,
                    league: m.league,
                    sport: m.sport
                }));
            }
            betMode = 'parlay';
            renderMatches();
            renderBetSlip();
            showSimToast('🚀 ¡Parlay de partidos reales cargado con bonificación acumulada!');
        }

        /* =========================================================================
           9. RECHARGE & HISTORY MODALS
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

        function updateHistoryCountBadge() {
            const badge = document.getElementById('ticketCountBadge');
            if (badge) badge.innerText = betHistory.length;
        }

        function switchView(view) {
            if (view === 'history') openHistoryModal();
        }

        function openHistoryModal() {
            playSound('click');
            evaluateRealTimeTickets();

            const modal = document.getElementById('historyModal');
            const list = document.getElementById('historyListContainer');
            if (!modal || !list) return;

            if (betHistory.length === 0) {
                list.innerHTML = `
                    <div class="py-12 text-center text-slate-400">
                        <span class="text-4xl mb-2 block">📭</span>
                        <h4 class="font-bebas text-2xl text-white">NO HAY BOLETOS EN EL HISTORIAL</h4>
                        <p class="text-xs text-slate-500">Realiza tu primera apuesta en tiempo real para hacer seguimiento en vivo aquí.</p>
                    </div>
                `;
            } else {
                list.innerHTML = betHistory.map(ticket => `
                    <div class="bg-wpCard border ${ticket.status === 'won' ? 'border-wpGreen/70 shadow-wpGreen/10 shadow-lg' : ticket.status === 'lost' ? 'border-rose-500/40' : 'border-cyan-500/40'} rounded-2xl p-4 transition shadow-md">
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
                                    <span class="px-2.5 py-0.5 rounded-full bg-wpGreen/20 text-wpGreen font-black text-xs font-outfit border border-wpGreen/30">🏆 GANADA</span>
                                ` : ticket.status === 'lost' ? `
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-500/20 text-rose-400 font-black text-xs font-outfit border border-rose-500/30">❌ NO ACERTADA</span>
                                ` : `
                                    <span class="px-2.5 py-0.5 rounded-full bg-cyan-500/20 text-cyan-300 font-black text-xs font-outfit border border-cyan-500/30 animate-pulse">🟢 EN TIEMPO REAL</span>
                                `}
                            </div>
                        </div>

                        <div class="space-y-2 mb-3">
                            ${ticket.selections.map(s => {
                                const currentMatch = matches.find(m => m.id === s.matchId || (m.home && s.home && m.home.includes(s.home)));
                                const isLive = currentMatch ? currentMatch.isLive : s.isLive;
                                const isFinished = currentMatch ? (currentMatch.isFinished || currentMatch.minute === 'FT') : s.isFinished;
                                const currentScore = currentMatch ? `${currentMatch.homeScore ?? 0} - ${currentMatch.awayScore ?? 0}` : (s.liveScore || '0 - 0');
                                const currentMinute = currentMatch ? currentMatch.minute : (s.minute || '');

                                return `
                                    <div class="bg-wpDark/60 border border-wpBorder/40 rounded-xl p-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 text-xs">
                                        <div>
                                            <div class="text-white font-bold">${s.home} vs ${s.away}</div>
                                            <div class="text-slate-400 text-[11px] font-outfit">Pronóstico: <strong class="text-wpGreen font-black">${s.label}</strong> (@${s.odds.toFixed(2)})</div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            ${isLive ? `
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-wpRed/20 text-wpRed text-[10px] font-black font-outfit border border-wpRed/30">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-wpRed animate-ping"></span>
                                                    VIVO ${currentMinute} (${currentScore})
                                                </span>
                                            ` : isFinished ? `
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 text-[10px] font-bold font-outfit border border-slate-700">
                                                    🏁 Final: ${currentScore}
                                                </span>
                                                ${s.status === 'won' ? `
                                                    <span class="text-wpGreen font-bold text-[10px] font-outfit">✓ Acertada</span>
                                                ` : s.status === 'lost' ? `
                                                    <span class="text-rose-400 font-bold text-[10px] font-outfit">✗ No Acertada</span>
                                                ` : ''}
                                            ` : `
                                                <span class="text-[10px] text-slate-400 bg-wpCard px-2 py-0.5 rounded-full border border-wpBorder font-outfit">
                                                    ⏱️ Programado
                                                </span>
                                            `}
                                        </div>
                                    </div>
                                `;
                            }).join('')}
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-wpBorder/40 text-xs font-outfit">
                            <div><span class="text-slate-400">Apostado: </span><span class="font-bold text-white">${formatCOP(ticket.totalStake)}</span></div>
                            <div><span class="text-slate-400">Cuota: </span><span class="font-bold text-wpGreen font-bebas text-base">${ticket.odds.toFixed(2)}x</span></div>
                            <div><span class="text-slate-400">Premio: </span><span class="font-black ${ticket.status === 'won' ? 'text-wpGreen' : (ticket.status === 'lost' ? 'text-slate-500 line-through' : 'text-cyan-400')}">${ticket.status === 'won' ? formatCOP(ticket.wonAmount) : formatCOP(ticket.potentialWin)}</span></div>
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
           10. SPORTS FILTER
           ========================================================================= */
        function filterSport(sport) {
            playSound('click');
            currentSportFilter = sport;
            currentLeagueFilter = 'all';

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
                beisbol: '⚾ Béisbol MLB & Torneos',
                baloncesto: '🏀 Baloncesto NBA & Euroliga',
                futbol_americano: '🏈 Fútbol Americano (NFL)'
            };
            const titleEl = document.getElementById('currentLeagueTitle');
            if (titleEl) titleEl.innerText = titles[sport] || 'Eventos Deportivos';

            renderLeaguesBar();
            renderMatches();
        }

        /* =========================================================================
           11. INITIALIZATION ON DOM READY
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
            renderBetSlip();
            renderLeaguesBar();

            // Initial fetch from Live Sports API for Day 0 (Today)
            fetchFixturesFromApi(0);

            // Start 30-second live polling engine
            startPollingEngine();
        });
    </script>

</body>
</html>
