<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetnetSearches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('getnet_searches', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('restrict');
            
            $table->string('subseller_id')->nullable();
    
            $table->string('query_params')->nullable();
    
            $table->dateTime('requested_at')->nullable();
            $table->dateTime('ended_at')->nullable();
    
            $table->mediumInteger('time_get_api_data')->nullable();
            $table->mediumInteger('time_script_execution')->nullable();
    
            $table->unsignedInteger('list_transactions_count')->nullable();
            $table->longText('list_transactions_node')->nullable();
            
            $table->unsignedInteger('commission_count')->nullable();
            $table->longText('commission_node')->nullable();
            
            $table->unsignedInteger('adjustments_count')->nullable();
            $table->longText('adjustments_node')->nullable();
            
            $table->unsignedInteger('chargeback_count')->nullable();
            $table->longText('chargeback_node')->nullable();
    
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
        Schema::dropIfExists('getnet_searches');
    }
}
