<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Masjid Pedia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
    <div class="container-fluid d-flex flex-column justify-content-center align-items-center login-container bg-light">
        @if(session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif
        <div class="login-box bg-white shadow rounded p-4">
            <h1 class="text-center">MasjidPedia</h1>
            <div class="mt-4">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input 
                            type="email" 
                            class="form-control 
                            @error('email') is-invalid @enderror 
                            @if(session('error-role')) is-invalid @endif" 
                            name="email" 
                            @if(session('error-role')) 
                            value="{{ session('error-role')['email'] }}"
                            @else  
                            value="{{ old('email') }}"
                            @endif  
                            autofocus 
                            required
                            >
                          @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          @if(session('error-role'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ session('error-role')['message'] }}</strong>
                                </span>
                            @endif
                        </div>
                      </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                          <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                          @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                        </div>
                    </div>
                      <div class="mb-3 row">
                          <div class="col-sm-2"></div>
                          <div class="col-sm-10">
                              <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> 
                              <label class="form-check-label" for="remember">
                               Remember Me
                            </label>
                          </div>
                      </div>
                      <div class="mb-3 row">
                          <div class="col-sm-2"></div>
                          <div class="col-sm-10">
                              <button type="submit" class="btn btn-primary d-inline-block me-2">Login</button>
                              <a href="{{ route('register') }}" class="btn btn-outline-primary d-inline-block">Register</a>
                          </div>
                      </div>
                      <div class="row">
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Lupa password ? klik di sini') }}
                            </a>
                      </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>