<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockContentsTable extends Migration
{
    public function up()
    {
        Schema::create('block_contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('block_id');
            $table->bigInteger('page_id')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }
}
