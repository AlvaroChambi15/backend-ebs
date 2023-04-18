<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $user1 = new User();
        $user1->name = "Alvaro";
        $user1->email = "alvaro@gmail.com";
        $user1->role = "primary";
        $user1->permiso = true;
        $user1->password = bcrypt("alvaro123");
        $user1->save();

        $user2 = new User();
        $user2->name = "Juan";
        $user2->email = "juan@gmail.com";
        $user2->role = "guest";
        $user2->permiso = true;
        $user2->password = bcrypt("juan123");
        $user2->save();
    }
}