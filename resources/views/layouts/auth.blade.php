<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Login')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100 bg-[url('{{ asset('image/auth/Background1.png') }}')] bg-cover bg-center">

    @yield('content')

</body>
</html>


