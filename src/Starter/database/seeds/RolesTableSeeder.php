<?php

use Illuminate\Database\Seeder;
use {{App\}}Repositories\Role\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //initial basic role
        $roles = ['admin', 'member'];
        if ( Role::get()->isEmpty() ) {
            foreach ($roles as $role) {
                Role::create([
                    'name' => $role,
                    'label' => ucfirst($role)
                ]);
            }
        }
    }
}
