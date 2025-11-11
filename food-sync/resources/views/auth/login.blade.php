<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Sync - Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_box,explore,home,settings" />

    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="container-fluid">
        <main class="content d-flex flex-column align-items-center justify-content-center overflow-x-hidden overflow-y-hidden m-0 p-2">
            <span class="shadow-md fw-medium fs-4 text-center mb-4 px-3 py-2 ">Login to your Account</span>
            <div class="shadow-md p-4" style="width: 350px;">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="text" class="form-control" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <x-input-error :messages="$errors->get('email')" />
                    <x-input-error :messages="$errors->get('password')" />

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <div class="text-center mt-2">
                    <span>Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register here</a></span>
                </div>
            </div>
        </main>
    </div>
</body>

</html>