<?php

namespace App\Services\Shopper;

use Illuminate\Support\Arr;
use App\Services\BaseService;
use App\Models\Shopper\Shopper;
use App\Models\Store\Location\Location;
use App\Repositories\Shopper\ShopperRepository;

/**
 * Class ShopperService
 * @package App\Services\Shopper
 */
class ShopperService extends BaseService
{
    /**
     * @var ShopperRepository
     */
    protected $shopper;

    /**
     * ShopperService constructor.
     * @param ShopperRepository $shopper
     */
    public function __construct(ShopperRepository $shopper)
    {
        $this->shopper = $shopper;
        parent::__construct($this->shopper);
    }

    /**
     * Check in shopper in the Queue list
     * @param array $params
     * @return array
     */
    public function create(array $params = [])
    {

        $locationUuid = Arr::get($params, 'location_uuid');
        $location = Location::where('uuid',$locationUuid)->first();

        $shopperLimit = $location->shopper_limit;
        $shopperActiveCount = $location->shoppers()->where('status_id',1)->count();
        
        if($shopperLimit < $shopperActiveCount){   
            $params['status'] = 'Pending';
        }
        if($shopperLimit >= $shopperActiveCount){
            $params['status'] = 'Active';
        }
        
        $statusId = collect([
            'Pending' => 3,
            'Active'  => 1,
        ])->get($params['status']);

        $userData = Arr::only($params, ['first_name', 'last_name', 'email']);
        
        Arr::set($userData, 'status_id', $statusId);
        Arr::set($userData, 'location_id', $locationUuid);

        $shopper =  $location->shoppers()->create($userData);

        return array_merge($shopper->toArray(), ['status' => $params['status']]);
    }


    /**
     * Get the next pending shopper from a location order by check_in datetime
     * @param $locationUuid
     * @return mixed
     */
    public function getNextPendingShopper($locationId)
    {
        $shopper = $this->shopper->all(null,
            [ 
              'location_id'=> $locationId, 
              'status_id'  => Shopper::PENDING,
            ], null, null,
            [
                'check_in' => 'asc'
            ], null, 1);

        return array_merge(...array_values($shopper));
    }

    /**
     * update all pending shoppers until shopper limit is reached
     * @param $locationUuid
     * @return mixed
     */
    public function updatePendingShoppersByLocation($locationId)
    {
        $pendingShopper = $this->shopper->all(null,
            [ 
              'location_id'=> $locationId, 
              'status_id'  => Shopper::PENDING,
            ], null, null,
            [
                'check_in' => 'asc'
            ]);

        $activeShoppers = $this->shopper->all(null,
            [ 
              'location_id'=> $locationId, 
              'status_id'  => Shopper::ACTIVE,
            ], null, null,
            [
                'check_in' => 'asc'
            ]);

        $pendingShopper = array_values($pendingShopper);
        $shopperLimit = Location::find($locationId)->shopper_limit;
       
        if(count($activeShoppers) + count($pendingShopper) > $shopperLimit){
            $pendingShopper = array_slice($pendingShopper, 0, $shopperLimit - count($activeShoppers));
        }
       
        foreach($pendingShopper as $shopper){
            $this->shopper->update($shopper['id'], [
                'status_id' => Shopper::ACTIVE,
                'check_out' => now()
            ]);
        }
    }
}
