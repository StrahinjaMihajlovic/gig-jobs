<?php 

namespace App\Services;

use App\Http\Resources\GigResource;
use App\Models\Gig;
use Illuminate\Support\Facades\Auth;

/**
 * A service class containing methods used by gig controllers
 */
class GigService 
{
    public function listAllGigs($filters, $numberOfPages)
    {
        $gigs = Auth::user()->gigs();

        foreach ($filters as $filter => $value) {
            $this->parseFilter($filter, $value, $gigs);
        }

        return $gigs->paginate($numberOfPages);
    }

    protected function parseFilter($filter, $value, &$gigs) 
    {
        if ($filter === 'progress') {
            $gigs->ByProgress($value);
        }

        if ($filter === 'status') {
            $gigs->ByStatus($value);
        }
        
        if ($filter === 'company') {
            $gigs->ByCompanyString($value);
        }

        if ($filter === 'search_string') {
            $gigs->bySearch($value);
        }
    }

    public function createGig($data, $dates, $company)
    {
        $gig = new Gig();
        $gig->fill($data);
        $gig->timestamp_start = $dates['start_date'];
        $gig->timestamp_end = $dates['end_date'];
        $gig->company_id = $company;

        return $gig->save() ? true : false;
    }

    public function updateGig($data, $dates, $company, Gig $gig)
    {
        $gig->fill($data);
        $gig->timestamp_start = $dates['start_date'];
        $gig->timestamp_end = $dates['end_date'];
        $gig->company_id = $company;

        return $gig->save() ? new GigResource($gig) : false;
    }
}