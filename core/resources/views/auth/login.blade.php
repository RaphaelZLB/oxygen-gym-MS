<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Oxygen Gym LB</title>
    <link rel="icon" href="{{ asset('images/o2-icon.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="app-login-bg d-flex flex-column min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 mt-1 text-center">
                @include('partials.flash')

                <img src="{{ asset('images/oxygen_logo_high_res.png') }}" alt="Oxygen Gym Logo"
                    class="mx-auto d-block app-logo-login" style="filter: brightness(0) invert(1);">
                <div>
                    {{-- <h1 class="h4 mb-1">Oxygen Gym LB</h1> --}}
                    <p class="text-white">Welcome back, please login</p>
                </div>

                <div class="card app-card">
                    <div class="card-body">
                        <h2 class="h5 mb-3 text-center text-white">Login</h2>

                        <form method="POST" action="{{ route('login.submit') }}" id="login-form">
                            @csrf
                            <!-- Email input -->
                            <div class="mb-3 text-start">
                                <label class="form-label" for="email">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                    autocomplete="email" class="form-control @error('email') is-invalid @enderror"
                                    required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Password input -->
                            <div class="mb-4 text-start">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group">
                                    <input id="password" type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror" required autofocus>

                                    <button type="button" class="btn btn-outline-light" id="toggle-password"
                                        aria-label="Show password" aria-pressed="false">Show</button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember me checkbox -->
                            <div class="mb-4 d-flex justify-content-end align-items-center gap-2">
                                <label class="form-check-label text-white user-select-none mb-0" for="remember">Remember me</label>
                                <input class="form-check-input flex-shrink-0 m-0" type="checkbox" name="remember"
                                    id="remember" value="1" @checked(old('remember'))>
                            </div>
                            
                            <button class="btn btn-primary" type="submit">Login</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        (function() {
            const form = document.getElementById('login-form');
            const password = document.getElementById('password');
            const toggleBtn = document.getElementById('toggle-password');

            toggleBtn?.addEventListener('click', function() {
                if (!password) return;
                const show = password.type === 'password';
                password.type = show ? 'text' : 'password';
                toggleBtn.textContent = show ? 'Hide' : 'Show';
                toggleBtn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                toggleBtn.setAttribute('aria-pressed', show ? 'true' : 'false');
            });

            form?.addEventListener('submit', function() {
                const btn = form.querySelector('[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.textContent = 'Logging in…';
                }
                form.querySelectorAll('#email, #password').forEach(function(el) {
                    el.readOnly = true;
                });
                let overlay = document.getElementById('login-busy-overlay');
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.id = 'login-busy-overlay';
                    overlay.setAttribute('aria-busy', 'true');
                    overlay.style.cssText =
                        'position:fixed;inset:0;background:rgba(15,18,22,0.35);z-index:9999;cursor:wait;';
                    document.body.appendChild(overlay);
                }
            });
        })();
    </script>
</body>

</html>
