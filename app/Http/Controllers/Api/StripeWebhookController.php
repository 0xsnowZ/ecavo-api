<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhooks.
     * POST /api/webhook/stripe
     */
    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret') ?: env('STRIPE_WEBHOOK_SECRET');

        $event = null;

        if ($secret && $sigHeader) {
            try {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
            } catch (\UnexpectedValueException $e) {
                Log::warning('Stripe webhook payload invalid: ' . $e->getMessage());
                return response()->json(['error' => 'Invalid payload'], 400);
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                Log::warning('Stripe webhook signature invalid: ' . $e->getMessage());
                return response()->json(['error' => 'Invalid signature'], 400);
            }
        } else {
            // Fallback for development without signature verification
            $data = json_decode($payload, true);
            if (!$data) {
                return response()->json(['error' => 'Invalid JSON'], 400);
            }
            $event = (object)$data;
        }

        $eventType = is_object($event) ? ($event->type ?? null) : ($event['type'] ?? null);

        if ($eventType === 'payment_intent.succeeded') {
            $intent = is_object($event->data) ? $event->data->object : (object)($event['data']['object'] ?? []);
            $intentId = $intent->id ?? null;

            if ($intentId) {
                $order = Order::where('payment_id', $intentId)->first();
                if ($order) {
                    Log::info("Stripe Webhook: Payment confirmed for Order #{$order->id} (Intent: {$intentId})");
                } else {
                    Log::warning("Stripe Webhook: Payment succeeded for Intent {$intentId}, but no matching order was found yet.");
                }
            }
        } elseif ($eventType === 'payment_intent.payment_failed') {
            $intent = is_object($event->data) ? $event->data->object : (object)($event['data']['object'] ?? []);
            $intentId = $intent->id ?? null;
            Log::warning("Stripe Webhook: Payment failed for Intent {$intentId}");
        }

        return response()->json(['status' => 'success']);
    }
}
