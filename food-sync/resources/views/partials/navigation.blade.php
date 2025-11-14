<nav class="sidebar d-flex flex-column bg-light col-2 vh-100 p-4">
    <header class="d-flex flex-column align-items-start justify-content-start flex-grow-1 w-100">
        <h4 class="text-center fw-bolder mb-3 w-100">Food Sync</h4>
        <ul class="nav flex-column w-100">
            <li class="nav-item"><a class="nav-link active" href="index.html">
                    <span class="material-symbols-rounded nav-link-icon">home</span>Home</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="explore.html">
                    <span class="material-symbols-rounded nav-link-icon">explore</span>Explore</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="profile.html">
                    <span class="material-symbols-rounded nav-link-icon">account_box</span>Profile</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="settings.html">
                    <span class="material-symbols-rounded nav-link-icon">settings</span>Settings</a>
            </li>
        </ul>
    </header>

    <footer class="d-flex flex-column align-items-start justify-content-end flex-grow-1 w-100">
        <ul class="nav flex-column w-100">
            <li class="nav-item">
                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a id="logout-form-submit" class="nav-link text-center" href="logout">Sign Out</a>
                </form>
            </li>
        </ul>
    </footer>
</nav>

<script>
    const logoutForm = document.getElementById('logout-form');
    const logoutFormSubmit = document.getElementById("logout-form-submit");

    logoutFormSubmit.addEventListener('click', (event) => {
        event.preventDefault();
        logoutForm.submit();
    });
</script>