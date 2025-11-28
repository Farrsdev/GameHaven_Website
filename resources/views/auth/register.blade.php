<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GameHaven</title>
    <style>
        :root {
            --primary-blue: #6a9eff;
            --dark-blue: #4a7de8;
            --light-blue: #f0f5ff;
            --accent-blue: #8ab4ff;
            --text-dark: #333;
            --text-light: #fff;
            --error-color: #ff6b6b;
            --success-color: #51cf66;
            --border-radius: 12px;
            --box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7ff, #e6eeff);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 450px;
            background-color: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }

        .container:hover {
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        }

        .header {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .logo {
            height: 50px;
            margin-right: 10px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .logo-text {
            font-size: 28px;
            font-weight: bold;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header h1 {
            color: white;
            font-size: 24px;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        .form-container {
            padding: 35px;
        }

        .error-container {
            background-color: #fff5f5;
            color: var(--error-color);
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            border-left: 4px solid var(--error-color);
            box-shadow: 0 2px 8px rgba(255, 107, 107, 0.1);
        }

        .error-container ul {
            list-style-type: none;
        }

        .error-container li {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .error-container li::before {
            content: '⚠';
            margin-right: 8px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 15px;
        }

        .form-group input {
            width: 100%;
            padding: 15px 18px;
            border: 2px solid #e6e9f0;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
            background-color: #fafbff;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-blue);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(106, 158, 255, 0.15);
        }

        .form-group input::placeholder {
            color: #a0a4b8;
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(106, 158, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: var(--transition);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(106, 158, 255, 0.4);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:active {
            transform: translateY(0);
        }

        .form-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 15px;
            color: #666;
            padding-top: 20px;
            border-top: 1px solid #e6e9f0;
        }

        .form-footer a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            position: relative;
        }

        .form-footer a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-blue);
            transition: var(--transition);
        }

        .form-footer a:hover::after {
            width: 100%;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 42px;
            background: none;
            border: none;
            color: #a0a4b8;
            cursor: pointer;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .container {
                max-width: 100%;
            }
            
            .form-container {
                padding: 25px;
            }
            
            .header {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-container">
                <img src="/images/gamehavenlogo.png" alt="GameHaven Logo" class="logo">
                <div class="logo-text">GameHaven</div>
            </div>
            <h1>Create Your Account</h1>
        </div>
        
        <div class="form-container">
            @if ($errors->any())
                <div class="error-container">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Choose a username" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">Show</button>
                </div>
                
                <button type="submit" class="btn">Register</button>
            </form>
            
            <div class="form-footer">
                <p>Already have an account? <a href="{{ url('/login') }}">Login here</a></p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleButton = passwordInput.nextElementSibling;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = 'Hide';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = 'Show';
            }
        }
    </script>
</body>
</html>