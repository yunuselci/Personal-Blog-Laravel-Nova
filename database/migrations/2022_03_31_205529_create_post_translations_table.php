<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_translations', function (Blueprint $table): void {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('post_id');
            $table->unique(['post_id', 'locale']);
            $table->foreign('post_id')->references('id')->on('posts')
                ->onDelete('cascade')
            ;
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->longText('description');
            $table->string('slug');
            $table->integer('dev_to_article_id')->nullable();
            $table->boolean('published')->nullable()->default(0);
            $table->boolean('publish_to_dev_to')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_translations');
    }
};
