<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;
use App\Models\User;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles');
            $table->string('u_name');
            $table->string('u_email')->unique();
            $table->string('u_pass'); 
            $table->enum('status', ['Active', 'Pending'])->default('Pending');
            $table->rememberToken(); 
            $table->timestamps();
        });

        $users = [
            [
            'role_id' => 1,
            'u_name' => 'Admin Admin',
            'u_email' => 'admin@gmail.com',
            'u_pass' => Hash::make('password'),
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
         ],
        ];

        foreach($users as $user){
            User::create($user);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};