<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Todo一覧画面が正常に表示される
     */
    public function test_can_display_todo_list(): void
    {
        $todo = Todo::create([
            'title' => 'テストタスク1',
            'is_completed' => false,
        ]);

        $response = $this->get('/todos');

        $response->assertStatus(200);
        $response->assertSee('テストタスク1');
    }

    /**
     * トップページ (/) からもTodo一覧が表示される
     */
    public function test_home_page_shows_todo_list(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Laravel Todo アプリ');
    }

    /**
     * 新規Todoを正常に作成できる
     */
    public function test_can_create_a_todo(): void
    {
        $response = $this->post('/todos', [
            'title' => '買い物に行く',
        ]);

        $response->assertRedirect('/todos');
        $this->assertDatabaseHas('todos', [
            'title' => '買い物に行く',
            'is_completed' => false,
        ]);
    }

    /**
     * タイトルが空の場合はバリデーションエラーになる
     */
    public function test_cannot_create_a_todo_without_title(): void
    {
        $response = $this->post('/todos', [
            'title' => '',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('todos', 0);
    }

    /**
     * Todoの完了/未完了を切り替えできる
     */
    public function test_can_toggle_todo_completion_status(): void
    {
        $todo = Todo::create([
            'title' => '未完了のタスク',
            'is_completed' => false,
        ]);

        // 完了へトグル
        $response = $this->patch("/todos/{$todo->id}/toggle");
        $response->assertStatus(302);
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'is_completed' => true,
        ]);

        // 再度トグルして未完了へ
        $response = $this->patch("/todos/{$todo->id}/toggle");
        $response->assertStatus(302);
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'is_completed' => false,
        ]);
    }

    /**
     * Todoのタイトルを更新できる
     */
    public function test_can_update_todo_title(): void
    {
        $todo = Todo::create([
            'title' => '変更前のタイトル',
            'is_completed' => false,
        ]);

        $response = $this->put("/todos/{$todo->id}", [
            'title' => '変更後のタイトル',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => '変更後のタイトル',
        ]);
    }

    /**
     * Todoを削除できる
     */
    public function test_can_delete_a_todo(): void
    {
        $todo = Todo::create([
            'title' => '削除対象タスク',
            'is_completed' => false,
        ]);

        $response = $this->delete("/todos/{$todo->id}");

        $response->assertStatus(302);
        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }

    /**
     * フィルター機能（未完了・完了）が正しく動作する
     */
    public function test_can_filter_todos_by_status(): void
    {
        Todo::create(['title' => '未完了タスク', 'is_completed' => false]);
        Todo::create(['title' => '完了済みタスク', 'is_completed' => true]);

        // 未完了のみ取得
        $responseActive = $this->get('/todos?filter=active');
        $responseActive->assertSee('未完了タスク');
        $responseActive->assertDontSee('完了済みタスク');

        // 完了済みのみ取得
        $responseCompleted = $this->get('/todos?filter=completed');
        $responseCompleted->assertSee('完了済みタスク');
        $responseCompleted->assertDontSee('未完了タスク');
    }
}
