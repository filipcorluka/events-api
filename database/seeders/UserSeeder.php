<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$user = User::create([
            'email'	   => 'user@events.com',
            'password' => Hash::make('UserPW1?'),
		]);
        $user->save();
		$user = User::create([
            'email'	   => 'test@events.com',
            'password' => Hash::make('TestPW1?'),
		]);
        $user->save();
    }
}
