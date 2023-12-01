<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.scss')
    <title>HiroApp</title>
</head>

<body>
    <div id="root"></div>
    @vite('resources/js/HiroApp.jsx')
</body>

</html>
