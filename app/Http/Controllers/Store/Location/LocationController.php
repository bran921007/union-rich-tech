<?php

namespace App\Http\Controllers\Store\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Location\LocationCreateRequest;
use App\Http\Requests\Store\Location\LocationQueueRequest;
use App\Http\Requests\Store\Location\LocationStoreRequest;
use App\Models\Shopper\Shopper;
use App\Models\Store\Location\Location;
use App\Services\Shopper\ShopperService;
use App\Services\Store\Location\LocationService;
use Illuminate\Http\Request;

/**
 * Class LocationController
 * @package App\Http\Controllers\Store
 */
class LocationController extends Controller
{
    /**
     * @var LocationService
     */
    protected $location;

    /**
     * @var ShopperService
     */
    protected $shopper;

    /**
     * LocationController constructor.
     * @param LocationService $location
     */
    public function __construct(LocationService $location, ShopperService $shopper)
    {
        $this->location = $location;
        $this->shopper = $shopper;
    }

    /**
     * @param Location $location
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function public(Location $location)
    {
        return view('stores.location.public')
            ->with('location', $location);
    }

    /**
     * @param LocationCreateRequest $request
     * @param string $storeUuid
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(LocationCreateRequest $request, string $storeUuid)
    {
        return view('stores.location.create')
            ->with('store', $storeUuid);
    }

    /**
     * @param LocationStoreRequest $request
     * @param string $storeUuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LocationStoreRequest $request, string $storeUuid): \Illuminate\Http\RedirectResponse
    {
        $this->location->create([
            'location_name' => $request->location_name,
            'shopper_limit' => $request->shopper_limit,
            'store_id' => $storeUuid
        ]);

        return redirect()->route('store.store', ['store' => $storeUuid]);
    }

    /**
     * @param LocationQueueRequest $request
     * @param string $storeUuid
     * @param string $locationUuid
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function queue(LocationQueueRequest $request, string $storeUuid, string $locationUuid)
    {
        $location = $this->location->show(
            [
                'uuid' => $locationUuid
            ],
            [
                'Shoppers',
                'Shoppers.Status',
                'Store'
            ]
        );
        
        $shoppers = null;

        if( isset($location['shoppers']) && count($location['shoppers']) >= 1 ){
            $shoppers = $this->location->getShoppers($location['shoppers']);
        }

        return view('stores.location.queue')
            ->with('location', $location)
            ->with('shoppers', $shoppers);
    }

     /**
     * @param Request $request
     * @param string $storeUuid
     * @param string $shopperUuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkout(Request $request, string $storeUuid = '', string $shopperUuid)
    {
        $shopper = $this->shopper->show(
            [
                'uuid' => $shopperUuid
            ],
            [
                'Location'
            ]
        );

        $this->shopper->update($shopper['id'], [
            'status_id' => Shopper::COMPLETED,
            'check_out' => now()
        ]);

        $this->shopper->updatePendingShoppersByLocation($shopper['location_id']);

        return redirect()->route('store.location.queue', ['storeUuid' => $storeUuid, 'locationUuid' => $shopper['location']['uuid']])->with('success', 'Shopper has been checked out.');
    }

     /**
     * @param Request $request
     * @param string $storeUuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateShopperLimit(Request $request, $storeUuid = null)
    {   
        $location = $this->location->show(
            [
                'uuid' => $request->location
            ]
        );
        
        $this->location->update($location['id'], [
            'shopper_limit' => $request->shopper_limit
        ]);

        return redirect()->back()->with('success', 'Location limit has been updated.');
    }
}
