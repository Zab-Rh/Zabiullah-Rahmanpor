<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - IT Student Portal</title>

</head>

<body>

    <h1>Login</h1>

    @if ($errors->any())

    <ul>

        @foreach ($errors->all() as $error)

        <li>{{ $error }}</li>

        @endforeach

    </ul>

    @endif

    @if (session('success'))

    <p>{{ session('success') }}</p>

    @endif

    <form method="POST" action="{{ route('login.store') }}">

        @csrf

        <label for="email">Email</label>

        <input id="email" name="email" type="email"

            value="{{ old('email') }}" required>

        <br><br>

        <label for="password">Password</label>

        <input id="password" name="password" type="password"

            required>

        <br><br>

        <button type="submit">Login</button>

    </form>

    <a href="{{ route('register') }}">Create an account</a>

</body>

</html>