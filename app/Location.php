<?php

namespace App;

use App\Contracts\Resource;
use App\Relationships\LocationRelationships;
use App\Resources\LocationResource;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

class Location extends Model implements Resource
{
    use SpatialTrait;
    use LocationResource;
    use LocationRelationships;

    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "trackable_id",
        "trackable_type",
        "device",
        "uuid",
        "location",
        "accuracy",
        "altitude",
        "speed",
        "heading",
        "odometer",
        "event",
        "activity_type",
        "activity_confidence",
        "battery_level",
        "battery_is_charging",
        "is_moving",
        "generated_at",
    ];

    /**
     * The attributes that should be cast to spatial types.
     *
     * @var array
     */
    protected $spatialFields = [
        'location',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'device' => 'array',
        'generated_at' => 'datetime',
    ];

    /**
     * The properties which are hidden.
     *
     * @var array
     */
    protected $hidden = ['id'];
}
