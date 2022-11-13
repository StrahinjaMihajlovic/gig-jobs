<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(protected CompanyService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageNum = $request->query('per_page');
        $companiesCollection = $this->service->listUserCompanies($pageNum);

        return response()->json($companiesCollection);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'string',
            'address' => 'required|string|max:255'
        ]);

        $company = $this->service->createCompany($data);

        if ($company) {

            return response()->json([
                'message' => 'success',
                'company' => $company
            ], 201);
        } else {

            return response()->json([
                'message' => 'failure',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => 'string|max:100',
            'description' => 'string',
            'address' => 'string|max:250'
        ]);

        $updatedCompany = $this->service->updateCompany($company, $data);

        if ($updatedCompany) {
            return response()->json([
                'message' => 'success',
                'company' => $updatedCompany
            ]);
        } else {
            return response()->json([
                'message' => 'failure',
                'company' => $company
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        return $company->delete() 
        ? response()->json(['message' => 'success'], 200)
        : response()->json(['message' => 'success'], 500);
    }
}
