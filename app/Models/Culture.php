<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Culture extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom_commun',
        'nom_scientifique',
        'type',
        'saison',
        'ph_sol_min',
        'ph_sol_max',
        'temp_min',
        'temp_max',
        'besoin_eau_cycle',
        'soil_type',
        'conseils',
        'region'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => \App\Enums\CultureTypeEnum::class,
            'saison' => \App\Enums\SaisonEnum::class,
            'soil_type' => \App\Enums\SoilTypeEnum::class,
            'ph_sol_min' => 'float',
            'ph_sol_max' => 'float',
            'temp_min' => 'integer',
            'temp_max' => 'integer',
            'besoin_eau_cycle' => 'integer',
        ];
    }

    /**
     * Get the products for this culture.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'culture_product')
            ->withPivot('dosage_specifique', 'notes')
            ->withTimestamps();
    }

    /**
     * Get the parcels for this culture.
     */
    public function parcels()
    {
        return $this->hasMany(Parcel::class);
    }
}
