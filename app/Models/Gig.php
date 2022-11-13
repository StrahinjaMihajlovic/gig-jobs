<?php

namespace App\Models;

use Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gig extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'company_id'
    ];

    protected $casts = [
        'timestamp_start' => 'datetime',
        'timestamp_end' => 'datetime'
    ];

    /**
     * Returns a company this gig belongs to
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
