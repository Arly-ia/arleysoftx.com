<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
        ['name' => 'Rampa de cemento',                                          'category' => 'reparacion',    'priority' => 'alta'],
    ];

    protected function getJsonPath(): string
    {
        return storage_path('app/jj_tasks.json');
    }

    protected function getTasks(): array
    {
        $path = $this->getJsonPath();
        $tasks = [];

        if (File::exists($path)) {
            $content = File::get($path);
            $tasks = json_decode($content, true) ?: [];
        } else {
            $dir = dirname($path);
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            foreach (self::$defaultTasks as $idx => $task) {
                $tasks[] = [
                    'id'           => $idx + 1,
                    'name'         => $task['name'],
                    'category'     => $task['category'],
                    'priority'     => $task['priority'],
                    'status'       => 'pendiente',
                    'completed_at' => null,
                    'notes'        => null,
                    'photos'       => [],
                    'receipts'     => [],
                ];
            }
        }

        // Ensure Gastos Generales (ID 0) is always present
        $hasGeneral = false;
        foreach ($tasks as $t) {
            if ($t['id'] === 0) {
                $hasGeneral = true;
                break;
            }
        }

        if (!$hasGeneral) {
            array_unshift($tasks, [
                'id'           => 0,
                'name'         => 'Gastos Generales (Materiales, Compras y Varios)',
                'category'     => 'mantenimiento',
                'priority'     => 'media',
                'status'       => 'en_progreso',
                'completed_at' => null,
                'notes'        => 'Sube aquí todas las facturas y recibos generales que no correspondan a una tarea específica.',
                'photos'       => [],
                'receipts'     => [],
            ]);
            $this->saveTasks($tasks);
        }

        return $tasks;
    }

    protected function saveTasks(array $tasks): void
    {
        File::put($this->getJsonPath(), json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function index()
    {
        $tasks = $this->getTasks();

        $generalTask = null;
        $normalTasks = [];
        $total_gastos = 0.0;

        foreach ($tasks as $task) {
            // Sum receipts
            if (isset($task['receipts']) && is_array($task['receipts'])) {
                foreach ($task['receipts'] as $receipt) {
                    $total_gastos += (float) ($receipt['amount'] ?? 0);
                }
            }

            if ($task['id'] === 0) {
                $generalTask = (object) [
                    'id'             => 0,
                    'name'           => $task['name'],
                    'category'       => $task['category'],
                    'priority'       => $task['priority'],
                    'status'         => $task['status'],
                    'completed_at'   => null,
                    'notes'          => $task['notes'] ?? null,
                    'photos'         => collect([]),
                    'receipts'       => collect(array_map(fn($r) => (object)$r, $task['receipts'] ?? [])),
                    'receipts_total' => array_sum(array_column($task['receipts'] ?? [], 'amount')),
                ];
            } else {
                $normalTasks[] = $task;
            }
        }

        // Sort normal tasks
        usort($normalTasks, function($a, $b) {
            $prioOrder = ['alta' => 1, 'media' => 2, 'baja' => 3];
            $statusOrder = ['pendiente' => 1, 'en_progreso' => 2, 'completada' => 3];

            $aPrio = $prioOrder[$a['priority']] ?? 2;
            $bPrio = $prioOrder[$b['priority']] ?? 2;
            if ($aPrio !== $bPrio) {
                return $aPrio <=> $bPrio;
            }

            $aStatus = $statusOrder[$a['status']] ?? 2;
            $bStatus = $statusOrder[$b['status']] ?? 2;
            return $aStatus <=> $bStatus;
        });

        $total = count($normalTasks);
        $completadas = 0;
        $en_progreso = 0;
        $pendientes = 0;

        foreach ($normalTasks as $task) {
            if ($task['status'] === 'completada') {
                $completadas++;
            } elseif ($task['status'] === 'en_progreso') {
                $en_progreso++;
            } else {
                $pendientes++;
            }
        }

        $stats = [
            'total'        => $total,
            'completadas'  => $completadas,
            'en_progreso'  => $en_progreso,
            'pendientes'   => $pendientes,
            'total_gastos' => $total_gastos,
            'pct'          => $total > 0 ? (int) round(($completadas / $total) * 100) : 0,
        ];

        // Format normal tasks as objects for Blade
        $tasksObj = array_map(function($task) {
            $receipts = $task['receipts'] ?? [];
            $receiptsTotal = array_sum(array_column($receipts, 'amount'));

            return (object) [
                'id'             => $task['id'],
                'name'           => $task['name'],
                'category'       => $task['category'],
                'priority'       => $task['priority'],
                'status'         => $task['status'],
                'completed_at'   => $task['completed_at'] ? \Carbon\Carbon::parse($task['completed_at']) : null,
                'notes'          => $task['notes'] ?? null,
                'photos'         => collect(array_map(fn($p) => (object)$p, $task['photos'] ?? [])),
                'receipts'       => collect(array_map(fn($r) => (object)$r, $receipts)),
                'receipts_total' => $receiptsTotal,
            ];
        }, $normalTasks);

        return view('jj_20wings_tasks', [
            'tasks'       => $tasksObj,
            'generalTask' => $generalTask,
            'stats'       => $stats
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required|in:mantenimiento,pintura,limpieza,electrico,fabricacion,instalacion,reparacion',
            'priority' => 'required|in:alta,media,baja',
            'notes'    => 'nullable|string|max:1000',
        ]);

        $tasks = $this->getTasks();
        $maxId = 0;
        foreach ($tasks as $t) {
            if ($t['id'] > $maxId) {
                $maxId = $t['id'];
            }
        }

        $tasks[] = [
            'id'           => $maxId + 1,
            'name'         => $request->name,
            'category'     => $request->category,
            'priority'     => $request->priority,
            'status'       => 'pendiente',
            'completed_at' => null,
            'notes'        => $request->notes,
            'photos'       => [],
            'receipts'     => [],
        ];

        $this->saveTasks($tasks);

        return redirect()->route('jj.20wings.tasks')->with('success', '✓ Tarea creada exitosamente.');
    }

    public function updateStatus(Request $request, $id)
    {
        $tasks = $this->getTasks();
        $found = false;
        $updatedTask = null;

        foreach ($tasks as &$task) {
            if ($task['id'] == $id) {
                $task['status'] = $request->status;
                $task['completed_at'] = ($request->status === 'completada') ? now()->toIso8601String() : null;
                $updatedTask = $task;
                $found = true;
                break;
            }
        }

        if (!$found) {
            return response()->json(['success' => false, 'error' => 'Task not found'], 404);
        }

        $this->saveTasks($tasks);

        return response()->json(['success' => true, 'task' => $updatedTask]);
    }

    public function uploadPhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|max:8192',
            'type'  => 'required|in:antes,despues',
        ]);

        $tasks = $this->getTasks();
        $taskIndex = -1;

        foreach ($tasks as $idx => $task) {
            if ($task['id'] == $id) {
                $taskIndex = $idx;
                break;
            }
        }

        if ($taskIndex === -1) {
            return response()->json(['success' => false, 'error' => 'Task not found'], 404);
        }

        $dir = public_path('images/jj_tasks');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $file     = $request->file('photo');
        $ext      = $file->getClientOriginalExtension();
        $photoId  = time() . rand(100, 999);
        $filename = 'task_' . $id . '_' . $request->type . '_' . $photoId . '.' . $ext;
        $file->move($dir, $filename);

        $newPhoto = [
            'id'       => (int) $photoId,
            'type'     => $request->type,
            'filename' => $filename,
        ];

        if (!isset($tasks[$taskIndex]['photos'])) {
            $tasks[$taskIndex]['photos'] = [];
        }

        $tasks[$taskIndex]['photos'][] = $newPhoto;
        $this->saveTasks($tasks);

        return response()->json([
            'success' => true,
            'photo'   => [
                'id'   => $newPhoto['id'],
                'type' => $newPhoto['type'],
                'url'  => asset('images/jj_tasks/' . $filename),
            ],
        ]);
    }

    public function deletePhoto($id)
    {
        $tasks = $this->getTasks();
        $photoDeleted = false;

        foreach ($tasks as &$task) {
            if (isset($task['photos'])) {
                foreach ($task['photos'] as $idx => $photo) {
                    if ($photo['id'] == $id) {
                        $fp = public_path('images/jj_tasks/' . $photo['filename']);
                        if (file_exists($fp)) {
                            unlink($fp);
                        }
                        unset($task['photos'][$idx]);
                        $task['photos'] = array_values($task['photos']);
                        $photoDeleted = true;
                        break 2;
                    }
                }
            }
        }

        if (!$photoDeleted) {
            return response()->json(['success' => false, 'error' => 'Photo not found'], 404);
        }

        $this->saveTasks($tasks);
        return response()->json(['success' => true]);
    }

    public function uploadReceipt(Request $request, $id)
    {
        $request->validate([
            'photo'  => 'required|image|max:8192',
            'amount' => 'required|numeric|min:0',
        ]);

        $tasks = $this->getTasks();
        $taskIndex = -1;

        foreach ($tasks as $idx => $task) {
            if ($task['id'] == $id) {
                $taskIndex = $idx;
                break;
            }
        }

        if ($taskIndex === -1) {
            return response()->json(['success' => false, 'error' => 'Task not found'], 404);
        }

        $dir = public_path('images/jj_receipts');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $file     = $request->file('photo');
        $ext      = $file->getClientOriginalExtension();
        $receiptId = time() . rand(100, 999);
        $filename = 'receipt_' . $id . '_' . $receiptId . '.' . $ext;
        $file->move($dir, $filename);

        $newReceipt = [
            'id'       => (int) $receiptId,
            'amount'   => (float) $request->amount,
            'filename' => $filename,
        ];

        if (!isset($tasks[$taskIndex]['receipts'])) {
            $tasks[$taskIndex]['receipts'] = [];
        }

        $tasks[$taskIndex]['receipts'][] = $newReceipt;
        $this->saveTasks($tasks);

        // Calculate new task total and overall total
        $taskTotal = array_sum(array_column($tasks[$taskIndex]['receipts'], 'amount'));
        $overallTotal = 0.0;
        foreach ($tasks as $t) {
            if (isset($t['receipts']) && is_array($t['receipts'])) {
                $overallTotal += array_sum(array_column($t['receipts'], 'amount'));
            }
        }

        return response()->json([
            'success' => true,
            'receipt' => [
                'id'       => $newReceipt['id'],
                'amount'   => $newReceipt['amount'],
                'url'      => asset('images/jj_receipts/' . $filename),
                'filename' => $filename,
            ],
            'task_total'    => $taskTotal,
            'overall_total' => $overallTotal,
        ]);
    }

    public function deleteReceipt($id)
    {
        $tasks = $this->getTasks();
        $receiptDeleted = false;
        $taskTotal = 0.0;
        $overallTotal = 0.0;

        foreach ($tasks as &$task) {
            if (isset($task['receipts'])) {
                foreach ($task['receipts'] as $idx => $receipt) {
                    if ($receipt['id'] == $id) {
                        $fp = public_path('images/jj_receipts/' . $receipt['filename']);
                        if (file_exists($fp)) {
                            unlink($fp);
                        }
                        unset($task['receipts'][$idx]);
                        $task['receipts'] = array_values($task['receipts']);
                        $receiptDeleted = true;
                        $taskTotal = array_sum(array_column($task['receipts'], 'amount'));
                        break;
                    }
                }
            }
        }

        if (!$receiptDeleted) {
            return response()->json(['success' => false, 'error' => 'Receipt not found'], 404);
        }

        $this->saveTasks($tasks);

        // Calculate overall total
        foreach ($tasks as $t) {
            if (isset($t['receipts']) && is_array($t['receipts'])) {
                $overallTotal += array_sum(array_column($t['receipts'], 'amount'));
            }
        }

        return response()->json([
            'success'       => true,
            'task_total'    => $taskTotal,
            'overall_total' => $overallTotal,
        ]);
    }

    public function destroy($id)
    {
        $tasks = $this->getTasks();
        $taskIndex = -1;

        foreach ($tasks as $idx => $task) {
            if ($task['id'] == $id) {
                $taskIndex = $idx;
                break;
            }
        }

        if ($taskIndex === -1) {
            return response()->json(['success' => false, 'error' => 'Task not found'], 404);
        }

        // Delete all photos from disk
        if (isset($tasks[$taskIndex]['photos'])) {
            foreach ($tasks[$taskIndex]['photos'] as $photo) {
                $fp = public_path('images/jj_tasks/' . $photo['filename']);
                if (file_exists($fp)) {
                    unlink($fp);
                }
            }
        }

        // Delete all receipts from disk
        if (isset($tasks[$taskIndex]['receipts'])) {
            foreach ($tasks[$taskIndex]['receipts'] as $receipt) {
                $fp = public_path('images/jj_receipts/' . $receipt['filename']);
                if (file_exists($fp)) {
                    unlink($fp);
                }
            }
        }

        unset($tasks[$taskIndex]);
        $tasks = array_values($tasks);

        $this->saveTasks($tasks);

        return response()->json(['success' => true]);
    }
}
