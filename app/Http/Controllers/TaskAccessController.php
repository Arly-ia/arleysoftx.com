<?php

namespace App\Http\Controllers;

use App\Models\TaskLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskAccessController extends Controller
{
    /**
     * Muestra la página de acceso con el formulario de clave.
     */
    public function showAccessPage(Request $request)
    {
        // Si ya tiene sesión activa, redirigir directamente al tutorial
        if (session('task_tutorial_paid')) {
            return redirect()->route('tutorial.task');
        }

        $error   = $request->session()->pull('access_error');
        $success = $request->session()->pull('access_success');

        return view('task_access', compact('error', 'success'));
    }

    /**
     * Verifica la clave de acceso y la huella del dispositivo.
     */
    public function verifyKey(Request $request)
    {
        $request->validate([
            'license_key'          => 'required|string|max:24',
            'device_fingerprint'   => 'nullable|string|max:255',
        ]);

        $key         = strtoupper(trim($request->input('license_key')));
        $fingerprint = $request->input('device_fingerprint', '');

        // Si el fingerprint está vacío, usar User-Agent como fallback
        if (empty($fingerprint)) {
            $fingerprint = md5($request->userAgent() . $request->ip());
        }

        $license = TaskLicense::where('license_key', $key)->first();

        // 1. Clave no existe
        if (!$license) {
            return back()->with('access_error', 'Clave de acceso inválida. Verifica que la escribiste correctamente.');
        }

        // 2. Clave desactivada por el administrador
        if (!$license->is_active) {
            return back()->with('access_error', 'Esta clave de acceso ha sido revocada. Contacta al soporte si crees que es un error.');
        }

        // 3. Verificar dispositivo
        if ($license->hasDevice($fingerprint)) {
            // Dispositivo ya conocido → acceso directo
            $this->grantAccess($license);
            return redirect()->route('tutorial.task')->with('success', '¡Bienvenido de vuelta! Acceso concedido.');
        }

        // 4. Intentar agregar nuevo dispositivo
        if ($license->canAddDevice()) {
            $license->addDevice($fingerprint);
            $this->grantAccess($license);
            return redirect()->route('tutorial.task')->with('success', '¡Acceso concedido! Disfruta el contenido.');
        }

        // 5. Demasiados dispositivos
        Log::warning("TaskLicense: intento de acceso desde dispositivo no autorizado. key={$key} fingerprint={$fingerprint}");
        return back()->with('access_error',
            '⚠️ Esta clave ya está siendo usada en el número máximo de dispositivos permitidos. ' .
            'Si cambiaste de dispositivo, contacta al soporte para reactivar tu acceso.'
        );
    }

    /**
     * Revoca (desactiva) una licencia desde el panel de admin.
     */
    public function revokeLicense(Request $request, int $id)
    {
        if (session('task_admin_logged') !== true) {
            abort(403);
        }
        $license = TaskLicense::findOrFail($id);
        $license->is_active = false;
        $license->save();
        return back()->with('success', "Clave {$license->license_key} revocada exitosamente.");
    }

    /**
     * Resetea los dispositivos de una licencia desde el panel de admin.
     */
    public function resetDevices(Request $request, int $id)
    {
        if (session('task_admin_logged') !== true) {
            abort(403);
        }
        $license = TaskLicense::findOrFail($id);
        $license->resetDevices();
        return back()->with('success', "Dispositivos de la clave {$license->license_key} reseteados. El comprador podrá volver a ingresar.");
    }

    /**
     * Guarda en sesión que el usuario tiene acceso válido.
     */
    private function grantAccess(TaskLicense $license): void
    {

        session([
            'task_tutorial_paid'        => true,
            'task_license_key'          => $license->license_key,
            'task_license_email'        => $license->email,
        ]);
    }
}
