<?php

namespace App\Http\Controllers;

use App\Services\ShippingService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    public function provinces()
    {
        return response()->json($this->shippingService->getProvinces());
    }

    public function cities($provinceId)
    {
        return response()->json($this->shippingService->getCities($provinceId));
    }

    public function calculateCost(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
            'weight' => 'required|numeric',
            'courier' => 'required|in:jne,pos,tiki',
        ]);

        return response()->json($this->shippingService->calculateCost(
            $request->city_id,
            $request->weight,
            $request->courier
        ));
    }
}
