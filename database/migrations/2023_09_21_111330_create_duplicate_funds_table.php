<?php

use App\Models\Fund;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('duplicate_funds', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Fund::class)->constrained();

            $table->unsignedBigInteger('duplicate_fund_id');
            $table->foreign('duplicate_fund_id')->references('id')->on('funds');
            $table->unique(['fund_id', 'duplicate_fund_id']);

            $table->softDeletes();
            $table->timestamps();


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duplicate_funds');
    }
};
