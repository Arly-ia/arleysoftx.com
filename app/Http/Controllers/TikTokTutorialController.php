<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TikTokTutorialController extends Controller
{
    /**
     * Muestra la página de ventas del tutorial (landing page).
     */
    public function showLanding()
    {
        $hasKeys = !empty(config('services.stripe.key')) && !empty(config('services.stripe.secret'));
        return view('stripe.landing', compact('hasKeys'));
    }

    /**
     * Crea la sesión de Stripe Checkout y redirige al usuario a Stripe.
     */
    public function createCheckoutSession(Request $request)
    {
        $stripeSecret = config('services.stripe.secret');

        if (empty($stripeSecret)) {
            return back()->with('error', 'La clave secreta de Stripe no está configurada en el archivo .env.');
        }

        try {
            // Creamos la sesión usando el cliente HTTP de Laravel sin librerías externas
            $response = Http::withBasicAuth($stripeSecret, '')
                ->asForm()
                ->post('https://api.stripe.com/v1/checkout/sessions', [
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'price_data' => [
                                'currency' => 'usd',
                                'product_data' => [
                                    'name' => 'Guías y Tutoriales Task',
                                    'description' => 'Acceso completo a videotutoriales de optimización, guías técnicas en PDF y recursos digitales descargables.',
                                ],
                                'unit_amount' => 2700, // $27.00 USD en centavos
                            ],
                            'quantity' => 1,
                        ]
                    ],
                    'mode' => 'payment',
                    'allow_promotion_codes' => 'true',
                    'success_url' => route('tutorial.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('tutorial.cancel'),
                ]);

            if ($response->failed()) {
                $errorData = $response->json();
                $errorMessage = isset($errorData['error']['message']) ? $errorData['error']['message'] : 'Error desconocido al conectar con Stripe.';
                Log::error('Stripe API error: ' . $response->body());
                return back()->with('error', 'Error de Stripe: ' . $errorMessage);
            }

            $session = $response->json();

            // Redirigimos a la URL de checkout de Stripe
            return redirect($session['url']);

        } catch (\Exception $e) {
            Log::error('Stripe checkout exception: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error inesperado al iniciar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la página de éxito tras el pago completado.
     * Genera una clave de licencia única y la envía por email al comprador.
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (empty($sessionId)) {
            return redirect()->route('tutorial.landing')->with('error', 'Sesión de pago no válida o incompleta.');
        }

        $stripeSecret  = config('services.stripe.secret');
        $isPaid        = false;
        $buyerEmail    = null;
        $sessionData   = null;

        if (!empty($stripeSecret)) {
            try {
                // Consultamos a Stripe sobre el estado de la sesión para validarla
                $response = Http::withBasicAuth($stripeSecret, '')
                    ->get("https://api.stripe.com/v1/checkout/sessions/{$sessionId}");

                if ($response->successful()) {
                    $sessionData = $response->json();
                    if ($sessionData['payment_status'] === 'paid') {
                        $isPaid     = true;
                        $buyerEmail = $sessionData['customer_details']['email'] ?? null;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error consultando sesión de Stripe: ' . $e->getMessage());
            }
        } else {
            // Si el desarrollador entra directamente sin llaves, permitimos ver la demo
            $isPaid     = true;
            $buyerEmail = 'demo@arleysoftx.com';
        }

        if ($isPaid) {
            // Generar o recuperar la clave de licencia para esta sesión de Stripe
            $licenseKey = $this->getOrCreateLicense($sessionId, $buyerEmail);

            // Enviar email con la clave (si SMTP está configurado)
            if ($buyerEmail && $licenseKey) {
                $this->sendLicenseEmail($buyerEmail, $licenseKey);
            }

            // Redirigir a la página de éxito mostrando la clave
            return view('stripe.success', [
                'licenseKey' => $licenseKey,
                'buyerEmail' => $buyerEmail,
            ]);
        }

        return redirect()->route('tutorial.landing')->with('error', 'El pago no ha sido completado o verificado.');
    }

    /**
     * Obtiene la licencia existente para una sesión de Stripe o crea una nueva.
     */
    private function getOrCreateLicense(string $sessionId, ?string $email): ?string
    {
        try {
            $license = \App\Models\TaskLicense::where('stripe_session_id', $sessionId)->first();

            if ($license) {
                return $license->license_key;
            }

            $key     = \App\Models\TaskLicense::generateKey();
            $license = \App\Models\TaskLicense::create([
                'license_key'       => $key,
                'email'             => $email,
                'stripe_session_id' => $sessionId,
                'is_active'         => true,
                'max_devices'       => 2,
            ]);

            Log::info("TaskLicense created: key={$key} email={$email} session={$sessionId}");
            return $key;

        } catch (\Exception $e) {
            Log::error('Error creating TaskLicense: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Envía el email con la clave de acceso al comprador.
     */
    private function sendLicenseEmail(string $email, string $licenseKey): void
    {
        try {
            \Illuminate\Support\Facades\Mail::send(
                'emails.task_license',
                ['licenseKey' => $licenseKey],
                function ($message) use ($email, $licenseKey) {
                    $message->to($email)
                            ->subject("🔐 Tu Clave de Acceso: {$licenseKey} — Tutorial TASK");
                }
            );
            Log::info("License email sent to {$email} with key {$licenseKey}");
        } catch (\Exception $e) {
            Log::warning("Could not send license email to {$email}: " . $e->getMessage());
            // No interrumpimos el flujo si el email falla
        }
    }

    /**
     * Muestra la página de pago cancelado.
     */
    public function cancel()
    {
        return view('stripe.cancel');
    }
}
