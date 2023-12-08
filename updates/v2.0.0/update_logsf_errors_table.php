<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //trace_log('ok je met à jours');
        Schema::table('waka_salesforce_logsf_errors', function (Blueprint $table) {
            $table->renameColumn('logsf_id', 'log_sf_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waka_salesforce_logsf_errors', function (Blueprint $table) {
            $table->renameColumn('log_sf_id', 'logsf_id');
        });
    }
};
