<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Réinitialise le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Création des permissions
        $permissions = [
            'ajouter document',
            'modifier document',
            'supprimer document',
            'voir tous les documents',
            'voir ses documents',
            'gérer utilisateurs',
            'gérer rôles et permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Création des rôles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $responsable = Role::firstOrCreate(['name' => 'responsable']);
        $employe = Role::firstOrCreate(['name' => 'employe']);

        // Attribution des permissions
        $admin->syncPermissions($permissions);

        $responsable->syncPermissions([
            'ajouter document',
            'modifier document',
            'supprimer document',
            'voir tous les documents',
        ]);

        $employe->syncPermissions([
            'ajouter document',
            'voir ses documents',
        ]);
    }
}
