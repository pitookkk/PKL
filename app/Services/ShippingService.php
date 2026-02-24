<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ShippingService
{
    protected $baseUrl = 'https://api.rajaongkir.com/basic'; // Switched from starter to basic
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.key');
    }

    /**
     * Get all provinces.
     */
    public function getProvinces()
    {
        $response = Http::withHeaders(['key' => $this->apiKey])->get("{$this->baseUrl}/province");
        
        if ($response->failed()) {
            \Illuminate\Support\Facades\Log::error('RajaOngkir API Error (Provinces): ' . $response->body());
            return [];
        }

        return $response->json()['rajaongkir']['results'] ?? [];
    }

    /**
     * Get cities by province ID.
     */
    public function getCities($provinceId)
    {
        $response = Http::withHeaders(['key' => $this->apiKey])->get("{$this->baseUrl}/city", [
            'province' => $provinceId
        ]);

        if ($response->failed()) {
            \Illuminate\Support\Facades\Log::error('RajaOngkir API Error (Cities): ' . $response->body());
            return [];
        }

        return $response->json()['rajaongkir']['results'] ?? [];
    }

    /**
     * Calculate shipping cost.
     */
    public function calculateCost($destinationCityId, $weight, $courier)
    {
        $response = Http::withHeaders(['key' => $this->apiKey])->post("{$this->baseUrl}/cost", [
            'origin'        => config('services.rajaongkir.origin'), // ID Kota asal (toko Anda)
            'destination'   => $destinationCityId,
            'weight'        => $weight,
            'courier'       => $courier
        ]);

        return $response->json()['rajaongkir']['results'][0]['costs'] ?? [];
    }
}
