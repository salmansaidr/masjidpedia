<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\User::class, 1)->create();
        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@gmail.com';
        $user->email_verified_at = now();
        $user->password = \Hash::make('password');
        $user->role = 'admin';
        $user->remember_token = Str::random(10);
        $user->save();
    }
}
