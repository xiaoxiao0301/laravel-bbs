<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index()->comment('帖子标题');
            $table->text('body')->comment('帖子内容');
            $table->unsignedBigInteger('user_id')->index()->comment('用户ID');
            $table->unsignedBigInteger('category_id')->index()->comment('分类ID');
            $table->integer('reply_count')->index()->default(0)->comment('回复数量');
            $table->integer('view_count')->index()->default(0)->comment('查看总数');
            $table->unsignedBigInteger('last_reply_user_id')->index()->default(0)->comment('最后回复的用户ID');
            $table->integer('order')->default(0)->comment('排序');
            $table->text('excerpt')->nullable()->comment('文章摘要，SEO优化时使用');
            $table->string('slug')->nullable()->comment('SEO友好的URL');
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
        Schema::dropIfExists('topics');
    }
}
