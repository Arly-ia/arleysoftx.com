<?php

namespace App\Http\Controllers;

use App\Models\JJTask;
use App\Models\JJTaskPhoto;
use Illuminate\Http\Request;

class JJTaskController extends Controller
{
    private static array $defaultTasks = [
        ['name' => 'Mantenimiento e instalación de freidora',                   'category' => 'mantenimiento', 'priority' => 'alta'],
        ['name' => 'Pintura exterior',                                          'category' => 'pintura',       'priority' => 'media'],
        ['name' => 'Pintura interior',                                          'category' => 'pintura',       'priority' => 'media'],
        ['name' => 'Limpieza general',                                          'category' => 'limpieza',      'priority' => 'media'],
        ['name' => 'Fabricación de barra',                                      'category' => 'fabricacion',   'priority' => 'alta'],
        ['name' => 'Mantenimiento de ductos y luminarias',                      'category' => 'mantenimiento', 'priority' => 'media'],
        ['name' => 'Pintura de baños y cambio de poceta',                       'category' => 'pintura',       'priority' => 'alta'],
        ['name' => 'Instalación de muebles',                                    'category' => 'instalacion',   'priority' => 'media'],
        ['name' => 'Instalación de lavamanos',                                  'category' => 'instalacion',   'priority' => 'alta'],
        ['name' => 'Reparación de paredes',                                     'category' => 'reparacion',    'priority' => 'media'],
        ['name' => 'Pintura de techo',                                          'category' => 'pintura',       'priority' => 'media'],
        ['name' => 'Instalación de cajón con tomas frente a la campana',        'category' => 'electrico',     'priority' => 'alta'],
        ['name' => 'Instalación de 6 puntos eléctricos',                        'category' => 'electrico',     'priority' => 'alta'],
        ['name' => 'Reparación de piso (cerámica) en cocina',                   'category' => 'reparacion',    'priority' => 'alta'],
        ['name' => 'Instalación de TV',                                         'category' => 'instalacion',   'priority' => 'baja'],
        ['name' => 'Instalación de 2 ventiladores e iluminación para terraza',  'category' => 'instalacion',   'priority' => 'media'],
        ['name' => 'Fabricación de marco de puerta (storage)',                  'category' => 'fabricacion',   'priority' => 'media'],
        ['name' => 'Instalación de reja de puerta',                             'category' => 'instalacion',   'priority' => 'media'],
        ['name' => 'Limpieza de vidrios interior y exterior',                   'category' => 'limpieza',      'priority' => 'baja'],
        ['name' => 'Lavado a presión de paredes, techo, iluminación y otros',   'category' => 'limpieza',      'priority' => 'media'],
        ['name' => 'Pintar Storage',                                            'category' => 'pintura',       'priority' => 'media'],
        ['name' => 'Piso Storage',                                              'category' => 'reparacion',    'priority' => 'media'],
    ];

    /** Seed default tasks on first visit */
    private function seedIfEmpty(): void
    {
        if (JJTask::count() === 0) {
            foreach (self::$defaultTasks as $task) {
                JJTask::create(array_merge($task, ['status' => 'pendiente']));
            }
        }
    }

    public function index()
    {
        $this->seedIfEmpty();

        $tasks = JJTask::with('photos')
            ->orderByRaw("FIELD(priority, 'alta', 'media', 'baja')")
            ->orderByRaw("FIELD(status, 'pendiente', 'en_progreso', 'completada')")
            ->get();

        $stats = [
            'total'       => $tasks->count(),
            'completadas' => $tasks->where('status', 'completada')->count(),
            'en_progreso' => $tasks->where('status', 'en_progreso')->count(),
            'pendientes'  => $tasks->where('status', 'pendiente')->count(),
        ];
        $stats['pct'] = $stats['total'] > 0
            ? (int) round(($stats['completadas'] / $stats['total']) * 100)
            : 0;

        return view('jj_20wings_tasks', compact('tasks', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required|in:mantenimiento,pintura,limpieza,electrico,fabricacion,instalacion,reparacion',
            'priority' => 'required|in:alta,media,baja',
            'notes'    => 'nullable|string|max:1000',
        ]);

        JJTask::create([
            'name'     => $request->name,
            'category' => $request->category,
            'priority' => $request->priority,
            'status'   => 'pendiente',
            'notes'    => $request->notes,
        ]);

        return redirect()->route('jj.20wings.tasks')->with('success', '✓ Tarea creada exitosamente.');
    }

    public function updateStatus(Request $request, $id)
    {
        $task = JJTask::findOrFail($id);
        $task->status       = $request->status;
        $task->completed_at = ($request->status === 'completada') ? now() : null;
        $task->save();

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function uploadPhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|max:8192',
            'type'  => 'required|in:antes,despues',
        ]);

        JJTask::findOrFail($id);

        $dir = public_path('images/jj_tasks');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $file     = $request->file('photo');
        $ext      = $file->getClientOriginalExtension();
        $filename = 'task_' . $id . '_' . $request->type . '_' . time() . '.' . $ext;
        $file->move($dir, $filename);

        $photo = JJTaskPhoto::create([
            'task_id'  => $id,
            'type'     => $request->type,
            'filename' => $filename,
        ]);

        return response()->json([
            'success' => true,
            'photo'   => [
                'id'   => $photo->id,
                'type' => $photo->type,
                'url'  => asset('images/jj_tasks/' . $filename),
            ],
        ]);
    }

    public function deletePhoto($id)
    {
        $photo = JJTaskPhoto::findOrFail($id);
        $fp    = public_path('images/jj_tasks/' . $photo->filename);
        if (file_exists($fp)) {
            unlink($fp);
        }
        $photo->delete();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $task = JJTask::with('photos')->findOrFail($id);
        foreach ($task->photos as $photo) {
            $fp = public_path('images/jj_tasks/' . $photo->filename);
            if (file_exists($fp)) {
                unlink($fp);
            }
        }
        $task->delete();

        return response()->json(['success' => true]);
    }
}
