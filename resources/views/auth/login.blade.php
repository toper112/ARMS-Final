<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('images/salusBG.png') no-repeat center center fixed;
            /* Local background image */
            background-size: cover;
            /* Cover the entire area */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Outer container */
        .outer-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            padding: 20px;
            /* Optional padding around the inner container */
        }

        /* Inner login container wrapper */
        .login-container-wrapper {
            display: flex;
            align-items: center;
            background: linear-gradient(to right, rgba(255, 255, 255, 0.7) 50%, rgba(168, 241, 166, 0.7) 50%);
            /* Split background */
            padding: 30px;
            border: 2px solid #000000;
            border-radius: 15px;
            box-shadow: 0px 0px 25px rgba(255, 255, 255, 0.3);
            width: 90%;
            /* Full width for large screens, adjust as needed */
            max-width: 1000px;
            /* Limit maximum width */
        }

        .logo-container {
            margin-right: 30px;
            background-color: #99c79100;
            width: 100%;
            height: 100%;
        }

        .logo-container img {
            width: 250px;
            /* Width of the logo */
            height: 250px;
            /* Height of the logo */
            border-radius: 5px;
            /* Rounded corners for the logo */
        }

        .form-container {
            width: 100%;
            /* Full width for the form */
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 90%;
            /* Full width for input fields */
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #000000;
            border-radius: 5px;
            color: black;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #000000;
            color: rgb(255, 255, 255);
            font-size: 16px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #727e67;
            /* Darker green on hover */
        }

        .form-container a {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #000000;
            font-size: 14px;
        }

        .form-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="outer-container"> <!-- New outer container -->
        <div class="login-container-wrapper">
            <div class="logo-container">
                <img src="images/white.png" alt="Salus Institute of Technology Logo"> <!-- Local logo image -->
            </div>
            <div class="form-container">
                <form method="POST" action="/login"> <!-- Adjust the action URL as needed -->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"> <!-- CSRF Token -->

                    <!-- Email Address -->
                    <div>
                        <label for="email" style="color: black; font-weight: bold;">Email</label>
                        <input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus
                            autocomplete="username">
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <label for="password" style="color: black; font-weight: bold;">Password</label>
                        <div class="relative">
                            <input id="password" class="block mt-1 w-full border p-2" type="password" name="password"
                                required autocomplete="current-password">
                            {{-- <button type="button" id="togglePassword" class="fas fa-eye toggle-password"
                                style="position: absolute; top: 49%; right: 500px; background: none; border: none; color: black; font-weight: bold; cursor: pointer; margin-top:3px; width: 70px">
                                Show
                            </button> --}}
                        </div>
                    </div>


                    <div class="flex items-center justify-end mt-4">
                        <button type="submit">
                            Log in
                        </button>
                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="/password/request">
                            Forgot your password?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

{{-- <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.textContent = type === 'password' ? 'Show' : 'Hide';
    });
</script> --}}
