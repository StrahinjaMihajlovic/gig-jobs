<?php 

namespace App\Services;

use App\Http\Resources\GigResource;
use App\Models\Gig;
use Illuminate\Database\Eloquent\Collection;
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

    /**
     * Applies wanted filters to the gigs queries.
     * Discussion of efficiency: Since this function applies only where clauses
     * with different params on the non trivial fields, the execute time can be improved,
     * especially for the textual fields, by adding the appropriate type of indexes
     * on (or just some of) columns 'status', 'timestamp_start', 'timestamp_end', 'name' and 'description'
     * on the gigs columns. Since this will sort the dataset and make the search easier for
     * the DB system. Needs to be analyzed which indexes bring the most performance, since
     * adding an index impacts negatively the performance on DB writes.
     * Another solution would be using some third party service or app which specialize
     * on searching datasets, e.g ElasticSearch which will do most of heavy lifting
     * with searching and just return needed keys which DB can use to pull the related
     * rows without applying filters itself.
     * @param str $filter
     * @param str $value
     * @param Collection $gigs
     * 
     * @return void
     */
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