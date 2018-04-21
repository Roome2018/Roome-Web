<?php

use Illuminate\Database\Seeder;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            
            ['id' => 1, 'title' => 'user_management_access',],
            ['id' => 2, 'title' => 'user_management_create',],
            ['id' => 3, 'title' => 'user_management_edit',],
            ['id' => 4, 'title' => 'user_management_view',],
            ['id' => 5, 'title' => 'user_management_delete',],
            ['id' => 6, 'title' => 'permission_access',],
            ['id' => 7, 'title' => 'permission_create',],
            ['id' => 8, 'title' => 'permission_edit',],
            ['id' => 9, 'title' => 'permission_view',],
            ['id' => 10, 'title' => 'permission_delete',],
            ['id' => 11, 'title' => 'role_access',],
            ['id' => 12, 'title' => 'role_create',],
            ['id' => 13, 'title' => 'role_edit',],
            ['id' => 14, 'title' => 'role_view',],
            ['id' => 15, 'title' => 'role_delete',],
            ['id' => 16, 'title' => 'user_access',],
            ['id' => 17, 'title' => 'user_create',],
            ['id' => 18, 'title' => 'user_edit',],
            ['id' => 19, 'title' => 'user_view',],
            ['id' => 20, 'title' => 'user_delete',],
            ['id' => 21, 'title' => 'room_access',],
            ['id' => 22, 'title' => 'room_create',],
            ['id' => 23, 'title' => 'room_edit',],
            ['id' => 24, 'title' => 'room_view',],
            ['id' => 25, 'title' => 'room_delete',],
            ['id' => 26, 'title' => 'comment_access',],
            ['id' => 27, 'title' => 'comment_create',],
            ['id' => 28, 'title' => 'comment_edit',],
            ['id' => 29, 'title' => 'comment_view',],
            ['id' => 30, 'title' => 'comment_delete',],
            ['id' => 31, 'title' => 'like_access',],
            ['id' => 32, 'title' => 'like_create',],
            ['id' => 33, 'title' => 'like_edit',],
            ['id' => 34, 'title' => 'like_view',],
            ['id' => 35, 'title' => 'like_delete',],
            ['id' => 36, 'title' => 'booking_access',],
            ['id' => 37, 'title' => 'booking_create',],
            ['id' => 38, 'title' => 'booking_edit',],
            ['id' => 39, 'title' => 'booking_view',],
            ['id' => 40, 'title' => 'booking_delete',],

        ];

        foreach ($items as $item) {
            \App\Permission::create($item);
        }
    }
}
