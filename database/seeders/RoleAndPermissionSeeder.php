<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $secretaryRole = Role::create(['name' => 'secretary']);

        // Create permissions for different modules
        $permissions = [
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Course permissions
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',
            
            // Student permissions
            'view students',
            'create students',
            'edit students',
            'delete students',
            
            // Enrollment permissions
            'view enrollments',
            'create enrollments',
            'edit enrollments',
            'delete enrollments',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign all permissions to admin role
        $adminRole->givePermissionTo(Permission::all());

        // Assign limited permissions to secretary role
        $secretaryRole->givePermissionTo([
            'view users',
            'view courses',
            'create courses',
            'edit courses',
            'view students',
            'create students',
            'edit students',
            'view enrollments',
            'create enrollments',
            'edit enrollments',
        ]);

        // Assign admin role to existing admin user
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }
    }
}
