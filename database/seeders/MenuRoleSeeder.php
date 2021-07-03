<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class MenuRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $super_admin_menu_ids = Menu::pluck('id');
        $super_admin_id = Role::where('name', 'super_admin')->pluck('id')[0];
        foreach ($super_admin_menu_ids as $mid) {
            MenuRole::create([
                'mid' => $mid,
                'rid' => $super_admin_id
            ]);
        }
    }
}
