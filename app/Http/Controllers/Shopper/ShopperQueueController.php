<?php

namespace App\Http\Controllers\Shopper;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckInRequest;
use App\Services\Shopper\ShopperService;
use Illuminate\Http\Request;

class ShopperQueueController extends Controller
{

    /**
     * @var ShopperService
     */
    protected $shopper;

    /**
     * LocationController constructor.
     * @param ShopperService $shopper
     */
    public function __construct(ShopperService $shopper)
    {
        $this->shopper = $shopper;
    }
        
    
    /**
     * Store shopper in queue
     * @param $locationUuid
     * @param CheckInRequest $request
     */
    public function checkIn($locationUuid, CheckInRequest $request)
    {

        $data = array_merge($request->all(), ['location_uuid' => $locationUuid]);
        $shopper = $this->shopper->create($data);

        return back()->with('success', 'You have been checked in. Your status is: '.$shopper['status']);
    }

}
