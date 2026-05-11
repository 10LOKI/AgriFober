<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherData extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'weather_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'parcel_id',
        'region',
        'temp',
        'humidity',
        'precipitation',
        'wind_speed',
        'condition',
        'source',
        'recorded_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'temp' => 'float',
            'humidity' => 'float',
            'precipitation' => 'float',
            'wind_speed' => 'float',
            'recorded_at' => 'datetime',
        ];
    }

    /**
     * Get the parcel that owns the weather data.
     */
    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }
}
