<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesBlocksTable extends Migration
{
    public function up()
    {
        Schema::create('pages_blocks', function (Blueprint $table) {
            $table->bigInteger('page_id');
            $table->bigInteger('block_id');
            $table->string('label');
            $table->timestamps();
        });
    }
}
