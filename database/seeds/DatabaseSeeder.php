<?php

use App\User;
use App\Roles;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

    	User::truncate();
    	Roles::truncate();

        factory(Roles::class, 1)->create(['role'=>'Hr Office']);
        factory(Roles::class, 1)->create(['role'=>'Tax Office']);
        factory(Roles::class, 1)->create(['role'=>'Bank Office']);

        factory(User::class, 10)->create();

    }
}
