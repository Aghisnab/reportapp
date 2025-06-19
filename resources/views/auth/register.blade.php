<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register - Report Event App</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 180px; /* Sesuaikan ukuran logo */
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo"> <!-- Ganti dengan path logo Anda -->
        </div>

        <h5 class="text-center font-weight-light my-4">Create Account</h5>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('register') }}" method="POST">  <!-- Form method POST -->
            @csrf  <!-- Token keamanan Laravel -->

            <!-- Nama Lengkap -->
            <div class="form-floating mb-3">
                <input class="form-control" id="name" type="text" name="name" placeholder="Enter your full name" value="{{ old('name') }}" required />
                <label for="name">Full Name</label>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-floating mb-3">
                <input class="form-control" id="email" type="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" required />
                <label for="email">Email address</label>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3 mb-md-0">
                        <input class="form-control" id="password" type="password" name="password" placeholder="Create a password" required />
                        <label for="password">Password</label>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div class="col-md-6">
                    <div class="form-floating mb-3 mb-md-0">
                        <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm password" required />
                        <label for="password_confirmation">Confirm Password</label>
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="mt-4 mb-0">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                </div>
            </div>
        </form>
        <div class="text-center mt-3">
            <a href="{{ url('login') }}">Have an account? Go to login</a>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-7X1E1H7FQn0sb9q3HqgJX3kM2U7n1dWc1kV8U6s9o0M5U1h4Hn5F+Wk4z8bYwH9K" crossorigin="anonymous"></script>
</body>
</html>
