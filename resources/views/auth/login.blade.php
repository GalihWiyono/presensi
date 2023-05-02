<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Signin Template · Bootstrap v5.0</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">


    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">


    <!-- Custom styles for this template -->
    <link href="{{ 'css/signin.css' }}" rel="stylesheet">
</head>

<body class="text-center">
    @if (session()->has('status'))
        <div class="alert alert-danger alert-dismissible fade show mt-3 mx-3" id="notification" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="div container">
        <main class="form-signin">
            <form action="/login" method="post">
                @csrf
                <img class="mb-4" src="{{ 'brand/bootstrap-logo.svg' }}" alt="" width="72" height="57">
                <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
                <div class="form-floating">
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                        id="floatingInput" placeholder="Username" autofocus autocomplete="off"
                        value="{{ old('username') }}">
                    <label for="floatingInput" name="username">Username</label>
                    @error('username')
                        <div class="div invalid-feedback mb-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-floating">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword" name="password">Password</label>
                    @error('password')
                        <div class="div invalid-feedback mb-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button class="w-100 mt-3 btn btn-lg btn-primary" type="submit">Sign in</button>
                <p class="mt-4 mb-3 text-muted">&copy; 2023 Muhammad Galih Wiyono Putra</p>
            </form>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
        integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous">
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"
    integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous">
</script> --}}

    <script src="{{ 'js/dashboard.js' }}"></script>
</body>

</html>
