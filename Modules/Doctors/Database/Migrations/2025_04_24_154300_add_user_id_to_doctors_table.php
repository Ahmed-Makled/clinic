<?php

namespace Modules\Doctors\Database\Migrations;

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
        Schema::table('doctors', function (Blueprint $table) {
            // Add user_id column after id
            $table->unsignedBigInteger('user_id')->nullable()->after('id');

            // Create foreign key constraint
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Add index for faster lookups
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Remove foreign key constraint
            $table->dropForeign(['user_id']);

            // Remove index
            $table->dropIndex(['user_id']);

            // Remove column
            $table->dropColumn('user_id');
        });
    }
};
