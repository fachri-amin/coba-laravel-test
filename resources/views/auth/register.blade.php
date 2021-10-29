<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="/register" method="POST">
        @csrf
        <div class="">
            <label for="name">Name</label>
            <input type="text" name="name" id="">
        </div>
        <div class="">
            <label for="email">Email</label>
            <input type="text" name="email" id="">
        </div>
        <div class="">
            <label for="password">Password</label>
            <input type="text" name="password" id="">
        </div>
        <button type="submit">Register</button>
    </form>
</body>
</html>