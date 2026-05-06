<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\GlbCompany;
use App\Models\GlbBranch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Crypt;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = \App\Models\User::firstOrCreate([
          'name' => \Crypt::encryptString('Administrator'),
          'username' => 'admin',
          'email' => 'admin@admin.com',
        ],[
          'email_verified_at' => now(),
          'password' => \Hash::make('rahasia'),
          'remember_token' => \Str::random(10),
        ]);

        $role = Role::firstOrCreate(['name' => 'super-admin']);
        $user->assignRole($role);

        $menu = Menu::firstOrCreate([
                      'name' => 'admin',
                    ]);

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Dashboard',
            'url'     => '/administrator',
            'route'   => null,
            'controller' => 'AdminDashboard'
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'fas fa-tachometer-alt',
                'parent_id'  => null,
                'order'      => 1,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Users',
            'url'     => '/administrator/users',
            'route'   => null,
            'controller' => 'AdminUsers'
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'fas fa-users',
                'parent_id'  => null,
                'order'      => 2,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Roles',
            'url'     => '/administrator/roles',
            'route'   => null,
            'controller' => 'AdminRoles'
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'fas fa-lock',
                'parent_id'  => null,
                'order'      => 3,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Permissions',
            'url'     => '/administrator/permissions',
            'route'   => null,
            'controller' => 'AdminPermissions'
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'fas fa-user-lock',
                'parent_id'  => null,
                'order'      => 4,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Menus',
            'url'     => '/administrator/menus',
            'route'   => null,
            'controller' => 'AdminMenus'
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'fas fa-list',
                'parent_id'  => null,
                'order'      => 5,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Companies',
            'url'     => '/administrator/companies',
            'route'   => null,
            'controller' => 'AdminCompanies'
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'fas fa-building',
                'parent_id'  => null,
                'order'      => 6,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Running Codes',
            'url'     => '/administrator/running-codes',
            'route'   => null,
            'controller' => 'AdminRunningCodes'
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'fas fa-list',
                'parent_id'  => null,
                'order'      => 7,
            ])->save();
        }

        $menu = Menu::firstOrCreate([
                      'name' => 'main_menu',
                    ]);

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Dashboard',
            'url'     => '/dashboard',
            'route'   => null,
            'controller' => 'Dashboard'
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'var_name' => 'dashboard',
                'permission' => 'open_dashboard',
                'target'     => '_self',
                'icon_class' => null,
                'parent_id'  => null,
                'order'      => 1,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Setup',
            'url'     => '/setup',
            'route'   => null,
            'controller' => null
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'var_name' => null,
                'permission' => 'open_setup',
                'target'     => '_self',
                'icon_class' => null,
                'parent_id'  => null,
                'order'      => 2,
            ])->save();
        }

        $menu = Menu::firstOrCreate([
                      'name' => 'sidebar_setup',
                    ]);

        $menuParent = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Locations',
            'url'     => '#',
            'route'   => null,
            'controller' => null
        ]);
        if (!$menuParent->exists) {
            $menuParent->fill([
                'var_name' => null,
                'permission' => null,
                'target'     => '_self',
                'icon_class' => null,
                'parent_id'  => null,
                'order'      => 1,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Countries',
            'url'     => '/setup/countries',
            'route'   => 'setup.countries',
            'controller' => 'SetupCountries'
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'var_name' => 'country',
                'permission' => 'open_setup_countries',
                'target'     => '_self',
                'icon_class' => 'fas fa-flag',
                'parent_id'  => $menuParent->id,
                'order'      => 1,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Unloco',
            'url'     => '/setup/unloco',
            'route'   => 'setup.unloco',
            'controller' => 'SetupUnloco'
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'var_name' => 'unloco',
                'permission' => 'open_setup_unloco',
                'target'     => '_self',
                'icon_class' => 'fas fa-globe',
                'parent_id'  => $menuParent->id,
                'order'      => 2,
            ])->save();
        }

        $menuParent = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Accounts',
            'url'     => '#',
            'route'   => null,
            'controller' => null
        ]);
        if (!$menuParent->exists) {
            $menuParent->fill([
                'var_name' => null,
                'permission' => null,
                'target'     => '_self',
                'icon_class' => null,
                'parent_id'  => null,
                'order'      => 2,
            ])->save();
        }
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Currency',
            'url'     => '/setup/currency',
            'route'   => 'setup.currency',
            'controller' => 'SetupCurrency'
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'var_name' => 'currency',
                'permission' => 'open_setup_currency',
                'target'     => '_self',
                'icon_class' => 'fas fa-money-bill-wave',
                'parent_id'  => $menuParent->id,
                'order'      => 1,
            ])->save();
        }

        $company = GlbCompany::firstOrNew([
            'GC_Code' => 'ONE',
            'GC_Name' => 'DEFAULT COMPANY',
            'GC_IsActive' => true,
        ]);
        if (!$company->exists) {
            $company->fill([
                'GC_Address1' => 'Jakarta',
                'GC_City' => 'Jakarta',
                'GC_Phone'  => '021-00000000',
                'GC_PostCode'      => '000000',
                'GC_State'      => 'DKI Jakarta',
            ])->save();
        }

        $branch = GlbBranch::firstOrCreate([
            'company_id' => $company->id,
            'CB_Code' => 'ONE'
            ],[
              'CB_IsActive' => true,
              'CB_FullName' => 'DEFAULT BRANCH'
            ]);

        $user->branches()->attach($branch->id);
    }
}
