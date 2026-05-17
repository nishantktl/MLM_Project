<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Winning Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>"> 
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png') ?>" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="row w-100 m-0">
          <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
            <div class="card col-lg-4 mx-auto">
              <div class="card-body px-5 py-5">
                <h3 class="card-title text-left mb-3">Login</h3>
                <div class="alert alert-danger d-none" id="form_error_alert"></div>
                <form>
                  <div class="form-group">
                    <label>User ID / Username *</label>
                    <input type="text" class="form-control p_input" id="login_id"
                        style="background-color: #2A3038; color: #ffffff; opacity: 1;"
                        placeholder="Enter User ID or Username">
                    <div class="error-message" id="login_id_error"></div>
                  </div>
                  <div class="form-group">
                    <label>Password *</label>
                    <input type="password" class="form-control p_input" id="password"
                        style="background-color: #2A3038; color: #ffffff; opacity: 1;"
                    >
                    <div class="error-message" id="password_error"></div>
                  </div>
                  <div class="text-center">
                    <button type="button" class="btn btn-primary btn-block enter-btn" id="login_btn">Login</button>
                  </div>
                  <p class="sign-up text-center">Don't have an Account?<a href="<?= base_url('register') ?>"> Sign Up</a></p>
                  </form>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- row ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="<?= base_url('assets/vendors/js/vendor.bundle.base.js') ?>"></script>
    <script src="<?= base_url('assets/js/off-canvas.js') ?>"></script>
    <script src="<?= base_url('assets/js/hoverable-collapse.js') ?>"></script>
    <script src="<?= base_url('assets/js/misc.js') ?>"></script>
    <script src="<?= base_url('assets/js/settings.js') ?>"></script>
    <script src="<?= base_url('assets/js/todolist.js') ?>"></script>
    <script>
      $(document).ready(function() {
            function clearErrors() {
                $('.error-message').text('');
            }

            function displayErrors(errors) {
                if (!errors) {
                    return;
                }

                Object.keys(errors).forEach(function(field) {
                    $('#' + field + '_error').text(errors[field]);
                });
            }

            function validateForm() {
                clearErrors();

                var loginId = $('#login_id').val().trim();
                var password = $('#password').val();
                var valid = true;

                if (loginId === '') {
                    $('#login_id_error').text('User ID or Username is required');
                    valid = false;
                }

                if (password === '') {
                    $('#password_error').text('Password is required');
                    valid = false;
                } else if (password.length < 6) {
                    $('#password_error').text('Password must be at least 6 characters');
                    valid = false;
                }

                return valid;
            }

            $('#login_id, #password').on('input change', function() {
                $(this).closest('.form-group').find('.error-message').text('');
            });

            $('#login_btn').click(function(e) {
                e.preventDefault();

                if (!validateForm()) {
                    return;
                }

                var params = {
                    login_id: $('#login_id').val().trim(),
                    password: $('#password').val(),
                };

                $.ajax({
                  url: '<?= base_url('login/submit') ?>',
                  type: 'POST',
                  data: params,
                  dataType: 'json',
                  success: function(response) {
                      if (response.Resp_code === 'RCS') {
                          window.location.href = response.data.redirect_url;
                      } else {
                          clearErrors();

                          if (response.data && typeof response.data === 'object') {
                              displayErrors(response.data);
                          }

                          // Show general error message in alert box above the form
                          if (response.Resp_desc) {
                              $('#form_error_alert')
                                  .removeClass('d-none')
                                  .html(response.Resp_desc);
                          } else {
                              $('#form_error_alert')
                                  .removeClass('d-none')
                                  .html('Something went wrong. Please try again.');
                          }

                          // Scroll to the error alert
                          $('html, body').animate({
                              scrollTop: $('#form_error_alert').offset().top - 20
                          }, 300);
                      }
                  },
                  error: function(xhr) {
                    clearErrors();

                    var json = xhr.responseJSON;

                    if (json) {
                        // Display field-level validation errors
                        if (json.data && typeof json.data === 'object') {
                            displayErrors(json.data);
                        }

                        // Display general error alert
                        $('#form_error_alert')
                            .removeClass('d-none')
                            .html(json.Resp_desc || 'An error occurred. Please try again.');
                    } else {
                        $('#form_error_alert')
                            .removeClass('d-none')
                            .html('An error occurred. Please try again.');
                    }

                    // Scroll to alert
                    $('html, body').animate({
                        scrollTop: $('#form_error_alert').offset().top - 20
                    }, 300);
                }
              });
            });
        }); 
    </script>
  </body>
</html>