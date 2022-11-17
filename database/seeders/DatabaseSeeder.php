<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Gig;
use App\Models\User;
use Illuminate\Database\Seeder;
use \Faker\Generator;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
        ->count(10000)
        ->create()
        ->each(function (User $user) {
            $user->companies()
            ->saveMany(Company::factory()
            ->count((new Generator())->numberBetween(1,3))
            ->create(['user_id' => $user->id])
            ->each( function (Company $company) {
                $company->gigs()
                ->saveMany(Gig::factory()
                ->count((new Generator())->numberBetween(2,5))
                ->create(['company_id' => $company->id]));
            }));
        });
    }
}
