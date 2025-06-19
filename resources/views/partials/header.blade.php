<header class="header">
    <div class="header-inner">
        <div class="container">
            <div class="inner d-flex align-items-center justify-content-between">
                <div class="logo">
                    <a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo.png') }}" alt="Logo"></a>
                </div>
                <div class="main-menu">
                    <nav class="navigation">
                        <ul class="nav menu d-flex align-items-center">
                            <li class="active"><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
