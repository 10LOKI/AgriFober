<?php

namespace App\Models;

use App\Enums\ReportStatusEnum;
use App\Enums\ReportTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'parcel_id',
        'culture_id',
        'titre',
        'type',
        'status',
        'resume',
        'programme',
        'donnees',
        'generated_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => ReportTypeEnum::class,
            'status' => ReportStatusEnum::class,
            'programme' => 'array',
            'donnees' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parcel this report concerns.
     */
    public function parcel(): BelongsTo
    {
        return $this->belongsTo(Parcel::class);
    }

    /**
     * Get the culture this report concerns.
     */
    public function culture(): BelongsTo
    {
        return $this->belongsTo(Culture::class);
    }

    /**
     * Get the change-log entries for this report.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(ReportHistory::class);
    }
}
