<?php

namespace Database\Seeders;

use App\Models\MainMenu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use App\Models\SubMenu;
use App\Utils\Util;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        Artisan::call('cache:clear');

        $settings = [
            ['key' => 'app_name', 'value' => config('app.name')],
            ['key' => 'app_logo', 'value' => 'assets/img/logo.png'],
            ['key' => 'app_dark_logo', 'value' => 'assets/img/dark-logo.png'],
            ['key' => 'favicon_logo', 'value' => 'assets/img/favicon.png'],
            ['key' => 'default_language', 'value' => 'en'],
            ['key' => 'currency', 'value' => 'USD'],
            ['key' => 'currency_symbol', 'value' => '$'],
            ['key' => 'date_format', 'value' => 'M j, Y'],
            ['key' => 'time_format', 'value' => 'g:i A'],
            ['key' => 'color', 'value' => 'theme-2'],
            ['key' => 'storage_type', 'value' => 'local'],
            ['key' => 'dark_mode', 'value' => 'off'],
            ['key' => 'transparent_layout', 'value' => '1'],
            ['key' => 'landing_page_status', 'value' => '1']
        ];
        //Check user is exist or not
        $admin_user = User::where('role', 'admin')->first();
        $admin_role = Role::firstOrCreate(['name' => 'Admin']);
        if (!$admin_user) {
            $admin_user = User::create([
                'first_name'              => 'Admin',
                'last_name'               => 'User',
                'email'             => 'admin@gmail.com',
                'role'              => 'admin',
                'password'          => Hash::make('123456'),
                'email_verified_at' => now(),
            ]);
        }
        Storage::copy('assets/img/logo.png', 'public/logo/app-light-logo.png');
        Storage::copy('assets/img/favicon.ico', 'public/logo/app-favicon-logo.ico');
        Storage::copy('assets/img/app-dark-logo.png', 'public/logo/app-dark-logo.png');
        foreach ($settings as $setting) {
            $setting['created_by'] = $admin_user->id;
            $setting['created_by_ip'] = request()->ip();
            Setting::firstOrCreate($setting);
        }

        $menu_lists = Util::getMenuListWithRoute();
        foreach ($menu_lists as $count => $menu) {
            //Check if menu already exists
            $main_menu = MainMenu::where('menu_name', $menu['menu_name'])->first();
            if (!$main_menu) {
                $store_main_menu = new MainMenu();
                $store_main_menu->language_key = $menu['language_key'];
                $store_main_menu->menu_name = $menu['menu_name'];
                $store_main_menu->menu_icon = $menu['menu_icon'];
                $store_main_menu->route_name = $menu['route_name'];
                $store_main_menu->table_name = $menu['table_name'];
                $store_main_menu->permissions = json_encode($menu['permissions']);
                $store_main_menu->order = $count + 1;
                $store_main_menu->status = $menu['status'];
                $store_main_menu->created_by = $menu['created_by'];
                $store_main_menu->created_by_ip = request()->ip();
                $store_main_menu->save();
            } else {
                $store_main_menu = $main_menu;
            }
            //Create Permission
            if (isset($menu['permissions']['permission'])) {
                Permission::firstOrCreate(['name' =>  $menu['permissions']['permission']]);
            } else {
                foreach ($menu['permissions'] as $k => $v) {
                    Permission::firstOrCreate(['name' => $v['permission']]);
                }
            }
            foreach ($menu['sub_menus'] as $cnt => $sub_menu) {
                //Check if sub menu already exists
                $sub_menu_store = SubMenu::where('menu_name', $sub_menu['menu_name'])->first();
                $sub_menu_cnt = 0;
                $get_last_order = SubMenu::orderBy('order', 'desc')->first();
                if ($get_last_order) {
                    $sub_menu_cnt = $get_last_order->order;
                }
                if (!$sub_menu_store) {
                    $get_last_order = SubMenu::orderBy('order', 'desc')->first();
                    $sub_menu_store = new SubMenu();
                    $sub_menu_store->language_key = $sub_menu['language_key'];
                    $sub_menu_store->main_menu_id = $store_main_menu->id;
                    $sub_menu_store->menu_name = $sub_menu['menu_name'];
                    $sub_menu_store->menu_icon = $sub_menu['menu_icon'];
                    $sub_menu_store->route_name = $sub_menu['route_name'];
                    $sub_menu_store->table_name = $sub_menu['table_name'];
                    $sub_menu_store->permissions = json_encode($sub_menu['permissions']);
                    $sub_menu_store->order = $sub_menu_cnt + 1;
                    $sub_menu_store->status = $sub_menu['status'];
                    $sub_menu_store->created_by = $sub_menu['created_by'];
                    $sub_menu_store->created_by_ip = request()->ip();
                    $sub_menu_store->save();
                } else {
                    $sub_menu_store->update([
                        'language_key' => $sub_menu['language_key'],
                        'main_menu_id' => $store_main_menu->id,
                        'menu_name' => $sub_menu['menu_name'],
                        'menu_icon' => $sub_menu['menu_icon'],
                        'route_name' => $sub_menu['route_name'],
                        'table_name' => $sub_menu['table_name'],
                        'permissions' => json_encode($sub_menu['permissions']),
                        'order' => $sub_menu_cnt + 1,
                        'status' => $sub_menu['status'],
                        "updated_by" => $sub_menu['created_by'],
                        "updated_by_ip" => request()->ip(),
                    ]);
                }
                //Create Permission
                foreach ($sub_menu['permissions'] as $k => $v) {
                    Permission::firstOrCreate(['name' => $v['permission']]);
                }
            }
        }
        $admin_role->syncPermissions(Permission::all());
        $admin_user->assignRole('Admin');
    }
}
