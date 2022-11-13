<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address'
    ];

    /**
     * Returns the user this company belongs to
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return all gigs associated with the given company
     * @return HasMany
     */
    public function gigs(): HasMany
    {
        return $this->hasMany(Gig::class);
    }
}
