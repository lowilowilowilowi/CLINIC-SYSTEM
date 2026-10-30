<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Clinic System</title>
    <style>
        :root {
            --clinic-blue: #4a4c8c;
            --clinic-border: #4a4c8c;
            --clinic-page: #ededed;
            --clinic-white: #ffffff;
            --clinic-error: #9f2424;
        }

        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            padding: 24px;
            display: grid;
            place-items: center;
            background: var(--clinic-page);
            color: var(--clinic-blue);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-window {
            width: min(100%, 900px);
            min-height: 590px;
            display: flex;
            flex-direction: column;
            border: 3px solid var(--clinic-border);
            background: var(--clinic-white);
        }

        .window-bar {
            min-height: 58px;
            display: flex;
            align-items: center;
            padding: 10px 16px;
            background: var(--clinic-blue);
            color: var(--clinic-white);
            font-size: 18px;
            font-weight: 700;
        }

        .login-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 32px;
        }

        h1 {
            margin: 0 0 52px;
            text-align: center;
            font-size: 32px;
            line-height: 1.2;
        }

        .login-form { width: min(100%, 570px); margin: 0 auto; }
        .field-row {
            display: grid;
            grid-template-columns: 150px minmax(0, 1fr);
            align-items: center;
            gap: 18px;
            margin-bottom: 28px;
        }

        label { text-align: right; font-size: 18px; font-weight: 600; }
        input {
            width: 100%;
            min-width: 0;
            height: 46px;
            padding: 8px 12px;
            border: 3px solid var(--clinic-border);
            border-radius: 0;
            background: var(--clinic-white);
            color: #242424;
            font: inherit;
        }

        input:focus { outline: 3px solid rgba(74, 76, 140, 0.2); outline-offset: 2px; }
        .error-message { margin: -18px 0 22px 168px; color: var(--clinic-error); font-size: 14px; }

        .login-button {
            display: block;
            min-width: 148px;
            min-height: 52px;
            margin: 42px auto 0;
            padding: 10px 24px;
            border: 0;
            border-radius: 0;
            background: var(--clinic-blue);
            color: var(--clinic-white);
            cursor: pointer;
            font: inherit;
            font-size: 18px;
            font-weight: 700;
        }

        .login-button:hover { background: #393b72; }
        .login-button:focus-visible { outline: 3px solid #22234c; outline-offset: 3px; }

        @media (max-width: 600px) {
            body { padding: 12px; }
            .login-window { min-height: min(590px, calc(100vh - 24px)); }
            .login-content { padding: 40px 20px; }
            h1 { margin-bottom: 42px; font-size: 28px; }
            .field-row { grid-template-columns: 1fr; gap: 8px; margin-bottom: 22px; }
            label { text-align: left; }
            .error-message { margin: -12px 0 20px; }
            .login-button { margin-top: 32px; }
        }
    </style>
</head>
<body>
    <main class="login-window">
        <div class="window-bar">CLINIC SYSTEM</div>
        <section class="login-content" aria-labelledby="login-title">
            <h1 id="login-title">CLINIC SYSTEM</h1>

            <form class="login-form" method="POST" action="{{ route('login.attempt') }}">
                @csrf

                <div class="field-row">
                    <label for="username">Username:</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" autocomplete="username" required autofocus>
                </div>

                <div class="field-row">
                    <label for="password">Password:</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required>
                </div>

                @error('username')
                    <p class="error-message" role="alert">{{ $message }}</p>
                @enderror

                <button class="login-button" type="submit">LOGIN</button>
            </form>
        </section>
    </main>
</body>
</html>