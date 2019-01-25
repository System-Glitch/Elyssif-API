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
            $table->integer('sender_id');
            $table->integer('recipient_id');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('ciphered_at');
            $table->timestamp('deciphered_at')->nullable();
            $table->string('hash');
            $table->string('hash_ciphered')->nullable();
            $table->sha256('public_key');
            $table->sha256('private_key');
            $table->double('price')->nullable();
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
