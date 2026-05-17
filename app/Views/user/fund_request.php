<?php
$data = getuserdata();
$qr = get_qr_code();
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
          <div id="deposit_success_banner" class="alert alert-success d-none text-white bg-success font-weight-bold p-3 mb-4 rounded shadow-sm"></div>
          <div id="deposit_error_banner" class="alert alert-danger d-none text-white bg-danger font-weight-bold p-3 mb-4 rounded shadow-sm"></div>

          <!-- HEADING -->
          <div class="border-bottom pb-3 mb-4">
            <h4 class="card-title font-weight-bold text-white mb-0">
              <i class="mdi mdi-wallet-plus text-success me-2"></i> Fund Deposit Request
            </h4>
          </div>

          <!-- FORM START -->
          <form id="fundDepositForm">
            <!-- CI4 CSRF Token -->
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token_input">

            <div class="row">

              <!-- ================= LEFT COLUMN: INPUTS & BUTTONS ================= -->
              <div class="col-lg-6 pr-lg-4">

                <div class="form-group mb-3">
                  <label class="font-weight-bold text-white">Fund Amount <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-dark text-white border-right-0 font-weight-bold">₹</span>
                    </div>
                    <input type="text" class="form-control p_input text-white border-left-0" id="fund_amount" name="fund_amount" placeholder="Enter deposit amount">
                  </div>
                  <div class="error-message text-danger small mt-1 font-weight-bold" id="fund_amount_error"></div>
                </div>

                <div class="form-group mb-4">
                  <label class="font-weight-bold text-white">UTR / Reference Number <span class="text-danger">*</span></label>
                  <input type="text" class="form-control p_input text-white" id="utr" name="utr" placeholder="Enter 12-digit UTR or Txn ID">
                  <div class="error-message text-danger small mt-1 font-weight-bold" id="utr_error"></div>
                </div>

                <!-- BUTTONS -->
                <div class="form-group mt-2 mb-0">
                  <button type="button" class="btn btn-success px-4 py-2 font-weight-bold me-2 text-white" id="submit_fund">
                    <i class="mdi mdi-content-save border-0"></i> Save Deposit
                  </button>
                  <button type="button" class="btn btn-outline-light px-4 py-2 text-white" id="reset_fund">
                    <i class="mdi mdi-refresh border-0"></i> Reset
                  </button>
                </div>

              </div>

              <!-- ================= RIGHT COLUMN: QR / BANK SLIP ================= -->
              <div class="col-lg-6 border-left d-flex flex-column justify-content-center align-items-center text-center mt-4 mt-lg-0">
                <h6 class="font-weight-bold text-white mb-3">Scan QR / Bank Details</h6>

                <div class="p-2 border rounded bg-dark shadow-sm d-inline-block">
                  <?php if ($qr): ?>
    <img src="<?= esc($qr['qr_image_url']) ?>"
         alt="<?= esc($qr['qr_name']) ?>"
         class="img-fluid rounded"
         style="max-height: 200px;">
<?php else: ?>
    <p>No QR Code available.</p>
<?php endif; ?>
                </div>

                <small class="text-light mt-3 d-block font-weight-bold">
                  <i class="mdi mdi-information-outline text-warning"></i> Transfer the exact amount to the details above and enter the UTR generated.
                </small>
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

    // ================= ENHANCED RESET LOGIC =================
    $('#reset_fund').click(function() {
        // Clear text inputs
        $('#fund_amount').val('');
        $('#utr').val('');
        
        $('#fund_amount_error, #utr_error').html('');
        $('#fund_amount, #utr').removeClass('is-invalid border-danger');
        
        $('#deposit_success_banner, #deposit_error_banner').addClass('d-none').html('');
    });

    // ================= SUBMIT FUND DEPOSIT =================
    $('#submit_fund').click(function(e) {
        e.preventDefault();

        var fundAmount = $('#fund_amount').val().trim();
        var utr        = $('#utr').val().trim();
        var btn        = $(this);
        var isValid    = true;

        $('#fund_amount_error, #utr_error').html('');
        $('#fund_amount, #utr').removeClass('is-invalid border-danger');
        $('#deposit_success_banner, #deposit_error_banner').addClass('d-none').html('');

        if (fundAmount === '' || isNaN(fundAmount) || parseFloat(fundAmount) <= 0) {
            $('#fund_amount_error').html('Please enter a valid amount greater than ₹0.');
            $('#fund_amount').addClass('is-invalid border-danger');
            isValid = false;
        }

        if(parseFloat(fundAmount) <= 1000){
          $('#fund_amount_error').html('Please enter a amount greater than ₹1000.');
            $('#fund_amount').addClass('is-invalid border-danger');
            isValid = false;
        }

        if (utr === '') {
            $('#utr_error').html('Please enter the UTR / Reference number.');
            $('#utr').addClass('is-invalid border-danger');
            isValid = false;
        } else if (utr.length < 8) {
            $('#utr_error').html('UTR number seems too short. Please check your bank receipt.');
            $('#utr').addClass('is-invalid border-danger');
            isValid = false;
        }

        if (!isValid) {
            return;
        }

        // Show processing state
        var originalBtnText = btn.html();
        btn.html('<i class="mdi mdi-loading mdi-spin"></i> Processing...').prop('disabled', true);

        var formData = $('#fundDepositForm').serialize();

        $.ajax({
            url: "<?= site_url('user/submit_fund_deposit') ?>",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                btn.html(originalBtnText).prop('disabled', false);

                // Dynamically update CSRF token hash to prevent form expiration
                if (response.csrf_hash) {
                    $('#csrf_token_input').val(response.csrf_hash);
                }

                // ================= SERVER VALIDATION FAILURE OR EXCEPTION =================
                if (response.Resp_code === 'ERR') {
                    
                    // 1. Specific field validation errors from CI4
                    if (response.errors && typeof response.errors === 'object') {
                        $.each(response.errors, function(field, errorText) {
                            $('#' + field + '_error').html(errorText);
                            $('#' + field).addClass('is-invalid border-danger');
                        });
                    } 
                    // 2. General failure (e.g., Session expired or Database error)
                    else if (response.Resp_desc) {
                        $('#deposit_error_banner').removeClass('d-none').html('❌ ' + response.Resp_desc);
                        $('html, body').animate({ scrollTop: $('#deposit_error_banner').offset().top - 100 }, 300);
                    }
                } 
                // ================= INSERTION WAS SUCCESSFUL =================
                else if (response.Resp_code === 'RCS') {
    
                    // Display green banner including the generated unique ID
                    $('#deposit_success_banner')
                        .removeClass('d-none')
                        .html('✅ ' + response.Resp_desc + '<br><strong>Transaction Reference ID: ' + response.data.transaction_id + '</strong>');

                    // Reset inputs
                    $('#fund_amount').val('');
                    $('#utr').val('');

                    $('html, body').animate({ scrollTop: $('#deposit_success_banner').offset().top - 100 }, 500);
                }
            },
            error: function() {
                btn.html(originalBtnText).prop('disabled', false);
                
                $('#deposit_error_banner')
                    .removeClass('d-none')
                    .html('❌ A server error occurred while processing your request. Please try again later.');
                
                $('html, body').animate({ scrollTop: $('#deposit_error_banner').offset().top - 100 }, 300);
            }
        });
    });

});
</script>