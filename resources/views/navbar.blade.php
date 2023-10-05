<nav class="navbar navbar-expand-lg navbar-dark bg-black fixed-top">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto fw-semibold">
                @guest
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register">Register</a>
                </li>
                @endguest
                @auth
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('home/create') ? 'active' : ''}}" aria-current="page" href="/home/create">Upload</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('home') ? 'active' : ''}}" href="/home">Profile</a>
                </li>
                <li><a class="nav-link" href="/logout">Log Out</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>