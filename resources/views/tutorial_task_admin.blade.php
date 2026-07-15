<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración: Tareas TASK | ArleySoftx</title>
    
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
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.15);
        }
        .neon-border {
            border-color: rgba(0, 240, 255, 0.2);
        }
    </style>
</head>
<body class="bg-darkBg text-slate-100 font-jakarta min-h-screen flex flex-col justify-between overflow-x-hidden selection:bg-neonBlue selection:text-darkBg">
    
    <!-- Background Grid Decoration -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f293710_1px,transparent_1px),linear-gradient(to_bottom,#1f293710_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] pointer-events-none"></div>

    <!-- Header Navigation -->
    <header class="w-full py-6 px-6 max-w-7xl mx-auto flex justify-between items-center relative z-10 border-b border-slate-800/40 bg-darkBg/50 backdrop-blur-md sticky top-0">
        <div class="flex items-center gap-2">
            <a href="{{ url('/guia-y-tutoriales-task') }}" class="text-2xl font-black font-outfit tracking-tighter bg-gradient-to-r from-neonBlue via-white to-neonGreen bg-clip-text text-transparent hover:opacity-90 transition">
                ARLEYSOFTX ADMIN
            </a>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ url('/guia-y-tutoriales-task') }}" target="_blank" class="text-xs font-bold text-neonBlue hover:text-white transition duration-200 flex items-center gap-1.5 px-4 py-2 rounded-xl bg-neonBlue/10 border border-neonBlue/20">
                👁️ Ver Página Pública
            </a>
            <a href="{{ url('/') }}" class="text-xs font-bold text-slate-400 hover:text-white transition duration-200 flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 border border-slate-800/60">
                Volver al Inicio
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-12 relative z-10 flex-grow w-full space-y-8">
        
        <!-- Header Panel Info -->
        <div class="space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-neonBlue/30 bg-neonBlue/5">
                <span class="w-2 h-2 rounded-full bg-neonBlue animate-pulse"></span>
                <span class="text-xs font-semibold text-neonBlue uppercase tracking-widest font-outfit">Panel de Control</span>
            </div>
            <h1 class="font-outfit font-black text-3xl sm:text-4xl text-white tracking-tight leading-none">
                Administrador de Labores TASK
            </h1>
            <p class="text-slate-400 text-sm leading-relaxed max-w-2xl">
                Agrega nuevas labores, edita los requisitos de video, actualiza las instrucciones de grabación o sube nuevas imágenes de referencia. Todo se guardará de forma segura.
            </p>
        </div>

        <!-- Alert messages -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xs opacity-50 hover:opacity-100 font-bold">Cerrar</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span>❌</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xs opacity-50 hover:opacity-100 font-bold">Cerrar</button>
            </div>
        @endif

        <form action="{{ route('tutorial.task.save') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Container of all Tasks -->
            <div id="tasks-container" class="space-y-6">
                @foreach($tasks as $index => $task)
                    <div class="task-card bg-slate-900/60 border border-slate-800/80 p-6 rounded-3xl space-y-4 relative" id="task-card-{{ $index }}">
                        
                        <!-- Header Card -->
                        <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                            <h4 class="font-outfit font-black text-white text-base tracking-wide uppercase">
                                Tarea #{{ $index + 1 }}: <span class="text-neonBlue">{{ $task['title'] }}</span>
                            </h4>
                            <button type="button" onclick="removeTaskCard({{ $index }}, true)" class="text-xs text-red-400 hover:text-red-300 font-bold bg-red-500/5 hover:bg-red-500/10 px-3 py-1.5 rounded-xl border border-red-500/20 transition">
                                🗑️ Eliminar Labor
                            </button>
                        </div>

                        <!-- Data Fields Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-outfit">Título:</label>
                                <input type="text" name="tasks[{{ $index }}][title]" value="{{ $task['title'] }}" required class="w-full bg-slate-950 border border-slate-800/80 rounded-xl px-3 py-2 text-sm text-white focus:border-neonBlue focus:ring-1 focus:ring-neonBlue/40 focus:outline-none transition">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-outfit">Tasa de Pago:</label>
                                <input type="text" name="tasks[{{ $index }}][pago]" value="{{ $task['pago'] }}" required class="w-full bg-slate-950 border border-slate-800/80 rounded-xl px-3 py-2 text-sm text-white focus:border-neonBlue focus:ring-1 focus:ring-neonBlue/40 focus:outline-none transition">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-outfit">Categoría:</label>
                                <input type="text" name="tasks[{{ $index }}][categoria]" value="{{ $task['categoria'] }}" required class="w-full bg-slate-950 border border-slate-800/80 rounded-xl px-3 py-2 text-sm text-white focus:border-neonBlue focus:ring-1 focus:ring-neonBlue/40 focus:outline-none transition">
                            </div>
                        </div>

                        <!-- Video Requisitos -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-outfit">Requisitos de Video (Separados por coma):</label>
                            <input type="text" name="tasks[{{ $index }}][requisitos]" value="{{ $task['requisitos'] }}" class="w-full bg-slate-950 border border-slate-800/80 rounded-xl px-3 py-2 text-sm text-white focus:border-neonBlue focus:ring-1 focus:ring-neonBlue/40 focus:outline-none transition">
                        </div>

                        <!-- Detailed Instructions -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-outfit">Instrucciones de Grabación:</label>
                            <textarea name="tasks[{{ $index }}][instrucciones]" rows="3" required class="w-full bg-slate-950 border border-slate-800/80 rounded-xl px-3 py-2 text-sm text-white focus:border-neonBlue focus:ring-1 focus:ring-neonBlue/40 focus:outline-none transition leading-relaxed">{{ $task['instrucciones'] }}</textarea>
                        </div>

                        <!-- Image upload and preview -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center pt-2">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-outfit block">Subir foto vertical (aspecto 3:4):</label>
                                <input type="file" name="tasks[{{ $index }}][image_file]" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-700 transition">
                                <input type="hidden" name="tasks[{{ $index }}][image_hidden]" value="{{ $task['image'] }}">
                            </div>
                            
                            <div class="bg-slate-950 p-3 rounded-2xl border border-slate-800/60 flex items-center gap-3">
                                @if(!empty($task['image']) && file_exists(public_path('images/' . $task['image'])))
                                    <div class="w-10 h-12 rounded bg-slate-900 border border-slate-800 overflow-hidden flex-shrink-0">
                                        <img src="{{ asset('images/' . $task['image']) }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-grow">
                                        <p class="text-[10px] text-slate-500 font-mono truncate max-w-[150px]">{{ $task['image'] }}</p>
                                        <label class="inline-flex items-center gap-1.5 mt-1 cursor-pointer">
                                            <input type="checkbox" name="tasks[{{ $index }}][delete_image]" value="1" class="rounded border-slate-800 bg-slate-900 text-neonRed focus:ring-0">
                                            <span class="text-[10px] font-bold text-red-400 uppercase tracking-wide">Eliminar foto</span>
                                        </label>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-500 italic">No hay foto cargada ("Foto Pendiente")</span>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Add Task & Floating Footer Bar -->
            <div class="flex flex-col sm:flex-row gap-4 justify-between items-center pt-4">
                <button type="button" onclick="addTask()" class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-slate-900 hover:bg-slate-850 border border-slate-800/80 hover:border-neonGreen/30 text-neonGreen font-bold font-outfit text-sm transition flex items-center justify-center gap-2">
                    ➕ Agregar Nueva Labor
                </button>
            </div>

            <!-- Sticky/Bottom Save Panel -->
            <div class="sticky bottom-6 z-20 w-full bg-slate-900/90 border border-slate-800 p-6 rounded-3xl backdrop-blur-md shadow-2xl flex flex-col sm:flex-row gap-4 items-center justify-between">
                <div class="w-full sm:w-auto space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-outfit block">Contraseña de Administrador:</label>
                    <input type="password" name="admin_password" required placeholder="Contraseña..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-sm text-white focus:border-neonBlue focus:outline-none transition">
                </div>
                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-neonBlue/10 hover:bg-neonBlue/20 text-neonBlue hover:text-white font-black font-outfit tracking-wider uppercase text-sm border border-neonBlue/30 hover:border-neonBlue/60 transition duration-300">
                    💾 Guardar Todo
                </button>
            </div>

        </form>

    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-900/60 bg-darkBg/90 py-8">
        <div class="max-w-6xl mx-auto px-6 text-center text-xs text-slate-500 font-light flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; {{ date('Y') }} ArleySoftx. Todos los derechos reservados.</p>
            <p>Panel de Administración de Contenidos TASK.</p>
        </div>
    </footer>

    <!-- Simple JavaScript for Task Insertion -->
    <script>
        let taskIndex = {{ count($tasks) }};
        
        function addTask() {
            const container = document.getElementById('tasks-container');
            const html = `
                <div class="task-card bg-slate-900/60 border border-slate-800/80 p-6 rounded-3xl space-y-4 relative animate-fade-in" id="task-card-${taskIndex}">
                    <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                        <h4 class="font-outfit font-black text-white text-base tracking-wide uppercase">
                            Nueva Labor (Índice #${taskIndex + 1})
                        </h4>
                        <button type="button" onclick="removeTaskCard(${taskIndex}, false)" class="text-xs text-red-400 hover:text-red-300 font-bold bg-red-500/5 hover:bg-red-500/10 px-3 py-1.5 rounded-xl border border-red-500/20 transition">
                            🗑️ Eliminar Labor
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-outfit">Título:</label>
                            <input type="text" name="tasks[${taskIndex}][title]" value="" required class="w-full bg-slate-950 border border-slate-800/80 rounded-xl px-3 py-2 text-sm text-white focus:border-neonBlue focus:outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-outfit">Tasa de Pago:</label>
                            <input type="text" name="tasks[${taskIndex}][pago]" value="$20/hour" required class="w-full bg-slate-950 border border-slate-800/80 rounded-xl px-3 py-2 text-sm text-white focus:border-neonBlue focus:outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-outfit">Categoría:</label>
                            <input type="text" name="tasks[${taskIndex}][categoria]" value="Projects" required class="w-full bg-slate-950 border border-slate-800/80 rounded-xl px-3 py-2 text-sm text-white focus:border-neonBlue focus:outline-none transition">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-outfit">Requisitos de Video (Separados por coma):</label>
                        <input type="text" name="tasks[${taskIndex}][requisitos]" value="Head mount, Landscape, Both hands visible" class="w-full bg-slate-950 border border-slate-800/80 rounded-xl px-3 py-2 text-sm text-white focus:border-neonBlue focus:outline-none transition">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-outfit">Instrucciones de Grabación:</label>
                        <textarea name="tasks[${taskIndex}][instrucciones]" rows="3" required class="w-full bg-slate-950 border border-slate-800/80 rounded-xl px-3 py-2 text-sm text-white focus:border-neonBlue focus:outline-none transition leading-relaxed"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-outfit block">Subir foto vertical (aspecto 3:4):</label>
                        <input type="file" name="tasks[${taskIndex}][image_file]" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-700 transition">
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            
            // Scroll a la nueva tarjeta
            document.getElementById(`task-card-${taskIndex}`).scrollIntoView({ behavior: 'smooth' });
            taskIndex++;
        }

        function removeTaskCard(index, isExisting) {
            if (confirm('¿Estás seguro de que deseas eliminar esta labor?')) {
                const card = document.getElementById(`task-card-${index}`);
                if (isExisting) {
                    // Ocultar la tarjeta y agregar input hidden para borrar en servidor
                    card.style.display = 'none';
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = `tasks[${index}][delete_task]`;
                    deleteInput.value = '1';
                    card.appendChild(deleteInput);
                } else {
                    // Si es nueva y no se ha guardado, se remueve directamente del DOM
                    card.remove();
                }
            }
        }
    </script>
</body>
</html>
