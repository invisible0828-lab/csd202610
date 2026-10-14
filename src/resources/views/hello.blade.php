<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f3f4f6;
            color: #374151;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            max-width: 28rem;
            width: 100%;
        }
        h1 {
            color: #ef4444;
            font-size: 1.5rem;
            margin-top: 0;
            margin-bottom: 1rem;
        }
        p {
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        ul {
            padding-left: 1.25rem;
            margin-bottom: 1.5rem;
        }
        li {
            margin-bottom: 0.25rem;
        }
        .footer {
            font-size: 0.875rem;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>

        <h3>機能・要素一覧:</h3>
        <ul>
            @foreach ($items as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>

        <div class="footer">
            現在日時: {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>
</body>
</html>
