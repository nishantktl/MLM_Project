<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Winning Money</title>

    <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png') ?>" />
    <style>
      .registration-modal .modal-content {
          border: none;
          border-radius: 18px;
          overflow: hidden;
          box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      }

      .registration-modal .modal-body {
          padding: 2rem;
      }

      .registration-modal .success-icon {
          width: 72px;
          height: 72px;
          border-radius: 50%;
          background: linear-gradient(135deg, #22c55e, #16a34a);
          color: #fff;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 34px;
          margin: 0 auto 1rem;
          box-shadow: 0 10px 25px rgba(34, 197, 94, 0.35);
      }

      .registration-modal .modal-title {
          font-size: 1.5rem;
          font-weight: 700;
          color: #212529;
          margin-bottom: 0.25rem;
      }

      .registration-modal .modal-subtitle {
          color: #6c757d;
          font-size: 0.95rem;
          margin-bottom: 1.5rem;
      }

      .registration-modal .details-card {
          background: #f8f9fa;
          border: 1px solid #eef0f2;
          border-radius: 14px;
          padding: 0;
          overflow: hidden;
      }

      .registration-modal .detail-row {
          display: flex;
          justify-content: space-between;
          gap: 12px;
          padding: 12px 16px;
          border-bottom: 1px solid #e9ecef;
      }

      .registration-modal .detail-row:last-child {
          border-bottom: none;
      }

      .registration-modal .detail-label {
          font-size: 0.85rem;
          color: #6c757d;
          font-weight: 500;
          min-width: 120px;
      }

      .registration-modal .detail-value {
          font-size: 0.9rem;
          font-weight: 600;
          color: #212529;
          text-align: right;
          word-break: break-word;
      }

      .registration-modal .detail-value.primary {
          color: #0d6efd;
      }

      .registration-modal .detail-value.success {
          color: #198754;
      }

      .registration-modal .detail-value.warning {
          color: #f59f00;
      }

      .registration-modal .note-text {
          font-size: 0.8rem;
          color: #6c757d;
          margin-top: 1rem;
          margin-bottom: 1.5rem;
      }

      .registration-modal .btn-login {
          border-radius: 10px;
          font-weight: 600;
          padding: 0.75rem 1rem;
          font-size: 0.95rem;
          box-shadow: 0 6px 18px rgba(25, 135, 84, 0.2);
      }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="row w-100 m-0">
          <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
            <div class="card col-lg-4 mx-auto">
              <div class="card-body px-5 py-5">
                <h3 class="card-title text-left mb-3">Register</h3>
                <div id="form_error_alert"
                    class="alert alert-danger d-none"
                    role="alert">
                </div>
                <form id="register_form" method="post" novalidate>
                  <div class="form-group">
                    <label>Sponser ID</label>
                    <input type="text" name="sponser_id" class="form-control p_input" id="sponser_id" placeholder="Enter Sponser ID" required>
                    <small class="error-message text-danger" id="sponser_id_error"></small>
                  </div>
                  <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control p_input" id="username" placeholder="Enter username" required minlength="3" maxlength="100">
                    <small class="error-message text-danger" id="username_error"></small>
                  </div>
                  <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" class="form-control p_input" id="phone" placeholder="Enter phone number" pattern="[0-9]{10}" required>
                    <small class="error-message text-danger" id="phone_error"></small>
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control p_input" id="email" placeholder="Enter email" required>
                    <small class="error-message text-danger" id="email_error"></small>
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control p_input" id="password" placeholder="Enter password" required minlength="6">
                    <small class="error-message text-danger" id="password_error"></small>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block enter-btn" id="register_btn">Register</button>
                  </div>
                  <p class="sign-up text-center">Already have an Account?<a href="<?= base_url('login') ?>"> Sign In</a></p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="<?= base_url('assets/vendors/js/vendor.bundle.base.js') ?>"></script>
    <script src="<?= base_url('assets/js/off-canvas.js') ?>"></script>
    <script src="<?= base_url('assets/js/hoverable-collapse.js') ?>"></script>
    <script src="<?= base_url('assets/js/misc.js') ?>"></script>
    <script src="<?= base_url('assets/js/settings.js') ?>"></script>
    <script src="<?= base_url('assets/js/todolist.js') ?>"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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

                var username = $('#username').val().trim();
                var phone = $('#phone').val().trim();
                var email = $('#email').val().trim();
                var password = $('#password').val();
                var valid = true;
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                var phoneRegex = /^[0-9]{10}$/;
                var sponser_id = $('#sponser_id').val().trim();
                  
                if (sponser_id === '') {
                      $('#sponser_id_error').text('Sponser ID is required');
                      valid = false;
                  }

                if (username === '') {
                    $('#username_error').text('Username is required');
                    valid = false;
                } else if (username.length < 3) {
                    $('#username_error').text('Username must be at least 3 characters');
                    valid = false;
                }

                if (phone === '') {
                    $('#phone_error').text('Phone number is required');
                    valid = false;
                } else if (!phoneRegex.test(phone)) {
                    $('#phone_error').text('Phone number must be 10 digits');
                    valid = false;
                }

                if (email === '') {
                    $('#email_error').text('Email is required');
                    valid = false;
                } else if (!emailRegex.test(email)) {
                    $('#email_error').text('Email is not valid');
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

            $('#sponser_id, #username, #phone, #email, #password').on('input change', function() {
                $(this).closest('.form-group').find('.error-message').text('');
            });

            $('#register_form').submit(function(e) {
                console.log('test');
                e.preventDefault();

                if (!validateForm()) {
                    return;
                }

                var params = {
                    username: $('#username').val().trim(),
                    phone: $('#phone').val().trim(),
                    email: $('#email').val().trim(),
                    password: $('#password').val(),
                    sponser_id: $('#sponser_id').val().trim()
                };

                $.ajax({
                  url: '<?= base_url('register/submit') ?>',
                  type: 'POST',
                  data: params,
                  dataType: 'json',
                  success: function(response) {
                      if (response.Resp_code === 'RCS') {
                          let modalHtml = `
                            <div class="modal fade registration-modal"
                                id="registrationSuccessModal"
                                tabindex="-1"
                                aria-hidden="true">

                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-body text-center">

                                            <div class="success-icon">
                                                <i class="mdi mdi-check"></i>
                                            </div>

                                            <h4 class="modal-title">${response.Resp_desc}</h4>
                                            <div class="details-card text-start">

                                                <div class="detail-row">
                                                    <div class="detail-label">User ID</div>
                                                    <div class="detail-value primary">
                                                        ${response.data.user_id}
                                                    </div>
                                                </div>

                                                <div class="detail-row">
                                                    <div class="detail-label">Username</div>
                                                    <div class="detail-value">
                                                        ${response.data.username}
                                                    </div>
                                                </div>

                                                <div class="detail-row">
                                                    <div class="detail-label">Email</div>
                                                    <div class="detail-value">
                                                        ${response.data.email}
                                                    </div>
                                                </div>

                                                <div class="detail-row">
                                                    <div class="detail-label">Phone</div>
                                                    <div class="detail-value">
                                                        ${response.data.phone}
                                                    </div>
                                                </div>

                                                <div class="detail-row">
                                                    <div class="detail-label">Password</div>
                                                    <div class="detail-value">
                                                        ${response.data.password}
                                                    </div>
                                                </div>

                                                <div class="detail-row">
                                                    <div class="detail-label">Transaction PIN</div>
                                                    <div class="detail-value warning">
                                                        ${response.data.txn_pin}
                                                    </div>
                                                </div>

                                                <div class="detail-row">
                                                    <div class="detail-label">Signup Bonus</div>
                                                    <div class="detail-value success">
                                                        ₹${response.data.bonus}
                                                    </div>
                                                </div>

                                            </div>

                                            <p class="note-text">
                                                Please save your login credentials and transaction PIN for future use.
                                            </p>

                                            <a href="<?= base_url('login') ?>"
                                              class="btn btn-success btn-login w-100">
                                                Go to Login
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;

                          $('body').append(modalHtml);

                          // Initialize Bootstrap modal
                          var modalElement = document.getElementById('registrationSuccessModal');
                          var successModal = new bootstrap.Modal(modalElement);

                          // Show modal
                          successModal.show();

                          // Remove modal from DOM after it is hidden
                          modalElement.addEventListener('hidden.bs.modal', function () {
                              successModal.dispose();
                              $('#registrationSuccessModal').remove();
                          });

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