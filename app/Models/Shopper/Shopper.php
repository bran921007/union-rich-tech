<?php

namespace App\Models\Shopper;

use App\Models\Store\Location\Location;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Shopper
 * @package App\Models\Shopper
 */
class Shopper extends Model
{
    use HasFactory, SoftDeletes;

    const ACTIVE    = 1;
    const COMPLETED = 2;
    const PENDING   = 3;

    /**
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'status_id',
        'location_id',
        'check_out'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function getStatusAttribute()
    {
        if(!$this->attributes['status_id']) {
           return null;
        }
        
        return collect([
            self::ACTIVE    => 'Active',
            self::COMPLETED => 'Completed',
            self::PENDING   => 'Pending',
        ])->get($this->status_id);
    }

    //update the status of the shopper
    public function updateStatus($status_id)
    {
        $this->status_id = $status_id;
        $this->save();
    }
}
