<?php

namespace App\Services;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

/**
 * A service class containing methods for serving the company controller
 */
class CompanyService 
{
    public function listUserCompanies($pageSize)
    {
        $companies = Company::where('user_id', Auth::user()->id)->paginate($pageSize);

        return CompanyResource::collection($companies);
    }

    public function createCompany($data)
    {
        $company = new Company();
        $company->fill($data);
        $company->user_id = Auth::user()->id;

        return $company->save() ?  new CompanyResource($company) : false;
    }

    public function updateCompany(Company $company, $data)
    {
       return $company->update($data);
    }

    public function deleteCompany(Company $company)
    {
       return $company->delete();
    }
}