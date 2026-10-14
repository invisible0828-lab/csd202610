<?php

namespace Database\Seeders;

use App\Models\Todo;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Todo::create([
            'title' => 'LaravelとDockerのセットアップを完了する',
            'is_completed' => true,
        ]);

        Todo::create([
            'title' => 'MySQLにTodoテーブルを作成する',
            'is_completed' => true,
        ]);

        Todo::create([
            'title' => '新しいTodoを追加してみる',
            'is_completed' => false,
        ]);

        Todo::create([
            'title' => 'タスクの完了・未完了を切り替えてみる',
            'is_completed' => false,
        ]);
    }
}
