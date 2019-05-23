<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('transactions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id')->unsigned();
            $table->foreign('file_id')
            ->references('id')
            ->on('files')
            ->onDelete('cascade');
            $table->string('txid')->index();
            $table->boolean('confirmed')->default(0);
            $table->double('amount')->default(0);
            $table->timestamps();
        });
    }
}
