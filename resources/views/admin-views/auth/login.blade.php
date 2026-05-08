<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required Meta Tags Always Come First -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Title -->
  <title>Admin | Login</title>

  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('assets/logo/logo-icon.png') }}" />

  <style>
    .input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 20px;
      color: #C6944F;
    }

    .icon-input {
      padding-left: 45px;
    }

    .toggle-password {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 20px;
      color: #6c757d;
    }
  </style>

</head>

<body>

  <!-- ========== MAIN CONTENT ========== -->
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth adminLoginBackGround">
        <div class="row flex-grow">
          <div class="col-lg-5 mx-auto">
            <div class="auth-form-light text-left px-4 py-4">
              <div class="brand-logo d-flex justify-content-center">
                <img src="{{ asset('assets/logo/logo-3.png') }}">
              </div>
              <h4>{{ 'Admin Signin' }}</h4>
              <h6 class="font-weight-light mb-0">Sign in to continue.</h6>
              <form class="pt-3" action="{{ route('admin.auth.login') }}" method="POST" id="submit-login-form">
                {{ csrf_field() }}
                <div class="form-group position-relative">
                  <i class="mdi mdi-account-outline input-icon"></i>
                  <input required
                    type="email"
                    name="email"
                    class="form-control form-control-lg icon-input"
                    id="exampleInputEmail1"
                    placeholder="Enter Username">
                </div>

                <div class="form-group position-relative">
                  <i class="mdi mdi-lock-outline input-icon"></i>

                  <input required
                    type="password"
                    name="password"
                    class="form-control form-control-lg icon-input"
                    id="exampleInputPassword1"
                    placeholder="Enter Password">

                  <i class="mdi mdi-eye-outline toggle-password" id="togglePassword"></i>
                </div>
                <div class="mt-3 d-grid gap-2">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                </div>
                <div class="mt-3 d-flex justify-content-between align-items-center">
                  <div class="form-check m-0">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" name="remember" value="1" class="form-check-input"> Keep me signed in </label>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- ========== END MAIN CONTENT ========== -->

  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('assets/js/misc.js') }}"></script>
  <script src="{{ asset('assets/js/settings.js') }}"></script>
  <script src="{{ asset('assets/js/todolist.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).on('submit', '#submit-login-form', function(e) {
      e.preventDefault();
      var form = $(this);
      $.ajax({
        url: form.attr('action'),
        method: form.attr('method'),
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
          if (response.status) {
            window.location.href = "{{ route('admin.dashboard') }}";
          } else {
            response.errors = Object.values(response.errors).flat();
            console.log('response.errors ----- ', response.errors)
            // alert(response.errors.join('\n'));

            // alert(response.message || 'Login failed. Please try again.');
          }
        },
        error: function(xhr) {
          resp = xhr.responseJSON.message
          if (xhr.responseJSON.errors) {
            resp = Object.values(xhr.responseJSON.errors).flat();
            swal.fire('', resp.join('<br>'), 'error')
          } else {
            swal.fire('', xhr.responseJSON.message, 'error')
          }

          // alert(xhr.responseJSON?.message || 'An error occurred. Please try again.');
        }
      });
    });
  </script>
  <script>
    $(document).on('click', '#togglePassword', function() {

      let passwordInput = $('#exampleInputPassword1');

      if (passwordInput.attr('type') === 'password') {
        passwordInput.attr('type', 'text');
        $(this).removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
      } else {
        passwordInput.attr('type', 'password');
        $(this).removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
      }

    });
  </script>

  <!-- endinject -->
</body>

</html>