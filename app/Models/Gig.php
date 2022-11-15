<?php

namespace App\Models;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Builder;

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

    /** 
     * Returns user that owns the company owning this gig
     * @return HasOneThrough
     */
    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Company::class, 'id', 'id', 'company_id', 'user_id');
    }

    /**
     * A scope function filtering gigs by a name of the company they belong to.
     * @param Builder $query
     * @param str $companyString
     * 
     * @return Builder
     */
    public function scopeByCompanyString(Builder $query, $companyString) 
    {
        return $query->whereRelation('company', 'name', 'like', "%$companyString%");
    }

    /**
     * A scope function filtering gigs by draft or published status.
     * @param Builder $query
     * @param bool $status
     * 
     * @return Builder
     */
    public function scopeByStatus(Builder $query, $status) 
    {
        return $query->where('status', $status);
    }

    /**
     * A scope function filtering the gigs by the time frame of their duration.
     * @param Builder $query
     * @param array<int,str> $progress
     * 
     * @return Builder
     */
    public function scopeByProgress(Builder $query, $progress)
    {
        $query->where(function (Builder $query) use ($progress) {
            $time = Carbon::now();
            
            if ($progress === 'not_started') {
                $query->orWhere('timestamp_start', '>=', $time);
            }

            if ($progress === 'started') {
                $query->orWhere(function(Builder $query) use ($time) {
                    $query->where('timestamp_start', '<', $time)
                    ->where('timestamp_end', '>', $time);
                });
            }

            if ($progress === 'finished') {
                $query->orWhere('timestamp_end', '<=', $time);     
            }
        });

        return $query;
    }

    /**
     * A scope function filtering gigs by the description or name search.
     * @param Builder $query
     * @param str $searchString
     * 
     * @return Builder
     */
    public function ScopeBySearch(Builder $query, $searchString)
    {
        return $query->where(function (Builder $query) use ($searchString) {
            $query->orWhere('gigs.name', 'like', "%$searchString%")
                ->orWhere('gigs.description', 'like', "%$searchString%");
        });
    }
}
