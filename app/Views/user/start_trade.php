<?php
$data = getuserdata();
$package_options = default_package();
?>
<div class="container-scroller">
  <!-- SIDEBAR -->
  <?= $this->include('user/layout/sidebar') ?>

  <!-- MAIN CONTAINER -->
  <div class="container-fluid page-body-wrapper">
    <!-- NAVBAR -->
    <nav class="navbar p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
      </div>
      <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
          <!-- Navbar profile dropdown... -->
          <li class="nav-item dropdown">
            <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
              <div class="navbar-profile">
                <img class="img-xs rounded-circle" src="<?= base_url('assets/images/faces/face15.jpg') ?>" alt="">
                <p class="mb-0 d-none d-sm-block navbar-profile-name"><?= isset($data['username']) ? $data['username'] : 'User'; ?></p>
                <i class="mdi mdi-menu-down d-none d-sm-block"></i>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
              <h6 class="p-3 mb-0">Profile</h6>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item" href="<?= base_url('logout') ?>">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-logout text-danger"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <p class="preview-subject mb-1">Log out</p>
                </div>
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-format-line-spacing"></span>
        </button>
      </div>
    </nav>

    <!-- CONTENT PANEL -->
    <div class="main-panel">
      <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-4">

          <!-- ================= SUCCESS / ERROR BANNERS ================= -->
          <!-- SUCCESS / ERROR BANNERS -->
        <div id="investment_success_banner"
            class="alert alert-success d-none text-white bg-success font-weight-bold p-3 mb-4 rounded shadow-sm"></div>

        <div id="investment_error_banner"
            class="alert alert-danger d-none text-white bg-danger font-weight-bold p-3 mb-4 rounded shadow-sm"></div>

        <!-- HEADING -->
        <div class="border-bottom pb-3 mb-4">
            <h4 class="card-title font-weight-bold text-white mb-0">
                <i class="mdi mdi-chart-line text-success me-2"></i> New Investment
            </h4>
        </div>

        <!-- FORM START -->
        <form id="investmentForm">

            <!-- CSRF Token -->
            <input type="hidden"
                name="<?= csrf_token() ?>"
                value="<?= csrf_hash() ?>"
                id="csrf_token_input">

            <!-- Hidden User ID -->
            <input type="hidden"
                name="user_id"
                id="user_id"
                value="<?= session()->get('user_id') ?>">

            <input type="hidden" name="action_type" value="initial_topup">

            <div class="row">

                <!-- ================= LEFT COLUMN ================= -->
                <div class="col-lg-6 pr-lg-4">

                    <!-- Wallet Fund -->
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-white">
                            Wallet Fund <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-dark text-white border-right-0 font-weight-bold">₹</span>
                            </div>
                            <input type="text"
                                class="form-control p_input text-white border-left-0"
                                id="wallet_fund"
                                name="wallet_fund"
                                value="<?= esc(number_format($deposit_balance ?? 0, 2)) ?>"
                                disabled
                                style="background-color: #2A3038; color: #ffffff; opacity: 1;">
                        </div>
                        <div class="error-message text-danger small mt-1 font-weight-bold"
                            id="wallet_fund_error"></div>
                    </div>

                    <!-- Member ID -->
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-white">
                            Member ID <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            class="form-control p_input text-white"
                            id="member_id"
                            name="member_id"
                            placeholder="Enter member ID">
                        <div class="error-message text-danger small mt-1 font-weight-bold"
                            id="member_id_error"></div>
                    </div>

                    <!-- Member Name -->
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-white">
                            Member Name
                        </label>
                        <input type="text"
                            class="form-control p_input text-white"
                            id="member_name"
                            name="member_name"
                            placeholder="Member name will appear automatically"
                            disabled
                            style="background-color: #2A3038; color: #ffffff; opacity: 1;">
                        <div class="error-message text-danger small mt-1 font-weight-bold"
                            id="member_name_error"></div>
                    </div>

                    <!-- Investment Amount -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-white">
                            Investment Amount <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-dark text-white border-right-0 font-weight-bold">
                                    ₹
                                </span>
                            </div>

                            <select
                                class="form-control p_input text-white border-left-0"
                                id="investment_amount"
                                name="investment_amount"
                                style="background-color:#2A3038; color:#ffffff;"
                            >
                                <option value="">Select Investment Package</option>

                                <?php foreach ($package_options as $key => $package): ?>
                                    <option value="<?= esc($key) ?>">
                                        <?= esc('Package ' . $package[0]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div
                            class="error-message text-danger small mt-1 font-weight-bold"
                            id="investment_amount_error"
                        ></div>
                    </div>

                    <!-- BUTTONS -->
                    <div class="form-group mt-2 mb-0">
                        <button type="button"
                                class="btn btn-success px-4 py-2 font-weight-bold me-2 text-white"
                                id="submit_investment">
                            <i class="mdi mdi-content-save border-0"></i> Submit Investment
                        </button>

                        <button type="button"
                                class="btn btn-outline-light px-4 py-2 text-white"
                                id="reset_investment">
                            <i class="mdi mdi-refresh border-0"></i> Reset
                        </button>
                    </div>

                </div>

                <!-- ================= RIGHT COLUMN ================= -->
                <div class="col-lg-6 border-left d-flex flex-column justify-content-center align-items-center text-center mt-4 mt-lg-0">

                    <h6 class="font-weight-bold text-white mb-3">
                        Investment Summary
                    </h6>

                    <div class="p-3 border rounded bg-dark shadow-sm w-100">
                        <p class="text-white mb-2">
                            <strong>User ID:</strong><br>
                            <?= esc(session()->get('user_id')) ?>
                        </p>

                        <p class="text-white mb-2">
                            <strong>Available Wallet Balance:</strong><br>
                            ₹ <span id="updated_wallet_balance"><?= esc(number_format($deposit_balance ?? 0, 2)) ?></span>
                        </p>

                        <p class="text-light small mb-0">
                            <i class="mdi mdi-information-outline text-warning"></i>
                            Enter the target member ID and the investment amount.
                            Member name will be fetched automatically.
                        </p>
                    </div>

                </div>

            </div>
        </form>
          <!-- FORM END -->

        </div>
      </div>
      
      <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between"></div>
      </footer>
    </div>
  </div>
</div>

<!-- JQUERY SCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    $('#member_id').on('keyup blur', function () {
        let memberId = $(this).val().trim();

        // Clear previous values
        $('#member_name').val('');
        $('#member_id_error').text('');

        // Only call AJAX if length is greater than 5
        if (memberId.length < 5) {
            return;
        }

        $.ajax({
            url: "<?= base_url('user/get_member_details') ?>",
            type: "POST",
            dataType: "json",
            data: {
                member_id: memberId,
                "<?= csrf_token() ?>": $('#csrf_token_input').val()
            },
            success: function (response) {

                // Update CSRF token
                if (response.csrf_hash) {
                    $('#csrf_token_input').val(response.csrf_hash);
                }

                if (response.Resp_code === 'RCS') {
                    $('#member_name').val(response.data.username);
                } else {
                    $('#member_name').val('');
                    $('#member_id_error').text(response.Resp_desc);
                }
            },
            error: function () {
                $('#member_name').val('');
                $('#member_id_error').text('Unable to fetch member details.');
            }
        });
    });

     // Submit Investment
    $('#submit_investment').on('click', function () {

        // Clear previous messages
        $('.error-message').text('');
        $('#investment_success_banner')
            .addClass('d-none')
            .removeClass('alert-success')
            .text('');

        $('#investment_error_banner')
            .addClass('d-none')
            .removeClass('alert-danger')
            .text('');

        let memberId = $('#member_id').val().trim();
        let investmentAmount = $('#investment_amount').val().trim();

        // Client-side validation
        let hasError = false;

        if (memberId === '') {
            $('#member_id_error').text('Member ID is required.');
            hasError = true;
        }

        // if (investmentAmount === '' || parseFloat(investmentAmount) <= 0) {
        //     $('#investment_amount_error').text('Please enter a valid investment amount.');
        //     hasError = true;
        // }

        if (hasError) {
            return;
        }

        // Disable button while processing
        $('#submit_investment')
            .prop('disabled', true)
            .html('<i class="mdi mdi-loading mdi-spin"></i> Processing...');

        $.ajax({
            url: "<?= base_url('user/submit-investment') ?>",
            type: "POST",
            dataType: "json",
            data: $('#investmentForm').serialize(),

            success: function (response) {

                // Update CSRF token
                if (response.csrf_hash) {
                    $('#csrf_token_input').val(response.csrf_hash);
                }

                if (response.Resp_code === 'SUCCESS') {

                    // Success banner
                    $('#investment_success_banner')
                        .removeClass('d-none')
                        .addClass('alert-success')
                        .text(response.Resp_desc);

                    // Update wallet fund field
                    if (response.data.remaining_balance !== undefined) {
                        $('#wallet_fund').val(
                            parseFloat(response.data.remaining_balance).toFixed(2)
                        );

                        $('#updated_wallet_balance').text(
                            parseFloat(response.data.remaining_balance).toFixed(2)
                        );
                    }

                    // Reset form fields except wallet fund
                    $('#member_id').val('');
                    $('#member_name').val('');
                    $('#investment_amount').val('');

                } else {

                    // Error banner
                    $('#investment_error_banner')
                        .removeClass('d-none')
                        .addClass('alert-danger')
                        .text(response.Resp_desc);
                }
            },

            error: function () {
                $('#investment_error_banner')
                    .removeClass('d-none')
                    .addClass('alert-danger')
                    .text('Server error. Please try again.');
            },

            complete: function () {
                $('#submit_investment')
                    .prop('disabled', false)
                    .html('<i class="mdi mdi-content-save border-0"></i> Submit Investment');
            }
        });
    });

    // Reset Form
    $('#reset_investment').on('click', function () {
        $('#member_id').val('');
        $('#member_name').val('');
        $('#investment_amount').val('');

        $('.error-message').text('');
        $('#investment_success_banner').addClass('d-none').text('');
        $('#investment_error_banner').addClass('d-none').text('');
    });
});
</script>