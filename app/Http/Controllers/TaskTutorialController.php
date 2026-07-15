<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TaskTutorialController extends Controller
{
    /**
     * Devuelve la ruta al archivo JSON de tareas.
     */
    protected function getJsonPath()
    {
        return storage_path('app/tasks.json');
    }

    /**
     * Lee la lista de tareas del archivo JSON. Si no existe, lo crea con la lista por defecto.
     */
    protected function getTasks()
    {
        $path = $this->getJsonPath();

        if (!File::exists($path)) {
            // Asegurar que el directorio exista
            $dir = dirname($path);
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            // Sembrar datos iniciales
            $defaultTasks = $this->getDefaultTasks();
            File::put($path, json_encode($defaultTasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return $defaultTasks;
        }

        $content = File::get($path);
        return json_decode($content, true) ?: [];
    }

    /**
     * Muestra la vista pública del tutorial.
     */
    public function index(Request $request)
    {
        // 1. Si viene session_id en la query de la URL, intentamos verificarlo en Stripe
        if ($request->has('session_id')) {
            $sessionId = $request->query('session_id');
            $stripeSecret = config('services.stripe.secret');

            if (!empty($stripeSecret)) {
                try {
                    $response = \Illuminate\Support\Facades\Http::withBasicAuth($stripeSecret, '')
                        ->get("https://api.stripe.com/v1/checkout/sessions/{$sessionId}");

                    if ($response->successful()) {
                        $sessionData = $response->json();
                        if ($sessionData['payment_status'] === 'paid') {
                            session(['task_tutorial_paid' => true]);
                            session(['task_tutorial_session_id' => $sessionId]);
                        }
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error validando session_id en index: ' . $e->getMessage());
                }
            } else {
                // Si no hay keys de Stripe en .env, permitimos acceso en desarrollo/local
                session(['task_tutorial_paid' => true]);
            }
        }

        // 2. Si no tiene sesión de pago, redirigir a la landing page de ventas
        if (!session('task_tutorial_paid')) {
            $stripeSecret = config('services.stripe.secret');
            if (!empty($stripeSecret)) {
                return redirect()->route('tutorial.landing')->with('error', 'Debes adquirir la guía para acceder a este contenido.');
            }
        }

        $tasks = $this->getTasks();
        return view('tutorial_task', compact('tasks'));
    }

    /**
     * Muestra la vista del panel de administración.
     */
    public function adminIndex()
    {
        $tasks = $this->getTasks();
        return view('tutorial_task_admin', compact('tasks'));
    }

    /**
     * Guarda los cambios enviados por el administrador.
     */
    public function save(Request $request)
    {
        // Validar contraseña
        $adminPassword = env('TASK_ADMIN_PASSWORD', 'arleysoft123');
        if ($request->input('admin_password') !== $adminPassword) {
            return back()->with('error', 'Contraseña de administrador incorrecta. Los cambios no fueron aplicados.')->withInput();
        }

        $tasksData = $request->input('tasks', []);
        $tasks = [];
        $destinationPath = public_path('images');

        foreach ($tasksData as $i => $taskData) {
            // Si el checkbox de eliminar tarea está marcado, se omite
            if (isset($taskData['delete_task']) && $taskData['delete_task'] == '1') {
                // Eliminar foto física si existía
                $oldImageName = $taskData['image_hidden'] ?? '';
                if (!empty($oldImageName) && file_exists($destinationPath . '/' . $oldImageName)) {
                    @unlink($destinationPath . '/' . $oldImageName);
                }
                continue;
            }

            $task = [
                'title' => $taskData['title'] ?? 'Nueva labor',
                'pago' => $taskData['pago'] ?? '$0/hour',
                'categoria' => $taskData['categoria'] ?? 'Projects',
                'requisitos' => $taskData['requisitos'] ?? '',
                'instrucciones' => $taskData['instrucciones'] ?? '',
                'image' => $taskData['image_hidden'] ?? ''
            ];

            // Procesar eliminación de imagen si se solicitó
            if (isset($taskData['delete_image']) && $taskData['delete_image'] == '1') {
                $oldImageName = $task['image'];
                if (!empty($oldImageName) && file_exists($destinationPath . '/' . $oldImageName)) {
                    @unlink($destinationPath . '/' . $oldImageName);
                }
                $task['image'] = '';
            }

            // Procesar subida de nueva imagen
            if ($request->hasFile("tasks.{$i}.image_file")) {
                $file = $request->file("tasks.{$i}.image_file");
                if ($file->isValid()) {
                    $slug = Str::slug($task['title']);
                    $filename = time() . '_' . $slug . '.webp';

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    $tempPath = $file->getRealPath();
                    $converted = false;

                    // Convertir a WebP usando la librería GD si está disponible
                    if (function_exists('imagewebp') && function_exists('imagecreatefromstring')) {
                        $imageContent = file_get_contents($tempPath);
                        $image = @imagecreatefromstring($imageContent);
                        if ($image !== false) {
                            imagealphablending($image, false);
                            imagesavealpha($image, true);
                            imagewebp($image, $destinationPath . '/' . $filename, 75);
                            imagedestroy($image);
                            $converted = true;
                        }
                    }

                    if (!$converted) {
                        // Fallback: copiar con extensión original
                        $filename = time() . '_' . $slug . '.' . $file->getClientOriginalExtension();
                        $file->move($destinationPath, $filename);
                    }

                    // Eliminar foto anterior si existía
                    $oldImageName = $task['image'];
                    if (!empty($oldImageName) && file_exists($destinationPath . '/' . $oldImageName)) {
                        @unlink($destinationPath . '/' . $oldImageName);
                    }

                    $task['image'] = $filename;
                }
            }

            $tasks[] = $task;
        }

        // Guardar de vuelta en el archivo JSON
        $path = $this->getJsonPath();
        File::put($path, json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return back()->with('success', 'Cambios y actualizaciones guardados correctamente en el portal.');
    }

    /**
     * Lista inicial por defecto de 26 labores.
     */
    protected function getDefaultTasks()
    {
        return [
            [
                'title' => 'Cut material',
                'pago' => '$25/hour',
                'categoria' => 'Projects',
                'requisitos' => 'Head mount, Landscape, Visible hands, Continuous cutting, Show result steady',
                'instrucciones' => 'Keep at least one hand visible in the frame at all times. Record continuous cutting of a material, clearly capturing the cutting process and motion. Ensure your hands, tools, and material are visible, then hold the cut result steady in frame to show the outcome.',
                'image' => ''
            ],
            [
                'title' => 'Trim or prune plants',
                'pago' => '$20/hour',
                'categoria' => 'Projects',
                'requisitos' => 'Head mount, Landscape, Both hands visible, 3 branches, Full motion, Show result steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Record trimming at least 3 branches clearly, showing the full cutting motion with hands and plants visible. Hold each trimmed result steady in frame for a few seconds before moving to the next branch.',
                'image' => ''
            ],
            [
                'title' => 'Wipe Surfaces',
                'pago' => '$15/hour',
                'categoria' => 'Chores',
                'requisitos' => 'Head mount, Landscape, Visible hand, Continuous wiping, Show cleaned area steady',
                'instrucciones' => 'Keep at least one hand visible in the frame at all times. Ensure your hand, cloth, and surface are clearly visible, and record continuous wiping. Hold the cleaned area steady in frame to clearly show the result.',
                'image' => ''
            ],
            [
                'title' => 'Make a drink',
                'pago' => '$15/hour',
                'categoria' => 'Chores',
                'requisitos' => 'Head mount, Landscape, Both hands visible, 5-20 actions, Show finished drink steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Continuously prepare a drink using 5-20 actions (e.g., pouring, measuring, stirring, blending) with hands clearly visible throughout. End by holding the finished drink steady in frame for a few seconds.',
                'image' => ''
            ],
            [
                'title' => 'Clean refrigerator shelf',
                'pago' => '$15/hour',
                'categoria' => 'Chores',
                'requisitos' => 'Head mount, Landscape, Both hands visible, Clean 1 full shelf, Show shelf steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Record removing items, wiping and cleaning at least 1 full shelf & replace items continuously, with hands and items visible throughout. Hold the cleaned shelf steady in frame for a few seconds before ending.',
                'image' => ''
            ],
            [
                'title' => 'Pour liquids',
                'pago' => '$15/hour',
                'categoria' => 'Cooking',
                'requisitos' => 'Head mount, Landscape, Both hands visible, 1 type of liquid, Pour slowly, Show final level steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Record pouring at least 1 type of liquid from one container to another slowly, steadily and continuously. Hold the final liquid level steady in frame for a few seconds before ending the video.',
                'image' => ''
            ],
            [
                'title' => 'Unpack grocery bags',
                'pago' => '$20/hour',
                'categoria' => 'Chores',
                'requisitos' => 'Head mount, Landscape, Both hands visible, >2 bags, Sort and store, Show result',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Remove all items from more than 2 grocery bags and place them on the counter or appropriate surface. Sort and store each item in its proper location (refrigerator, pantry, freezer, etc.).',
                'image' => ''
            ],
            [
                'title' => 'Measure and mark surfaces',
                'pago' => '$15/hour',
                'categoria' => 'Projects',
                'requisitos' => 'Head mount, Landscape, Visible hands, Continuous measuring/marking, Show final marked area steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Record continuous measuring and marking across the surface, ensuring your hands, measuring tool, and surface are clearly visible throughout. Hold the final marked area steady in frame to clearly show the result.',
                'image' => ''
            ],
            [
                'title' => 'Install or replace faucets',
                'pago' => '$25/hour',
                'categoria' => 'Projects',
                'requisitos' => 'Head mount, Landscape, Visible hands, Continuous process, Show installed faucet steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Record continuous installation or replacement of a faucet, clearly capturing the full process and key steps. Hold the final installed faucet steady in frame, ensuring your hands, tools, and the faucet are clearly visible.',
                'image' => ''
            ],
            [
                'title' => 'Sew, knit or crochet',
                'pago' => '$25/hour',
                'categoria' => 'Projects',
                'requisitos' => 'Head mount, Landscape, Visible hands, Continuous process, Show stitches/result steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Record continuous sewing, knitting, or crocheting, clearly capturing the process and motion throughout. Hold the finished piece or section steady in frame, ensuring the stitches and result are clearly visible.',
                'image' => ''
            ],
            [
                'title' => 'Explore a mall',
                'pago' => '$20/hour',
                'categoria' => 'Navigation',
                'requisitos' => 'Chest mount, Landscape, Level camera, Walk intersections/crowd, Pause at directory',
                'instrucciones' => 'Keep camera level showing walkway and storefronts. Record walking through intersections and crowd flow. Pause at directory sign or open atrium.',
                'image' => ''
            ],
            [
                'title' => 'Pot or repot plants',
                'pago' => '$20/hour',
                'categoria' => 'Projects',
                'requisitos' => 'Head mount, Landscape, Visible hands, Transfer plant + soil, Show final state steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Record transferring at least 1 plant into a new pot continuously, including adding soil and adjusting placement. Hold the final planted state steady in frame for a few seconds before ending the video.',
                'image' => ''
            ],
            [
                'title' => 'Install light fixtures or switch plates',
                'pago' => '$25/hour',
                'categoria' => 'Projects',
                'requisitos' => 'Head mount, Landscape, Visible hands, Continuous process, Show installed result steady',
                'instrucciones' => 'Keep at least one hand visible in the frame at all times. Record continuous installation of a light fixture or switch plate, ensuring your hands, tools, and the fixture or plate are clearly visible throughout. Hold the final installed result steady in frame to clearly show the outcome.',
                'image' => ''
            ],
            [
                'title' => 'Take out the trash & replace liner',
                'pago' => '$15/hour',
                'categoria' => 'Chores',
                'requisitos' => 'Head mount, Landscape, Visible hand, Continuous process, Show bin state steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Record removing trash outside and placing a new liner continuously, with hand, trash bins and liner visible throughout. Hold the final bin state steady in frame for a few seconds before ending.',
                'image' => ''
            ],
            [
                'title' => 'Mix and apply cement or mortar',
                'pago' => '$25/hour',
                'categoria' => 'Projects',
                'requisitos' => 'Head mount, Landscape, Visible hands, Continuous process, Show applied surface steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Record continuous mixing and application of cement or mortar, ensuring your hands, tools, and materials are clearly visible throughout. Hold the final applied surface steady in frame to clearly show the result.',
                'image' => ''
            ],
            [
                'title' => 'Drill or fasten materials',
                'pago' => '$25/hour',
                'categoria' => 'Projects',
                'requisitos' => 'Head mount, Landscape, Visible hands, Continuous process, Show secured result steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Record aligning and fastening at least 1 full attachment continuously, showing the complete fastening motion. Hold the secured result steady in frame for a few seconds before ending the video.',
                'image' => ''
            ],
            [
                'title' => 'Navigate an apartment complex',
                'pago' => '$15/hour',
                'categoria' => 'Navigation',
                'requisitos' => 'Chest mount, Landscape, Level camera, Walk shared spaces, Pause at intersections/entry',
                'instrucciones' => 'Keep hallway or walkway centered and level. Record continuous walking through shared spaces. Pause at intersections or entry points.',
                'image' => ''
            ],
            [
                'title' => 'Cook a dish',
                'pago' => '$20/hour',
                'categoria' => 'Cooking',
                'requisitos' => 'Head mount, Landscape, Visible hand, Continuous process, Show state change result steady',
                'instrucciones' => 'Keep at least one hand visible in the frame at all times. Record the cooking process, including flipping or repositioning, with hand and items fully visible. Hold on visible state change (browning, bubbling, texture change).',
                'image' => ''
            ],
            [
                'title' => 'Cut and chop food',
                'pago' => '$20/hour',
                'categoria' => 'Cooking',
                'requisitos' => 'Head mount, Landscape, Both hands visible, Continuous cutting, Show result steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Cut multiple ingredients, recording continuous, steady cutting that clearly shows different cutting motions. Hold the chopped results steady in frame to clearly show the outcome.',
                'image' => ''
            ],
            [
                'title' => 'Explore a museum',
                'pago' => '$20/hour',
                'categoria' => 'Navigation',
                'requisitos' => 'Chest mount, Landscape, Level camera, Walk between rooms, Pause at display/transition',
                'instrucciones' => 'Keep camera level showing exhibits and room layout. Record natural walking between rooms. Pause at display or transition point.',
                'image' => ''
            ],
            [
                'title' => 'Unload and organize dishwasher',
                'pago' => '$20/hour',
                'categoria' => 'Chores',
                'requisitos' => 'Head mount, Landscape, Visible hands, Continuous process, Show empty dishwasher steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Record yourself unloading a dishwasher continuously, with items and hands visible throughout. Remove and put away dishes into their proper places. Hold the final empty dishwasher steady in frame for a few seconds before ending.',
                'image' => ''
            ],
            [
                'title' => 'Install or tighten pipes',
                'pago' => '$25/hour',
                'categoria' => 'Projects',
                'requisitos' => 'Head mount, Landscape, Visible hand, Continuous process, Show secured connection steady',
                'instrucciones' => 'Keep at least one hand visible in the frame at all times. Ensure hands and pipe connection are visible. Record tightening and alignment clearly. Hold secured connection steady.',
                'image' => ''
            ],
            [
                'title' => 'Prepare a meal',
                'pago' => '$25/hour',
                'categoria' => 'Chores',
                'requisitos' => 'Head mount, Landscape, Both hands visible, 5-20 actions, Show finished meal steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Continuously prepare a meal using 5-20 actions (e.g., washing, cutting, mixing, cooking, assembling, plating) with hands clearly visible throughout. End by holding the finished meal steady in frame for a few seconds.',
                'image' => ''
            ],
            [
                'title' => 'Install fixtures',
                'pago' => '$25/hour',
                'categoria' => 'Projects',
                'requisitos' => 'Head mount, Landscape, Visible hand, Continuous process, Show installed fixture steady',
                'instrucciones' => 'Keep at least one hand visible in the frame at all times. Record installing one full fixture continuously, showing alignment, fastening, and any necessary adjustments. Hold the fully installed fixture steady in frame for a few seconds before ending the video.',
                'image' => ''
            ],
            [
                'title' => 'Record a natural conversation in Spanish',
                'pago' => '$20/hour',
                'categoria' => 'Language',
                'requisitos' => 'Spontaneous/unscripted, Audible voices, No hate/political/PII',
                'instrucciones' => 'Have a natural, unscripted conversation with your friends or family on everyday topics. Keep it spontaneous. Topic changes and interruptions are fine. Ensure voices are audible. Do not include hate speech, political content, explicit topics, or any personally identifiable information.',
                'image' => ''
            ],
            [
                'title' => 'Clean kitchen',
                'pago' => '$25/hour',
                'categoria' => 'Chores',
                'requisitos' => 'Head mount, Landscape, Visible hands, Continuous progress, Show result steady',
                'instrucciones' => 'Keep both hands visible in the frame at all times. Record yourself building, assembling, or repairing a physical object. Your hands must be visible and actively interacting with tools or materials throughout the video. Capture continuous progress on the task. At the end, hold the finished or partially completed result steady in frame for a few seconds.',
                'image' => ''
            ]
        ];
    }
}
