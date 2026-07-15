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
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (empty($sessionId)) {
            return redirect()->route('tutorial.landing')->with('error', 'Sesión de pago no válida o incompleta.');
        }

        $stripeSecret = config('services.stripe.secret');
        $paymentDetails = null;
        $isPaid = false;

        if (!empty($stripeSecret)) {
            try {
                // Consultamos a Stripe sobre el estado de la sesión para validarla
                $response = Http::withBasicAuth($stripeSecret, '')
                    ->get("https://api.stripe.com/v1/checkout/sessions/{$sessionId}");

                if ($response->successful()) {
                    $sessionData = $response->json();
                    $paymentDetails = $sessionData;
                    if ($sessionData['payment_status'] === 'paid') {
                        $isPaid = true;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error consultando sesión de Stripe: ' . $e->getMessage());
            }
        } else {
            // Si el desarrollador entra directamente sin llaves, permitimos ver la demo
            $isPaid = true;
        }

        if ($isPaid) {
            session(['task_tutorial_paid' => true]);
            session(['task_tutorial_session_id' => $sessionId]);
            return redirect()->route('tutorial.task')->with('success', '¡Acceso Concedido! Tu pago se ha procesado con éxito.');
        }

        return redirect()->route('tutorial.landing')->with('error', 'El pago no ha sido completado o verificado.');
    }

    /**
     * Muestra la página de pago cancelado.
     */
    public function cancel()
    {
        return view('stripe.cancel');
    }
}
