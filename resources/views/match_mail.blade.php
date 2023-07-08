<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('app.css') }}">
</head>
<body>
    <h1 class="text-xl text-cyan-500 mb-4">Hello {{$receiver}}, {{$sender}} has invited you to join a game as {{$role}}.</h1>
    <p>Join in the game by clicking on the following link</p>
    <a href="http://127.0.0.1:3000/play/game/{{$token}}" class="text-blue-600 underline">http://127.0.0.1:3000/play/game/{{$token}}</a>

    <footer class="flex justify-center h-24 w-full bg-neutral-600 items-center mt-10">
        <div class="text-white">&copy;2023 Poynt Sports</div>
    </footer>
</body>
</html>