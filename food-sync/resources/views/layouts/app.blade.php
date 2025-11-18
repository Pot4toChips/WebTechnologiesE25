<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Food Sync')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_box,explore,home,settings" />

    <link rel="stylesheet" href="css/styles.css">

    @yield('css')
</head>

<body>
    <script src="js/scripts.js" type="module"></script>
    <div class="container-fluid">
        <div class="row">
            @include('partials.navigation')

            <main class="content d-flex flex-column align-items-center justify-content-start overflow-x-hidden overflow-y-hidden col-10 m-0 p-0">
                <div class="d-flex flex-column align-items-center justify-content-start overflow-auto w-100 px-4 pt-4">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <div id="screen-blocker" class="position-fixed justify-content-center align-items-center top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-none" style="z-index: 1000; display: none;">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Updating...</span>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
        <div id="toast-message" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div id="toast-text" class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    @yield('js')

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>