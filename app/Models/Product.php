<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom_commercial',
        'description',
        'composant_actif',
        'dosage_recommande',
        'delai_avant_recolte',
        'type',
        'avantages',
        'usage_method',
        'safety_instructions',
        'image',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => \App\Enums\ProductTypeEnum::class,
            'delai_avant_recolte' => 'integer',
        ];
    }

    /**
     * Get the cultures for this product.
     */
    public function cultures()
    {
        return $this->belongsToMany(Culture::class, 'culture_product')
            ->withPivot('dosage_specifique', 'notes')
            ->withTimestamps();
    }
}
