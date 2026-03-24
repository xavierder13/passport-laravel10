<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $user = User::firstOrCreate([
        	'name' => 'Administrator', 
            'email' => 'admin@webportal.ac',
        	'password' => bcrypt('123$qweR'),
            'active' => true,
        ]);

        $role = Role::firstOrCreate(['name' => 'Administrator', 'guard_name' => 'api']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
