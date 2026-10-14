<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TodoController extends Controller
{
    /**
     * Todo一覧の表示
     */
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all');

        $query = Todo::query();

        if ($filter === 'active') {
            $query->where('is_completed', false);
        } elseif ($filter === 'completed') {
            $query->where('is_completed', true);
        }

        $todos = $query->orderBy('is_completed', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $counts = [
            'all' => Todo::count(),
            'active' => Todo::where('is_completed', false)->count(),
            'completed' => Todo::where('is_completed', true)->count(),
        ];

        return view('todos.index', [
            'todos' => $todos,
            'counts' => $counts,
            'currentFilter' => $filter,
        ]);
    }

    /**
     * 新規Todoの作成
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ], [
            'title.required' => 'Todoのタイトルを入力してください。',
            'title.max' => 'Todoのタイトルは255文字以内で入力してください。',
        ]);

        Todo::create([
            'title' => $validated['title'],
            'is_completed' => false,
        ]);

        return redirect()->route('todos.index')->with('success', 'Todoを追加しました！');
    }

    /**
     * Todoの完了/未完了切り替え
     */
    public function toggle(Todo $todo): RedirectResponse
    {
        $todo->update([
            'is_completed' => !$todo->is_completed,
        ]);

        $statusText = $todo->is_completed ? '完了' : '未完了';
        return back()->with('success', "Todoを「{$statusText}」に変更しました！");
    }

    /**
     * Todoタイトルの更新
     */
    public function update(Request $request, Todo $todo): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ], [
            'title.required' => 'Todoのタイトルを入力してください。',
            'title.max' => 'Todoのタイトルは255文字以内で入力してください。',
        ]);

        $todo->update([
            'title' => $validated['title'],
        ]);

        return back()->with('success', 'Todoを更新しました！');
    }

    /**
     * Todoの削除
     */
    public function destroy(Todo $todo): RedirectResponse
    {
        $todo->delete();

        return back()->with('success', 'Todoを削除しました！');
    }
}
