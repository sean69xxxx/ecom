<!--updated become secure-->
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash; 

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('product_name');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->text('shipping_address');
            $table->text('note')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        DB::table('users')->insertOrIgnore([
            [
                'name' => 'Alice Customer',
                'email' => 'alice@example.com',
                // 2. CHANGE MD5 TO HASH::MAKE
                'password' => Hash::make('password123'), 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bob Buyer',
                'email' => 'bob@example.com',
                // 2. CHANGE MD5 TO HASH::MAKE
                'password' => Hash::make('password123'), 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
