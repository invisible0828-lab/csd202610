<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todoアプリ - Laravel & MySQL</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Plus Jakarta Sans', 'Noto Sans JP', sans-serif;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen py-10 px-4">
  <div class="max-w-2xl mx-auto">
    <!-- ヘッダー -->
    <header class="mb-8 text-center">
      <div class="inline-flex items-center justify-center p-3 bg-indigo-50 text-indigo-600 rounded-2xl mb-3 shadow-sm border border-indigo-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
      </div>
      <h1 class="text-3xl font-bold tracking-tight text-slate-900">Laravel Todo アプリ</h1>
      <p class="text-sm text-slate-500 mt-1">Docker (PHP 8.4) + MySQL 8.4 で永続化されたタスク管理</p>
    </header>

    <!-- フラッシュメッセージ -->
    @if (session('success'))
      <div class="mb-6 flex items-center p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl shadow-sm text-sm">
        <svg class="w-5 h-5 mr-2 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    <!-- エラーメッセージ -->
    @if ($errors->any())
      <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl shadow-sm text-sm">
        <div class="font-semibold mb-1 flex items-center">
          <svg class="w-5 h-5 mr-1 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
          </svg>
          入力エラーがあります:
        </div>
        <ul class="list-disc list-inside text-xs space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- メインカード -->
    <main class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden mb-6">
      <!-- Todo新規作成フォーム -->
      <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50">
        <form action="{{ route('todos.store') }}" method="POST" class="flex gap-2">
          @csrf
          <input
            type="text"
            name="title"
            value="{{ old('title') }}"
            placeholder="新しいタスクを入力... (例: 本を読む、買い出し)"
            class="flex-1 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-slate-400"
            autofocus
            required
          >
          <button
            type="submit"
            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl transition duration-150 shadow-sm shadow-indigo-200 flex items-center gap-1.5 flex-shrink-0"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            追加
          </button>
        </form>
      </div>

      <!-- 統計 ＆ フィルタータブ -->
      <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-3 bg-white border-b border-slate-100 text-xs text-slate-500 gap-3">
        <!-- フィルターリンク -->
        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl w-full sm:w-auto">
          <a
            href="{{ route('todos.index', ['filter' => 'all']) }}"
            class="flex-1 sm:flex-none text-center px-3 py-1.5 rounded-lg font-medium transition {{ $currentFilter === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}"
          >
            すべて ({{ $counts['all'] }})
          </a>
          <a
            href="{{ route('todos.index', ['filter' => 'active']) }}"
            class="flex-1 sm:flex-none text-center px-3 py-1.5 rounded-lg font-medium transition {{ $currentFilter === 'active' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}"
          >
            未完了 ({{ $counts['active'] }})
          </a>
          <a
            href="{{ route('todos.index', ['filter' => 'completed']) }}"
            class="flex-1 sm:flex-none text-center px-3 py-1.5 rounded-lg font-medium transition {{ $currentFilter === 'completed' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}"
          >
            完了済み ({{ $counts['completed'] }})
          </a>
        </div>

        <!-- 完了率プログレスバー -->
        @php
          $percent = $counts['all'] > 0 ? round(($counts['completed'] / $counts['all']) * 100) : 0;
        @endphp
        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
          <span>進捗率: {{ $percent }}%</span>
          <div class="w-20 bg-slate-200 h-2 rounded-full overflow-hidden">
            <div class="bg-indigo-600 h-full transition-all duration-300" style="width: {{ $percent }}%;"></div>
          </div>
        </div>
      </div>

      <!-- Todo一覧 -->
      <div class="divide-y divide-slate-100">
        @forelse ($todos as $todo)
          <div class="p-4 sm:p-5 flex items-center justify-between gap-3 hover:bg-slate-50/70 transition group" id="todo-row-{{ $todo->id }}">
            <!-- 完了トグルフォーム -->
            <div class="flex items-center gap-3 flex-1 min-w-0">
              <form action="{{ route('todos.toggle', $todo) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button
                  type="submit"
                  class="w-5 h-5 rounded-md border flex items-center justify-center transition-all cursor-pointer {{ $todo->is_completed ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 bg-white hover:border-slate-400' }}"
                  title="{{ $todo->is_completed ? '未完了に戻す' : '完了にする' }}"
                >
                  @if ($todo->is_completed)
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  @endif
                </button>
              </form>

              <!-- タイトル表示 -->
              <div class="flex-1 min-w-0" id="todo-text-{{ $todo->id }}">
                <p class="text-sm font-medium truncate {{ $todo->is_completed ? 'line-through text-slate-400' : 'text-slate-800' }}">
                  {{ $todo->title }}
                </p>
                <span class="text-[11px] text-slate-400 block mt-0.5">
                  {{ $todo->created_at->format('Y/m/d H:i') }} 作成
                </span>
              </div>

              <!-- 編集用インラインフォーム (初期は非表示) -->
              <form
                action="{{ route('todos.update', $todo) }}"
                method="POST"
                id="todo-edit-form-{{ $todo->id }}"
                class="hidden flex-1 flex gap-2 items-center"
              >
                @csrf
                @method('PUT')
                <input
                  type="text"
                  name="title"
                  value="{{ $todo->title }}"
                  required
                  class="flex-1 px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                <button
                  type="submit"
                  class="px-2.5 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700"
                >
                  保存
                </button>
                <button
                  type="button"
                  onclick="toggleEdit({{ $todo->id }})"
                  class="px-2.5 py-1.5 bg-slate-200 text-slate-700 text-xs font-medium rounded-lg hover:bg-slate-300"
                >
                  取消
                </button>
              </form>
            </div>

            <!-- アクションボタン -->
            <div class="flex items-center gap-1 opacity-80 group-hover:opacity-100 transition flex-shrink-0" id="todo-actions-{{ $todo->id }}">
              <!-- 編集ボタン -->
              <button
                type="button"
                onclick="toggleEdit({{ $todo->id }})"
                class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition"
                title="編集"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
              </button>

              <!-- 削除ボタン -->
              <form
                action="{{ route('todos.destroy', $todo) }}"
                method="POST"
                onsubmit="return confirm('このTodo「{{ $todo->title }}」を削除しますか？');"
                class="inline"
              >
                @csrf
                @method('DELETE')
                <button
                  type="submit"
                  class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition"
                  title="削除"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </form>
            </div>
          </div>
        @empty
          <div class="py-12 text-center text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-sm font-medium">登録されているTodoはありません</p>
            <p class="text-xs text-slate-400 mt-1">上の入力欄から新しいタスクを追加してください</p>
          </div>
        @endforelse
      </div>
    </main>

    <!-- フッター -->
    <footer class="text-center text-xs text-slate-400">
      <p>Powered by Laravel 13 &bull; PHP 8.4 &bull; MySQL 8.4 &bull; Docker</p>
    </footer>
  </div>

  <script>
    function toggleEdit(id) {
      const textEl = document.getElementById('todo-text-' + id);
      const formEl = document.getElementById('todo-edit-form-' + id);
      const actionsEl = document.getElementById('todo-actions-' + id);

      if (formEl.classList.contains('hidden')) {
        formEl.classList.remove('hidden');
        textEl.classList.add('hidden');
        actionsEl.classList.add('hidden');
        const input = formEl.querySelector('input[name="title"]');
        if (input) {
          input.focus();
          input.select();
        }
      } else {
        formEl.classList.add('hidden');
        textEl.classList.remove('hidden');
        actionsEl.classList.remove('hidden');
      }
    }
  </script>
</body>
</html>
