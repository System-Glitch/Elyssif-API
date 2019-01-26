<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateContactUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_user', function (Blueprint $table) {
            $table->integer('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->integer('contact_id');
            $table->foreign('contact_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->string('notes')->nullable();
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
        /*
        Schema::table('contact_user', function($table) {
            // Attention, le drop sera impossible si ces champs n'existent pas
            $table->dropForeign(['user_id']);
            $table->dropForeign(['contact_id']);
        });
        */

        Schema::dropIfExists('contact_user');
    }
}