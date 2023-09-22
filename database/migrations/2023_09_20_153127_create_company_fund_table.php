<?php

use App\Models\Company;
use App\Models\Fund;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('company_fund', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->constrained();
            $table->foreignIdFor(Fund::class)->constrained();
            $table->unique(['company_id', 'fund_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_fund');
    }
};
