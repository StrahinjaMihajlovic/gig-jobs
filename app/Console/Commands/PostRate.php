<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PostRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post_rate:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the post rate of the users, which is a number
    of posted gigs vs number of created gigs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * Discussion of efficiency: This part is best done in as few steps as possible,
     * to avoid facing the n + 1 problem for 100000+ rows.
     * 
     * The solution presented below avoids the n + 1 problem by 
     * passing through whole users table and calculates gigs ratio for every user 
     * from derived tables, all in one MySQL update query.
     * 
     * At the time of testing, it needs ~1,5s - ~3s of a run time to execute for
     * 10.000 randomly generated users, ~10.000 companies and ~70.000 gigs on a AMD a6-6310 cpu,
     * which would be acceptable since it is a perfect candidate for a scheduled 
     * background job. 
     * 
     * There can be further improvements to the query, by limiting
     * the number of users who would be updated to ones who made recent changes to 
     * their gigs, thus avoiding the unnecessary update of the whole users table. 
     * 
     * One way of that being done is firing an event for every gig that is created/deleted or
     * it's status column was changed, and writing the necessary data to a cache system 
     * (e.g. Redis) from which we can pull the users who needs to have their post rate updated.
     * 
     * Also, this probably is not the most optimal query, so further analysis with tools
     * like MySQL explain function and experimentation would be required.
     * @return int
     */
    public function handle()
    {
        DB::statement('
            update users 
            join (
                select count(gigs.id) as total_gigs, user_id from gigs  
                join companies on companies.id = gigs.company_id 
                group by user_id order by user_id
            ) as all_gigs on users.id = all_gigs.user_id 
            left join 
            (
                select count(gigs.id) as active_gigs, user_id from gigs  
                join companies
                on companies.id = gigs.company_id 
                where gigs.status = 1 
                group by user_id order by user_id
            ) as active_gigs on active_gigs.user_id = users.id 
            set users.posted_rate = (IFNULL(active_gigs, 0) / total_gigs) * 100
        ');
    }
}
