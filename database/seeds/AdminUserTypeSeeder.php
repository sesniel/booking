<?php

use App\User;
use Illuminate\Database\Seeder;

class AdminUserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->states('admin')->create([
            'email' => 'admin@booking.com',
            'password' => 'bookingAdmin123ABC',
            'fname' => 'Admin',
            'lname' => 'Admin',
        ]);
    }
}
