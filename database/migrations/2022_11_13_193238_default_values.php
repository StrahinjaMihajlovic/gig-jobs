<?php

use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DefaultValues extends Migration
{
    public function __construct(...$params)
    {
        # It seems that Doctrine doesn't like double data types, therefore the code block below.
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gigs', function (Blueprint $table) {
            $table->double('pay_per_hour')->default(0.0)->change();
            $table->integer('number_of_positions')->default(0)->change();
            $table->boolean('status')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gigs', function (Blueprint $table) {
            $table->double('pay_per_hour')->change();
            $table->integer('number_of_positions')->change();
            $table->boolean('status')->change();
        });
    }
}
