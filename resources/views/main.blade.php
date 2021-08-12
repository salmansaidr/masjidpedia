<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.3.1/dt-1.10.25/datatables.min.css"/>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    @yield('css')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
          <a class="navbar-brand" href="{{ route('dashboard') }}">MasjidPedia</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Hallo, {{ Auth::user()->name }}</a>
              </li>
              <li class="nav-item nav-request-toko">
                <a class="nav-link active" aria-current="page" href="{{ route('product-request.index') }}" style="text-decoration: underline;">Request Toko</a>
                @php
                    $newRequest = \App\Http\Controllers\RequestProductSupplierController::countNewRequest();
                @endphp
                @if($newRequest)
                  <div>!</div>
                @endif
              </li>
              <li class="nav-item">
                <a class="nav-link active" style="text-decoration: underline;" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <div class="container main-container d-flex flex-column justify-content-center pt-5 pb-5" style="position: relative;">
            @yield('content')
        </div>
      </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.3.1/dt-1.10.25/datatables.min.js"></script>
    @yield('js')
</body>
</html>