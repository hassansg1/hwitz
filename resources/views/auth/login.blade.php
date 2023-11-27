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
          <h3 class="fw-bolder">Growth for owners, growth for residents</h3>
        </div>
        <form class="form-box" action="{{ route('login.post') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="label">E-mail address</label>
            <div>
              <input  name="email" required autofocus type="email" class="form-control w-100" /> 
            </div>
            @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
            @endif
          </div>
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="label">Password</label>
            <div style="display: flex; justify-content: space-between">
              <div class="input-group mb-3">

                <input type="password" name="password" class="form-control"  aria-label="password" id="password" >
                <span class="input-group-text bg-white border-start-0 select-cursor" id="basic-addon1"> 
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.73373 7.14528C4.4619 8.17472 3.46019 9.40197 2.89623 10.1662C2.80437 10.2907 2.76126 10.3501 2.73238 10.3973C2.71434 10.4268 2.71092 10.4372 2.70903 10.4443C2.7088 10.4455 2.70811 10.4494 2.70735 10.4562C2.70606 10.4677 2.70514 10.4826 2.70514 10.4993C2.70514 10.516 2.70606 10.5309 2.70735 10.5425C2.70811 10.5492 2.7088 10.5531 2.70903 10.5544C2.71092 10.5614 2.71434 10.5718 2.73238 10.6013C2.76126 10.6485 2.80437 10.7079 2.89623 10.8324C3.46019 11.5967 4.4619 12.8239 5.73373 13.8533C7.01683 14.8919 8.48678 15.6581 10.0004 15.6581C11.514 15.6581 12.9839 14.8919 14.267 13.8533C15.5389 12.8239 16.5406 11.5967 17.1045 10.8324C17.1964 10.7079 17.2395 10.6485 17.2684 10.6013C17.2864 10.5718 17.2899 10.5614 17.2917 10.5544C17.292 10.5531 17.2927 10.5492 17.2934 10.5425C17.2947 10.5309 17.2956 10.516 17.2956 10.4993C17.2956 10.4826 17.2947 10.4677 17.2934 10.4562C17.2927 10.4494 17.292 10.4455 17.2917 10.4443C17.2899 10.4372 17.2864 10.4268 17.2684 10.3973C17.2395 10.3501 17.1964 10.2907 17.1045 10.1662C16.5406 9.40197 15.5389 8.17472 14.267 7.14528C12.9839 6.10672 11.514 5.34053 10.0004 5.34053C8.48678 5.34053 7.01683 6.10672 5.73373 7.14528ZM4.79568 6.06766C6.18919 4.93973 7.98679 3.93359 10.0004 3.93359C12.014 3.93359 13.8116 4.93973 15.2051 6.06766C16.6099 7.2047 17.6939 8.53812 18.2934 9.35054C18.3032 9.36387 18.3131 9.3773 18.3232 9.39083C18.4673 9.58513 18.628 9.80189 18.7066 10.1006C18.7707 10.3442 18.7707 10.6544 18.7066 10.898C18.628 11.1967 18.4673 11.4135 18.3232 11.6078C18.3131 11.6213 18.3032 11.6348 18.2934 11.6481C17.6939 12.4605 16.6099 13.7939 15.2051 14.931C13.8116 16.0589 12.014 17.065 10.0004 17.065C7.98679 17.065 6.18919 16.0589 4.79568 14.931C3.3909 13.7939 2.30692 12.4605 1.70742 11.6481C1.69758 11.6348 1.68763 11.6213 1.67759 11.6078C1.53351 11.4135 1.37277 11.1967 1.29417 10.898C1.23007 10.6544 1.23007 10.3442 1.29417 10.1006C1.37277 9.80189 1.53351 9.58513 1.67759 9.39083C1.68763 9.37729 1.69758 9.36387 1.70742 9.35054C2.30692 8.53812 3.3909 7.2047 4.79568 6.06766ZM10.0004 9.09237C9.19458 9.09237 8.54134 9.72228 8.54134 10.4993C8.54134 11.2763 9.19458 11.9063 10.0004 11.9063C10.8062 11.9063 11.4594 11.2763 11.4594 10.4993C11.4594 9.72228 10.8062 9.09237 10.0004 9.09237ZM7.08229 10.4993C7.08229 8.94525 8.38877 7.68543 10.0004 7.68543C11.612 7.68543 12.9185 8.94525 12.9185 10.4993C12.9185 12.0534 11.612 13.3132 10.0004 13.3132C8.38877 13.3132 7.08229 12.0534 7.08229 10.4993Z" fill="#999999" onclick="togglePasswordVisibility('password')"/>
                  </svg>
                </span>
              </div>
              @if ($errors->has('password'))
                  <span class="text-danger">{{ $errors->first('password') }}</span>
              @endif
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-login w-100 mt-4">
            Login
          </button>
          <p class="forget-pass label">
            <a  href="{{route('password.request')}}">I forgot my password</a>
          </p>
        </form>
      </div>
    </section>

    <div
      class="modal fade"
      id="forgetPasswordModal"
      tabindex="-1"
      aria-labelledby="forgetPasswordModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h4 class="forget-password-heading">
              Would you like to remember your password?
            </h4>
            <p class="forget-password-text">
              You can view, edit, or remove saved passwords in the Passwords pane of System Settings.
            </p>
            <div style="display: block">
              <div>
                <button
                  type="button"
                  class="btn save-btn linear-border"
                  data-bs-dismiss="modal"
                >
                  Save Password
                </button>
              </div>
              <div>
                <button
                  type="button"
                  class="btn save-btn"
                  data-bs-dismiss="modal"
                >
                  Never for This Website
                </button>
              </div>
              <div>
                <button
                  type="button"
                  class="btn save-btn"
                  data-bs-dismiss="modal"
                >
                  Not Now
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"
    ></script>
    <script src="/js/login.js"></script>
  </body>
</html>
