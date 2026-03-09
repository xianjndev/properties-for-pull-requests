<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_products","view_any_products","create_products","update_products","restore_products","restore_any_products","replicate_products","reorder_products","delete_products","delete_any_products","force_delete_products","force_delete_any_products","view_products::import","view_any_products::import","create_products::import","update_products::import","restore_products::import","restore_any_products::import","replicate_products::import","reorder_products::import","delete_products::import","delete_any_products::import","force_delete_products::import","force_delete_any_products::import","view_projects","view_any_projects","create_projects","update_projects","restore_projects","restore_any_projects","replicate_projects","reorder_projects","delete_projects","delete_any_projects","force_delete_projects","force_delete_any_projects","view_property","view_any_property","create_property","update_property","restore_property","restore_any_property","replicate_property","reorder_property","delete_property","delete_any_property","force_delete_property","force_delete_any_property","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_status","view_any_status","create_status","update_status","restore_status","restore_any_status","replicate_status","reorder_status","delete_status","delete_any_status","force_delete_status","force_delete_any_status","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user"]},{"name":"panel_user","guard_name":"web","permissions":["view_products","view_any_products","create_products","update_products","restore_products","restore_any_products","replicate_products","reorder_products","delete_products","delete_any_products","force_delete_products","force_delete_any_products"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
