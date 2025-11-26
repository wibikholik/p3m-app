<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('usulan_reviewer', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('usulan_id');
        $table->unsignedBigInteger('reviewer_id');
        $table->unsignedBigInteger('assigned_by')->nullable();
        $table->timestamp('assigned_at')->nullable();
        $table->timestamp('accepted_at')->nullable();
        $table->timestamp('declined_at')->nullable();
        $table->timestamp('submitted_at')->nullable();
        $table->timestamp('deadline')->nullable();
        $table->enum('status', ['assigned','accepted','declined','submitted','overdue'])->default('assigned');
        $table->text('catatan_assign')->nullable();
        $table->text('catatan_reviewer')->nullable();
        $table->timestamps();

        $table->foreign('usulan_id')->references('id')->on('usulans')->onDelete('cascade');
        $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('assigned_by')->references('id')->on('users')->onDelete('set null');

        $table->unique(['usulan_id','reviewer_id']);
    });
}

public function down()
{
    Schema::dropIfExists('usulan_reviewer');
}
};
