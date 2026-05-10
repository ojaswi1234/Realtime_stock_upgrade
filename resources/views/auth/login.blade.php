<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login - Stock System</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #fff; padding: 30px; border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .login-box h2 { text-align: center; margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #666; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { width: 100%; padding: 10px; background: #3498db; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #2980b9; }
        ul { padding-left: 20px; color: red; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Stock System Login</h2>
        
        @if ($errors->any())
            <div style="background: #f8d7da; padding: 10px; color: #721c24; margin-bottom: 15px; border-radius: 4px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required autofocus value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="margin: 0; display: inline;">Remember me</label>
            </div>
            <button type="submit" class="btn">Log In</button>
        </form>
        <p style="text-align: center; margin-top: 15px; font-size: 14px;">
            Don't have an account? <a href="{{ route('register') }}">Register</a>
        </p>
        <p style="text-align: center; margin-top: 15px; font-size: 14px;">Use <b>admin@gmail.com</b> or <b>manager@gmail.com</b><br>Password: <b>password</b></p>
    </div>
</body>
</html>
