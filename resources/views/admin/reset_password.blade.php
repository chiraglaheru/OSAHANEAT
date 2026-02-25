<html>
    <title>Nitgnn</title>
    <body>
        <h1>Reset Password Page</h1>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <body class="container">

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            @endif

            @if (Session::has('error'))
                <li>{{ Session::get('error') }}</li>
            @endif

            @if (Session::has('success'))
                <li>{{ Session::get('success') }}</li>
            @endif

            <form method="POST" action="{{ route('admin.login_submit') }}">
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
              @csrf
              <div class="mb-3">
                  <label for="email" class="form-label">new password</label>
                  <input type="password" name="password " class="form-control" id="email" aria-describedby="emailHelp">
              </div>
              <div class="mb-3">
                  <label for="password" class="form-label">Confirm new password</label>
                  <input type="password" name="password_Confirmation" class="form-control" id="password">
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
          </form>

        </body>
    </body>
</html>
