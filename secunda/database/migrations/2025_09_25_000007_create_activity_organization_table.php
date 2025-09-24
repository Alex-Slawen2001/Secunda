<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_organization', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('activity_id');
            $table->primary(['organization_id', 'activity_id']);
            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->foreign('activity_id')->references('id')->on('activities')->cascadeOnDelete();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('activity_organization');
    }
};
