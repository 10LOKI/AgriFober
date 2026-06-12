<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteractionIA extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'interaction_ias';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'parcel_id',
        'conversation_id',
        'type',
        'input_mode',
        'prompt_text',
        'response_data',
        'image_path',
        'tokens_used',
        'feedback_rating',
        'engine',
        'model_version',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'response_data' => 'array',
            'type' => \App\Enums\InteractionTypeEnum::class,
            'input_mode' => \App\Enums\InputModeEnum::class,
            'tokens_used' => 'integer',
            'feedback_rating' => 'integer',
        ];
    }

    /**
     * Get the user that owns the interaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parcel for this interaction.
     */
    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }
}
