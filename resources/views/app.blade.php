<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>imgFavtter</title>
    <link rel="stylesheet" href="css/app.css">
    <script>
        window.laravel = {};
        window.laravel.csrfToken = "{{ csrf_token() }}";
    </script>
</head>
<body>
    <div id="app">
    </div>
</body>
<script src="js/app.js"></script>
</html>