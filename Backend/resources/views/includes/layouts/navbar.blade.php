<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <!-- Logo e collegamento alla homepage -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <div class="logo_laravel">
                <img src="{{ Vite::asset('resources/images/logo.png') }}" class="img-logo" alt="logo-travel-app">
            </div>
            {{-- Il nome dell'applicazione, attualmente non visualizzato --}}
        </a>

        <!-- Bottone per il menu a discesa su schermi piccoli -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu di navigazione per schermi più grandi -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Parte sinistra della barra di navigazione -->
            <ul class="ms-4 navbar-nav me-auto d-flex gap-2">
                <li class="nav-item">
                    <!-- Collegamento alla homepage, evidenziato se la rotta corrente è 'guest.home' -->
                    <a class="nav-link @if(Route::is('guest.home')) active @endif" href="{{url('/') }}">{{ __('Home') }}</a>
                </li>
                @auth
                <li class="nav-item">
                    <!-- Collegamento al dashboard admin, evidenziato se la rotta corrente è 'admin.home' -->
                    <a class="nav-link @if(Route::is('admin.home')) active @endif" href="{{ route('admin.home') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <!-- Collegamento alla lista dei viaggi, evidenziato se la rotta corrente è 'admin.trips*' -->
                    <a class="nav-link @if(Request::is('admin/trips*')) active @endif" href="{{ route('admin.trips.index') }}">Viaggi</a>
                </li>
                @endauth
            </ul>

            <!-- Parte destra della barra di navigazione -->
            <ul class="navbar-nav ml-auto">
                <!-- Link di autenticazione -->
                @guest
                <li class="nav-item">
                    <!-- Collegamento alla pagina di login -->
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                <li class="nav-item">
                    <!-- Collegamento alla pagina di registrazione -->
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
                @endif
                @else
                <li class="nav-item dropdown">
                    <!-- Menu a discesa per l'utente autenticato -->
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <!-- Collegamento alla dashboard admin -->
                        <a class="dropdown-item" href="{{ url('admin') }}">{{__('Dashboard')}}</a>
                        <!-- Collegamento al profilo dell'utente -->
                        <a class="dropdown-item" href="{{ url('profile') }}">{{__('Profilo')}}</a>
                        <!-- Collegamento per il logout, con un form per inviare una richiesta POST -->
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Esci') }}
                        </a>

                        <!-- Form per il logout -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
