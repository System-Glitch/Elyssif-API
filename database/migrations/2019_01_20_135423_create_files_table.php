<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id')->unsigned();
            $table->foreign('sender_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->integer('recipient_id')->unsigned();
            $table->foreign('recipient_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->timestamps();
            $table->timestamp('ciphered_at')->nullable();
            $table->timestamp('deciphered_at')->nullable();
            $table->string('hash');
            $table->string('hash_ciphered')->nullable();
            $table->string('public_key');
            $table->string('private_key');
            $table->string('address');
            $table->double('price')->default(0);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}