    <header>
        <!-- Responsive navbar-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-5">
                <a class="navbar-brand" href="/home">SQL Learning Hub | </a>
                <span id="profile-name" class="navbar-brand" href="#!"></span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link {{$menu == 'home' ? 'active' :''}}" aria-current="page" href="/home">Home</a></li>
                        <li class="nav-item"><a class="nav-link {{$menu == 'module' ? 'active' :''}}" href="/module">Module</a></li>
                        <li class="nav-item"><a class="nav-link {{$menu == 'practice' ? 'active' :''}}" href="/practice">Practice</a></li>
                        <li class="nav-item"><a class="nav-link {{$menu == 'contest' ? 'active' :''}}" href="/contest">Contest</a></li>
                        @if($user->role == 'Admin')
                            <li class="nav-item"><a class="nav-link {{$menu == 'admin' ? 'active' :''}}" href="/admin/index">Admin</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="/logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
