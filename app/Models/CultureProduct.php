<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CultureProduct extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'culture_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'culture_id',
        'product_id',
        'dosage_specifique',
        'notes',
    ];

    /**
     * Get the culture for this pivot.
     */
    public function culture()
    {
        return $this->belongsTo(Culture::class);
    }

    /**
     * Get the product for this pivot.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
