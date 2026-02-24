<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message to a given phone number.
     * In a real application, this would integrate with a WhatsApp Business API
     * like those provided by Meta or a third-party service like Twilio, MessageBird, etc.
     *
     * @param string $phoneNumber The recipient's phone number (e.g., "6281234567890").
     * @param string $message The message content.
     * @param string|null $templateName The name of the approved message template (for WhatsApp Business API).
     * @param array $templateParams Parameters to fill in the message template.
     * @return bool True if message was "sent" (or attempted to send), false otherwise.
     */
    public function sendMessage(string $phoneNumber, string $message, ?string $templateName = null, array $templateParams = []): bool
    {
        // TODO: Implement actual WhatsApp API integration here.
        // This is a placeholder. You would typically use Guzzle HTTP client
        // to call the WhatsApp Business API endpoint.

        Log::info("Attempting to send WhatsApp message to {$phoneNumber}:");
        Log::info("Message: {$message}");
        if ($templateName) {
            Log::info("Using template: {$templateName}");
            Log::info("Template Params: " . json_encode($templateParams));
        }

        try {
            // Example:
            // $client = new \GuzzleHttp\Client();
            // $response = $client->post('https://graph.facebook.com/v19.0/<YOUR_PHONE_NUMBER_ID>/messages', [
            //     'headers' => [
            //         'Authorization' => 'Bearer <YOUR_ACCESS_TOKEN>',
            //         'Content-Type' => 'application/json',
            //     ],
            //     'json' => [
            //         'messaging_product' => 'whatsapp',
            //         'to' => $phoneNumber,
            //         'type' => $templateName ? 'template' : 'text',
            //         'template' => $templateName ? [
            //             'name' => $templateName,
            //             'language' => ['code' => 'id'], // Or other language
            //             'components' => [
            //                 [
            //                     'type' => 'body',
            //                     'parameters' => array_map(function($param) {
            //                         return ['type' => 'text', 'text' => $param];
            //                     }, $templateParams)
            //                 ]
            //             ]
            //         ] : [
            //             'body' => $message,
            //         ],
            //     ],
            // ]);
            //
            // $statusCode = $response->getStatusCode();
            // if ($statusCode === 200) {
            //     Log::info("WhatsApp message sent successfully to {$phoneNumber}.");
            //     return true;
            // } else {
            //     Log::error("Failed to send WhatsApp message to {$phoneNumber}. Status: {$statusCode}, Response: " . $response->getBody());
            //     return false;
            // }

            // Simulate success for now
            Log::info("WhatsApp message simulated as sent to {$phoneNumber}.");
            return true;
        } catch (Exception $e) {
            Log::error("Error sending WhatsApp message to {$phoneNumber}: " . $e->getMessage());
            return false;
        }
    }
}
