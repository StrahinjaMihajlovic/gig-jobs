<?php

namespace App\Models;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function scopeByCompanyString(Builder $query, $companyString) 
    {
        return $query->whereRelation('company', 'name', 'like', "%$companyString%");
    }

    public function scopeByStatus(Builder $query, $status) 
    {
        return $query->where('status', $status);
    }

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
    }
}
