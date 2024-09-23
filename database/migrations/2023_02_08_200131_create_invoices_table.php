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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('bill_code')->nullable();
            $table->string('client_id')->nullable();
            $table->date('billed_date')->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('iva', 10, 2)->nullable();
            $table->decimal('retained_iva', 10, 2)->nullable();
            $table->decimal('isr', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
