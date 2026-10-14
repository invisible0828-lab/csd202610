<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

// トップページアクセス時もTodo一覧を表示
Route::get('/', [TodoController::class, 'index'])->name('home');

// Todo関連ルート
Route::prefix('todos')->name('todos.')->group(function () {
    Route::get('/', [TodoController::class, 'index'])->name('index');
    Route::post('/', [TodoController::class, 'store'])->name('store');
    Route::patch('/{todo}/toggle', [TodoController::class, 'toggle'])->name('toggle');
    Route::put('/{todo}', [TodoController::class, 'update'])->name('update');
    Route::delete('/{todo}', [TodoController::class, 'destroy'])->name('destroy');
});

// サンプルページ（既存）
Route::get('/hello', function () {
    return view('hello', [
        'title' => 'Laravel View サンプル',
        'message' => '新しいBladeビューが正常に作成・表示されました！',
        'items' => [
            'Bladeディレクティブ (@foreach)',
            '変数の出力 ({{ $var }})',
            'ルーティング (routes/web.php)',
        ],
    ]);
});
