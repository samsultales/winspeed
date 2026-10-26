<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionTableSeeder::class);

        $user = User::firstOrCreate(
            ['email' => 'kasino@email.com'],
            [
                'name' => 'kasino', 
                'username' => 'kasino',
                'password' => bcrypt('123456'),
                'is_active' => true,
            ]
        );
        
        // $role = Role::create(['name' => 'Admin']);
        $role = Role::firstOrCreate(['name' => 'Administrator', 'guard_name' => 'web']);

        $permissions = Permission::pluck('id','id')->all();
       
        $role->syncPermissions($permissions);
         
        $user->assignRole([$role->id]);
    }
}
