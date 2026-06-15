<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
                'password' => md5('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bob Buyer',
                'email' => 'bob@example.com',
                'password' => md5('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('transactions')->insert([
            [
                'user_id' => 1,
                'product_name' => 'Wireless Mouse',
                'price' => 29.90,
                'quantity' => 1,
                'shipping_address' => '12 Lab Street',
                'note' => 'Leave at door',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'product_name' => 'USB-C Charger',
                'price' => 49.90,
                'quantity' => 2,
                'shipping_address' => '88 Demo Avenue',
                'note' => 'Office delivery',
                'status' => 'paid',
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
