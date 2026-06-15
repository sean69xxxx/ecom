<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('insecure_users')) {
            $users = DB::table('insecure_users')->get();

            foreach ($users as $user) {
                DB::table('users')->updateOrInsert(
                    ['email' => $user->email],
                    [
                        'name' => $user->name,
                        'password' => $user->password,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]
                );
            }

            Schema::dropIfExists('insecure_users');
        }

        if (Schema::hasTable('insecure_transactions') && ! Schema::hasTable('transactions')) {
            Schema::rename('insecure_transactions', 'transactions');
        }

        if (! Schema::hasTable('transactions')) {
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
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('transactions') && ! Schema::hasTable('insecure_transactions')) {
            Schema::rename('transactions', 'insecure_transactions');
        }
    }
};
