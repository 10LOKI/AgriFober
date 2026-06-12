<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'culture_id',
        'nom',
        'surface',
        'date_plantation',
        'date_recolte_estimee',
        'status',
        'health_score',
        'latitude',
        'longitude',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'surface' => 'decimal:4',
            'health_score' => 'float',
            'date_plantation' => 'date',
            'date_recolte_estimee' => 'date',
            'status' => \App\Enums\ParcelStatusEnum::class,
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    /**
     * Get the user that owns the parcel.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the culture for this parcel.
     */
    public function culture()
    {
        return $this->belongsTo(Culture::class);
    }

    /**
     * Get the interaction IAs for this parcel.
     */
    public function interactionIas()
    {
        return $this->hasMany(InteractionIA::class);
    }

    /**
     * Get the weather data for this parcel.
     */
    public function weatherData()
    {
        return $this->hasMany(WeatherData::class);
    }

    /**
     * Get the reports generated for this parcel.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
