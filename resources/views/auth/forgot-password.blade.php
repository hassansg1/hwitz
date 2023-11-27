<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Urban Sky</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="{{asset('fonts/fonts.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  </head>
  <body>
    <section class="login-box container d-flex justify-content-center flex-column">
      <div>
        <div class="login-container">
          <img src="{{url('images/logo.png')}}" alt="" />
          <h3 class="fw-bolder">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</h3>
        </div>
        <x-auth-session-status class="mb-4" :status="session('status')" style="text-align: center;color:green" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" style="text-align: center; color:red" />
        <form class="form-box" action="{{ route('password.email') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="label"
              >E-mail address</label
            >
            <div>
              <input  name="email" required autofocus type="email" name="email" :value="old('email')" class="form-control w-100" /> 
            </div>
          </div>
          <button
            type="submit"
            class="btn btn-primary btn-login w-100 mt-4"
          >
            Email password reset link
          </button>
          </p>
        </form>
      </div>
    </section>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"
    ></script>
    <script src="/js/login.js"></script>
  </body>
</html>
