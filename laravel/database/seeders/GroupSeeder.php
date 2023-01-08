<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::create([
            'name' => 'Administrators',
            'is_removable' => 'N',
            'is_member_restricted' => 'N',
            'is_admin' => 'Y',
            'is_admin_restricted' => 'N',
        ]);

        Group::create([
            'name' => 'Members',
            'is_removable' => 'N',
            'is_member_restricted' => 'N',
            'is_admin' => 'N',
            'is_admin_restricted' => 'Y',
        ]);
    }
}
