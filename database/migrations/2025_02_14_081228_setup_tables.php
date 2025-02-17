<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('colleges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('acronym', 50);
            $table->timestamps();
        });

        Schema::create('team_compositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_id')->constrained('colleges')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('medal_weight_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('category_no');
            $table->string('name');
            $table->decimal('gold_weight', 10, 2);
            $table->decimal('silver_weight', 10, 2);
            $table->decimal('bronze_weight', 10, 2);
            $table->timestamps();
        });

        Schema::create('sports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('event_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_double_bronze');
            $table->foreignId('sport_id')->constrained('sports')->onDelete('cascade');
            $table->foreignId('medal_weight_category_id')->constrained('medal_weight_categories')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('medal_records', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('gold_tally')->default(0);
            $table->integer('silver_tally')->default(0);
            $table->integer('bronze_tally')->default(0);
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('event_category_id')->constrained('event_categories')->onDelete('cascade');
            $table->foreignId('entry_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('posted_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_posted')->default(false);
            $table->integer('number_of_participant')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medal_record');
        Schema::dropIfExists('event_category');
        Schema::dropIfExists('sports');
        Schema::dropIfExists('medal_weight_category');
        Schema::dropIfExists('team_composition');
        Schema::dropIfExists('college');
        Schema::dropIfExists('teams');
    }
};
