<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('driver_id');
            $table->string('company');
            $table->string('contact');
            $table->string('po');
            $table->date('put_date');
            $table->date('del_date');
            $table->string('origin');
            $table->string('destination');
            $table->float('weight', 8, 2);
            $table->float('revenue', 8, 2);
            $table->float('mile', 8, 2);
            $table->float('dho', 8, 2);
            $table->float('rpm', 8, 2);
            $table->float('dh_rpm', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('details');
    }
}
