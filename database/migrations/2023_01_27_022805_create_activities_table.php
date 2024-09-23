<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('unit_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('site_id')->nullable();
            $table->integer('site_service_id')->nullable();
            $table->integer('expense_id')->nullable();
            $table->date('date')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->integer('billable')->nullable();
            $table->integer('billed')->nullable();
            $table->integer('paid')->nullable();
            $table->date('payment_date')->nullable();
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
        Schema::dropIfExists('activities');
    }
};
