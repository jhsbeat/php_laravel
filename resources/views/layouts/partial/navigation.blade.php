<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">
      {{ config('app.name', 'Laravel') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav mr-auto">

      </ul>

      <!-- Right Side Of Navbar -->
      <ul class="navbar-nav ml-auto">
        <li><a class="nav-link" href="{{ route('articles.index') }}">포럼</a></li>

        <!-- Authentication Links -->
        @guest
          <li><a class="nav-link" href="{{ route('sessions.create') }}">{{ __('Login') }}</a></li>
          <li><a class="nav-link" href="{{ route('users.create') }}">{{ __('Register') }}</a></li>
        @else
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
              {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ route('sessions.destroy') }}">
                {{ __('Logout') }}
              </a>
            </div>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>