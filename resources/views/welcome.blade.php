<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>LifeStore 生活をなんでも記録するアプリ</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased grid place-items-center h-screen">
        <div class="flex flex-col items-center w-fit">
            <h1 class="font-bold text-6xl mb-12">LifeStore</h1>
            <div class="flex flex-col gap-8 w-40">
                <a href="./register" class="button rounded border border-solid py-3 text-center hover:shadow-md">新規登録</a>
                <a href="./login" class="button rounded bg-gray-800 text-white py-3 text-center hover:shadow-md">ログイン</a>
            </div>
        </div>
    </body>
</html>
