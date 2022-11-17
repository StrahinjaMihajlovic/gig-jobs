<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gigs\GigsCreateRequest;
use App\Http\Requests\Gigs\GigsListRequest;
use App\Http\Requests\Gigs\GigsUpdateRequest;
use App\Http\Resources\GigResource;
use App\Models\Company;
use App\Models\Gig;
use App\Services\GigService;
use Illuminate\Http\Request;

class GigController extends Controller
{
    public function __construct(protected GigService $service) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GigsListRequest $request)
    {
        $filters = $request->validated();
        $pageNum = $request->query('per_page', 15);
        $gigs = $this->service->listAllGigs($filters, $pageNum);

        return response()->json(GigResource::collection($gigs));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GigsCreateRequest $request)
    {
        $company = $request->input('company');
        $data = $request->validated();
        $dates = $request->only(['start_date', 'end_date']);

        $gig = $this->service->createGig($data, $dates, $company);

        if ($gig) {

            return response()->json([
                'message' => 'success',
                'gig' => $gig
            ], 201);
        } else {

            return response()->json([
                'message' => 'failed',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Gig $gig)
    {
        return response()->json(new GigResource($gig));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  GigsUpdateRequest $request
     * @param  Gig  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GigsUpdateRequest $request, Gig $gig)
    {
        $data = $request->validated();
        $dates = $request->only(['start_date', 'end_date']);
        $company = $request->company;
        $updatedGig = $this->service->updateGig($data, $dates, $company, $gig);

        return response()->json([
            'message' => $updatedGig ? 'success' : 'failed',
            'gig' => new GigResource($gig)
        ], $updatedGig ? 200 : 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gig $gig)
    {
        return response()->json([
            'message' => $gig->delete() ? 'success' : 'failed'
        ]);
    }
}
