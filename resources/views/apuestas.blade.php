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
           4.5. DYNAMIC LEAGUES SUB-FILTER BAR & FAVORITES
           ========================================================================= */
        function toggleFavoriteLeague(event, leagueName) {
            if (event) event.stopPropagation();
            playSound('click');
            const index = favoriteLeagues.indexOf(leagueName);
            if (index > -1) {
                favoriteLeagues.splice(index, 1);
            } else {
                favoriteLeagues.push(leagueName);
            }
            localStorage.setItem('wp_favorite_leagues', JSON.stringify(favoriteLeagues));
            renderLeaguesBar();
            renderMatches();
        }

        function renderLeaguesBar() {
            const container = document.getElementById('leaguesFilterContainer');
            const indicator = document.getElementById('leagueCountIndicator');
            const activeBadge = document.getElementById('activeLeagueBadge');
            if (!container) return;

            // Get matches corresponding to active sport filter
            const sportMatches = matches.filter(m => {
                if (currentSportFilter === 'live' && !m.isLive) return false;
                if (currentSportFilter !== 'all' && currentSportFilter !== 'live' && m.sport !== currentSportFilter) return false;
                return true;
            });

            // Extract unique leagues and count matches (strictly > 0)
            const leagueCounts = {};
            sportMatches.forEach(m => {
                const lName = m.league || 'Otras Ligas';
                leagueCounts[lName] = (leagueCounts[lName] || 0) + 1;
            });

            // Exclude leagues that have 0 matches
            let uniqueLeagues = Object.keys(leagueCounts).filter(lName => (leagueCounts[lName] || 0) > 0);

            // Count favorite matches
            const favMatchesCount = sportMatches.filter(m => favoriteLeagues.includes(m.league)).length;

            // Sort leagues: Favorites FIRST, then by match count descending
            uniqueLeagues.sort((a, b) => {
                const isFavA = favoriteLeagues.includes(a);
                const isFavB = favoriteLeagues.includes(b);
                if (isFavA && !isFavB) return -1;
                if (!isFavA && isFavB) return 1;
                return leagueCounts[b] - leagueCounts[a];
            });

            if (indicator) {
                indicator.innerText = `${uniqueLeagues.length} ligas · ${sportMatches.length} partidos`;
            }

            if (activeBadge) {
                if (currentLeagueFilter === 'favorites') {
                    activeBadge.innerText = `⭐ Favoritas (${favMatchesCount})`;
                    activeBadge.classList.remove('hidden');
                } else if (currentLeagueFilter !== 'all') {
                    activeBadge.innerText = currentLeagueFilter;
                    activeBadge.classList.remove('hidden');
                } else {
                    activeBadge.classList.add('hidden');
                }
            }

            // Build HTML buttons
            let html = `
                <button onclick="filterLeague('all')" class="px-3 py-1.5 rounded-xl border text-xs font-bold font-outfit transition whitespace-nowrap flex items-center gap-1.5 ${currentLeagueFilter === 'all' ? 'bg-gradient-to-r from-wpGreen to-wpGreenDark text-wpDark font-black border-wpGreen shadow-md' : 'bg-wpCard text-slate-300 hover:bg-wpCardHover border-wpBorder'}">
                    <span>🔥</span>
                    <span>Todas las Ligas</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full ${currentLeagueFilter === 'all' ? 'bg-wpDark/25 text-wpDark font-black' : 'bg-wpDark text-slate-400 font-bold'}">${sportMatches.length}</span>
                </button>
            `;

            // Tab for Favoritas
            const isFavFilterActive = currentLeagueFilter === 'favorites';
            html += `
                <button onclick="filterLeague('favorites')" class="px-3 py-1.5 rounded-xl border text-xs font-bold font-outfit transition whitespace-nowrap flex items-center gap-1.5 ${isFavFilterActive ? 'bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-950 font-black border-amber-400 shadow-md' : (favMatchesCount > 0 ? 'bg-wpCard text-amber-300 hover:bg-wpCardHover border-amber-500/40' : 'bg-wpCard text-slate-400 hover:bg-wpCardHover border-wpBorder')}">
                    <span>⭐</span>
                    <span>Favoritas</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full ${isFavFilterActive ? 'bg-slate-950/30 text-slate-950 font-black' : 'bg-wpDark text-amber-400 font-bold'}">${favMatchesCount}</span>
                </button>
            `;

            uniqueLeagues.forEach(leagueName => {
                const isActive = currentLeagueFilter === leagueName;
                const count = leagueCounts[leagueName];
                const isFav = favoriteLeagues.includes(leagueName);
                const escapedName = leagueName.replace(/'/g, "\\'");
                
                html += `
                    <div class="inline-flex items-center rounded-xl border transition whitespace-nowrap overflow-hidden ${isActive ? 'bg-gradient-to-r from-wpGreen to-wpGreenDark text-wpDark font-black border-wpGreen shadow-md' : (isFav ? 'bg-wpCard text-amber-200 border-amber-500/50 hover:bg-wpCardHover' : 'bg-wpCard text-slate-300 hover:bg-wpCardHover border-wpBorder')}">
                        <button onclick="toggleFavoriteLeague(event, '${escapedName}')" class="pl-2.5 pr-1 py-1.5 text-xs hover:scale-125 transition" title="${isFav ? 'Quitar de favoritas' : 'Marcar liga como favorita'}">
                            ${isFav ? '⭐' : '<span class="text-slate-500 hover:text-amber-400">☆</span>'}
                        </button>
                        <button onclick="filterLeague('${escapedName}')" class="pr-2.5 pl-1 py-1.5 text-xs font-bold font-outfit flex items-center gap-1.5">
                            <span>${leagueName}</span>
                            <span class="text-[10px] px-1.5 py-0.2 rounded-full ${isActive ? 'bg-wpDark/25 text-wpDark font-black' : (isFav ? 'bg-wpDark text-amber-400 font-black' : 'bg-wpDark text-wpGreen font-black')}">${count}</span>
                        </button>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function filterLeague(leagueName) {
            playSound('click');
            currentLeagueFilter = leagueName;
            renderLeaguesBar();
            renderMatches();
        }

        /* =========================================================================
           5. RENDER MATCHES FEED
           ========================================================================= */
        function formatCOP(amount) {
            return '$' + Math.round(amount).toLocaleString('es-CO') + ' COP';
        }

        function updateBalanceDisplay() {
            document.getElementById('userBalanceDisplay').innerText = formatCOP(balance);
            localStorage.setItem('wp_balance', balance);
        }

        function renderMatches() {
            const container = document.getElementById('matchesContainer');
            if (!container) return;

            let filtered = matches.filter(m => {
                if (currentSportFilter === 'live' && !m.isLive) return false;
                if (currentSportFilter !== 'all' && currentSportFilter !== 'live' && m.sport !== currentSportFilter) return false;
                if (currentLeagueFilter === 'favorites') {
                    return favoriteLeagues.includes(m.league);
                }
                if (currentLeagueFilter !== 'all' && m.league !== currentLeagueFilter) return false;
                return true;
            });

            const liveCount = matches.filter(m => m.isLive).length;
            const liveBadge = document.getElementById('liveMatchBadgeCount');
            if (liveBadge) liveBadge.innerText = liveCount;

            if (filtered.length === 0) {
                if (currentLeagueFilter === 'favorites') {
                    container.innerHTML = `
                        <div class="bg-wpDark2 border border-amber-500/30 rounded-2xl p-10 text-center">
                            <span class="text-3xl mb-2 block">⭐</span>
                            <h4 class="font-bebas text-2xl text-white">NO HAY PARTIDOS EN TUS LIGAS FAVORITAS</h4>
                            <p class="text-xs text-slate-400 mb-4 font-light">No tienes ligas marcadas como favoritas con partidos hoy o en este deporte.</p>
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="filterLeague('all')" class="px-4 py-2 bg-wpGreen text-wpDark font-black font-outfit text-xs rounded-xl shadow">
                                    Ver Todas las Ligas
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
                const isFav = favoriteLeagues.includes(m.league);
                const escapedLeague = (m.league || '').replace(/'/g, "\\'");

                html += `
                    <div class="bg-wpDark2 hover:border-slate-700/80 border border-wpBorder rounded-3xl p-4 sm:p-5 transition shadow-lg relative overflow-hidden" id="match-card-${m.id}">
                        
                        <!-- Top League, Date & Live Info -->
                        <div class="flex items-center justify-between gap-2 mb-3 pb-2.5 border-b border-wpBorder/60">
                            <div class="flex items-center gap-1.5">
                                <button onclick="toggleFavoriteLeague(event, '${escapedLeague}')" class="text-xs hover:scale-125 transition p-0.5" title="${isFav ? 'Quitar de favoritas' : 'Marcar liga como favorita'}">
                                    ${isFav ? '⭐' : '<span class="text-slate-500 hover:text-amber-400">☆</span>'}
                                </button>
                                <span class="text-xs font-bold text-slate-300 font-outfit cursor-pointer hover:text-wpGreen transition" onclick="filterLeague('${escapedLeague}')">${m.league}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="openStatsModal('${m.id}')" class="text-xs font-bold text-wpGreen hover:bg-wpGreen/20 bg-wpGreen/10 border border-wpGreen/30 px-2.5 py-0.5 rounded-full font-outfit transition flex items-center gap-1">
                                    <span>🎯</span> <span class="hidden sm:inline">Pronóstico & H2H</span><span class="sm:hidden">IA & H2H</span>
                                </button>

                                ${m.isLive ? `
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-wpRed/15 border border-wpRed/30 text-wpRed text-[11px] font-black font-outfit">
                                        <span class="w-2 h-2 rounded-full bg-wpRed pulse-live"></span>
                                        VIVO ${m.minute}
                                    </span>
                                ` : `
                                    <span class="text-[11px] font-semibold text-slate-400 font-outfit bg-wpCard px-2.5 py-0.5 rounded-full border border-wpBorder">
                                        ⏱️ ${m.startTime}
                                    </span>
                                `}
                            </div>
                        </div>

                        <!-- Pill Badge de Sugerencia Rápida de Marcador -->
                        ${mScorePreds ? `
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
                                <span class="text-[9px] text-slate-400 font-outfit">H2H & IA 📊</span>
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
                                    ${m.awayLogo ? `
                                        <img src="${m.awayLogo}" alt="${m.away}" class="w-8 h-8 object-contain">
                                    ` : `
                                        <div class="w-8 h-8 rounded-full bg-wpCard border border-wpBorder group-hover:border-wpGreen flex items-center justify-center text-sm font-black text-white transition">
                                            ${m.away.charAt(0)}
                                        </div>
                                    `}
                                </div>
                            </div>
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
            if (match.score_predictions && match.score_predictions.topScores && match.score_predictions.topScores.length > 0) {
                return match.score_predictions;
            }

            const avgGoals = parseFloat(match.h2h?.avgGoals) || 2.6;
            const probHome = match.h2h?.homeWinProb || 48;
            const probDraw = match.h2h?.drawProb || 26;
            const probAway = match.h2h?.awayWinProb || 26;

            const homeRatio = Math.max(0.25, Math.min(0.75, (probHome + (probDraw * 0.35)) / 100));
            const awayRatio = 1 - homeRatio;

            const lambdaHome = Math.max(0.65, Math.min(3.8, parseFloat(((avgGoals * homeRatio) * 1.12).toFixed(2))));
            const lambdaAway = Math.max(0.45, Math.min(3.4, parseFloat(((avgGoals * awayRatio) * 0.92).toFixed(2))));

            function fact(n) { return n <= 1 ? 1 : n * fact(n - 1); }
            function poisson(lambda, k) { return (Math.pow(lambda, k) * Math.exp(-lambda)) / fact(k); }

            const scores = [];
            let totalSum = 0;
            for (let x = 0; x <= 5; x++) {
                for (let y = 0; y <= 5; y++) {
                    const p = poisson(lambdaHome, x) * poisson(lambdaAway, y);
                    totalSum += p;
                    scores.push({
                        score: `${x} - ${y}`,
                        homeGoals: x,
                        awayGoals: y,
                        rawProb: p,
                        type: x > y ? 'home_win' : (x === y ? 'draw' : 'away_win'),
                        typeLabel: x > y ? `Victoria ${match.home}` : (x === y ? 'Empate' : `Victoria ${match.away}`)
                    });
                }
            }

            scores.forEach(s => {
                const norm = (s.rawProb / Math.max(0.001, totalSum)) * 100;
                s.probability = parseFloat(norm.toFixed(1));
                s.odds = parseFloat(Math.max(2.10, Math.min(85.0, (100 / Math.max(0.5, norm)) * 1.08)).toFixed(2));
            });

            scores.sort((a, b) => b.rawProb - a.rawProb);
            const topScores = scores.slice(0, 6);
            const tags = [
                '🔥 Marcador Más Probable',
                '⚡ Segunda Opción Fuerte',
                '💡 Opción de Alto Valor',
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
            const confidence = Math.min(94, Math.round(48 + (bestProb * 2.1)));

            return {
                expectedGoalsHome: lambdaHome,
                expectedGoalsAway: lambdaAway,
                totalExpectedGoals: parseFloat((lambdaHome + lambdaAway).toFixed(2)),
                topScores: topScores,
                recommendedScore: bestScore,
                recommendedOdds: bestOdds,
                recommendedProb: bestProb,
                confidencePercent: confidence,
                analysis: `El modelo predictivo de Poisson proyecta una expectativa de ${lambdaHome} goles para ${match.home} y ${lambdaAway} para ${match.away}. Basado en el promedio reciente de los últimos encuentros y el factor de localía, el marcador más probable es ${bestScore} (${bestProb}% de probabilidad con cuota ${bestOdds}), seguido de ${topScores[1].score} (${topScores[1].probability}%).`
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
                    confetti({ particleCount: 120, spread: 80, origin: { y: 0.6 } });
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
                            <div><span class="text-slate-400">Apostado: </span><span class="font-bold text-white">${formatCOP(ticket.totalStake)}</span></div>
                            <div><span class="text-slate-400">Cuota: </span><span class="font-bold text-wpGreen font-bebas text-base">${ticket.odds.toFixed(2)}x</span></div>
                            <div><span class="text-slate-400">Premio: </span><span class="font-black ${ticket.status === 'won' ? 'text-wpGreen' : 'text-slate-400'}">${ticket.status === 'won' ? formatCOP(ticket.wonAmount) : formatCOP(ticket.potentialWin)}</span></div>
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
