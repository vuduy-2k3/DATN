<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            [
                'email' => 'quanly@gmail.com',
            'password' => bcrypt('duy020320'),
            ],
            // Bạn có thể thêm nhiều admin mặc định khác ở đây nếu muốn
        ]);
    }
}
