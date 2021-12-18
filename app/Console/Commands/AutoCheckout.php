<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Shopper\Shopper;
use Illuminate\Console\Command;
use App\Services\Shopper\ShopperService;

class AutoCheckout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkout:update-shoppers-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Shoppers Status';

    private $shopper;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ShopperService $shopper)
    {
        parent::__construct();
        $this->shopper = $shopper;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $shoppers = $this->shopper->all(null,
        [ 
            'status_id'  => Shopper::ACTIVE,
        ], null, null,
        [
            'check_in' => 'asc'
        ]);

        $expiringShoppers = collect($shoppers)->filter(function($shopper){
            $diffInMinutes = Carbon::parse($shopper['check_in'])->diffInMinutes(Carbon::now(), false);
            if($diffInMinutes >= config('checkout.limit-time')){
                return $shopper;
            }
        });
       

        foreach($expiringShoppers as $shopper)
        {
            $this->shopper->update($shopper['id'], [
                    'status_id' => Shopper::COMPLETED,
                    'check_out' => now()
            ]);
            $this->line('Shopper '.$shopper['id'].' has mark as completed');
        }

        

        return Command::SUCCESS;
    }
}
