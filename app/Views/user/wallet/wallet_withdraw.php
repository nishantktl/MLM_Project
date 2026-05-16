<?php
$data = getuserdata();

?>
<div class="container-scroller">
  <!-- SIDEBAR -->
  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
      <a class="sidebar-brand brand-logo" href="index.html"><img src="assets/images/logo.svg" alt="logo" /></a>
      <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
    </div>
    <ul class="nav">
        <li class="nav-item profile">
          <div class="profile-desc">
            <div class="profile-pic">
              <div class="profile-name">
                <h5 class="mb-0 font-weight-normal"><?= isset($data['username']) ? $data['username'] : 'User'; ?></h5>
              </div>
            </div>
          </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="<?= base_url('dashboard') ?>">
            <span class="menu-icon"><i class="mdi mdi-speedometer"></i></span>
            <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-icon"><i class="mdi mdi-laptop"></i></span>
            <span class="menu-title">Manage Fund</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('fund_request') ?>">Deposit Fund</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('deposit_history') ?>">Deposit History</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('p2p_transfer') ?>">P2P Transfer</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('p2p_receive') ?>">P2P Receive</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('income_to_purchase_wallet') ?>">Income To Purchase Wallet</a></li>
            </ul>
            </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#trade" aria-expanded="false" aria-controls="trade">
            <span class="menu-icon"><i class="mdi mdi-laptop"></i></span>
            <span class="menu-title">User Trading</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="trade">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('start_trade') ?>">Start Trade</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('re_trade') ?>">Re-Trade</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('trade_history') ?>">Trade History</a></li>
            </ul>
            </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#withdraw_balance" aria-expanded="false" aria-controls="withdraw_balance">
            <span class="menu-icon"><i class="mdi mdi-laptop"></i></span>
            <span class="menu-title">Withdraw</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="withdraw_balance">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('wallet_withdraw') ?>">Wallet Withdraw</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('withdraw_report') ?>">Withdraw Request</a></li>            
            </ul>
            </div>
        </li>
    </ul>
  </nav>

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
              <i class="mdi mdi-wallet-plus text-success me-2"></i> Fund Withdraw Request
            </h4>
          </div>

          <!-- FORM START -->
          <form id="withdrawalForm">
            <!-- CI4 CSRF Token -->
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token_input">

            <div class="row">
              <div class="col-lg-12 pr-lg-4">

                <!-- Total Income -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-white">Total Income</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white border-right-0 font-weight-bold">₹</span>
                        </div>
                        <input
                            type="text"
                            class="form-control p_input text-white border-left-0"
                            id="total_income"
                            name="total_income"
                            value="<?= esc(number_format($income_balance ?? 0, 2)) ?>"
                            readonly
                            style="background-color:#2A3038; color:#ffffff; opacity:1;"
                        >
                    </div>
                </div>

                <!-- Withdraw Income -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-white">Withdraw Income</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white border-right-0 font-weight-bold">₹</span>
                        </div>
                        <input
                            type="text"
                            class="form-control p_input text-white border-left-0"
                            id="withdraw_income"
                            name="withdraw_income"
                            value="<?= esc(number_format($withdrawal_balance ?? 0, 2)) ?>"
                            readonly
                            style="background-color:#2A3038; color:#ffffff; opacity:1;"
                        >
                    </div>
                </div>

                <!-- Net Balance Amount -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-white">Net Balance Amount</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white border-right-0 font-weight-bold">₹</span>
                        </div>
                        <input
                            type="text"
                            class="form-control p_input text-white border-left-0"
                            id="net_balance_amount"
                            name="net_balance_amount"
                            value="<?= esc(number_format(($income_balance ?? 0) - ($withdrawal_balance ?? 0), 2)) ?>"
                            readonly
                            style="background-color:#2A3038; color:#ffffff; opacity:1;"
                        >
                    </div>
                </div>

                <!-- Withdraw Amount -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-white">
                        Withdraw Amount <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white border-right-0 font-weight-bold">₹</span>
                        </div>
                        <input
                            type="text"
                            class="form-control p_input text-white border-left-0"
                            id="withdraw_amount"
                            name="withdraw_amount"
                            placeholder="Enter withdrawal amount"
                        >
                    </div>
                    <div class="error-message text-danger small mt-1 font-weight-bold"
                        id="withdraw_amount_error"></div>

                    <!-- Note -->
                    <small class="text-warning d-block mt-2 font-weight-bold">
                        <i class="mdi mdi-information-outline"></i>
                        You will be charged 10% of the withdrawal amount as processing fees.
                    </small>
                </div>

                <!-- Charged Amount (10%) -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-white">Charged Amount (10%)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white border-right-0 font-weight-bold">₹</span>
                        </div>
                        <input
                            type="text"
                            class="form-control p_input text-white border-left-0"
                            id="charged_amount"
                            name="charged_amount"
                            value="0.00"
                            readonly
                            style="background-color:#2A3038; color:#ffffff; opacity:1;"
                        >
                    </div>
                </div>

                <!-- Net Amount -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-white">Net Amount</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white border-right-0 font-weight-bold">₹</span>
                        </div>
                        <input
                            type="text"
                            class="form-control p_input text-white border-left-0"
                            id="net_amount"
                            name="net_amount"
                            value="0.00"
                            readonly
                            style="background-color:#2A3038; color:#ffffff; opacity:1;"
                        >
                    </div>
                </div>

                <!-- Transaction PIN -->
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-white">
                        Transaction PIN <span class="text-danger">*</span>
                    </label>
                    <input
                        type="password"
                        class="form-control p_input text-white"
                        id="transaction_pin"
                        name="transaction_pin"
                        placeholder="Enter transaction PIN"
                        maxlength="6"
                    >
                    <div class="error-message text-danger small mt-1 font-weight-bold"
                        id="transaction_pin_error"></div>
                </div>

                <!-- Buttons -->
                <div class="form-group mt-2 mb-0">
                    <button
                        type="button"
                        class="btn btn-success px-4 py-2 font-weight-bold me-2 text-white"
                        id="submit_withdrawal"
                    >
                        <i class="mdi mdi-content-save border-0"></i> Submit Withdrawal
                    </button>

                    <button
                        type="button"
                        class="btn btn-outline-light px-4 py-2 text-white"
                        id="reset_withdrawal"
                    >
                        <i class="mdi mdi-refresh border-0"></i> Reset
                    </button>
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

    $('#withdraw_amount').on('keyup change', function () {
        let withdrawAmount = parseFloat($(this).val()) || 0;

        // 10% processing fee
        let chargedAmount = withdrawAmount * 0.10;

        // Net amount after deduction
        let netAmount = withdrawAmount - chargedAmount;

        $('#charged_amount').val(chargedAmount.toFixed(2));
        $('#net_amount').val(netAmount.toFixed(2));
    });

    $('#reset_withdrawal').on('click', function () {
        $('#withdraw_amount').val('');
        $('#charged_amount').val('0.00');
        $('#net_amount').val('0.00');
        $('#transaction_pin').val('');
        $('.error-message').text('');
    });

    $('#submit_withdrawal').on('click', function () {
        $('.error-message').text('');

        let withdrawAmount = parseFloat($('#withdraw_amount').val()) || 0;
        let transactionPin = $('#transaction_pin').val().trim();

        let hasError = false;

        if (withdrawAmount <= 0) {
            $('#withdraw_amount_error').text('Please enter a valid withdrawal amount.');
            hasError = true;
        }

        if (transactionPin === '') {
            $('#transaction_pin_error').text('Transaction PIN is required.');
            hasError = true;
        }

        if (hasError) {
            return;
        }

        $.ajax({
            url: "<?= base_url('user/submit-withdrawal') ?>",
            type: "POST",
            data: $('#withdrawalForm').serialize(),
            dataType: "json",
            beforeSend: function () {
                $('#submit_withdrawal').prop('disabled', true).text('Processing...');
            },
            success: function (response) {
                // Update CSRF token
                $('#csrf_token_input').val(response.csrf_hash);

                if (response.Resp_code === 'SUCCESS') {
                    $('#deposit_success_banner')
                        .removeClass('d-none')
                        .text(response.Resp_desc);

                    $('#deposit_error_banner').addClass('d-none');

                    // Update displayed balances
                    $('#withdraw_income').val(
                        parseFloat(
                            ($('#withdraw_income').val() || '0').replace(/,/g, '')
                        ) + parseFloat(response.data.withdraw_amount)
                    );

                    $('#net_balance_amount').val(
                        parseFloat(response.data.remaining_balance).toFixed(2)
                    );

                    // Reset form
                    $('#withdraw_amount').val('');
                    $('#charged_amount').val('0.00');
                    $('#net_amount').val('0.00');
                    $('#transaction_pin').val('');
                } else {
                    $('#deposit_error_banner')
                        .removeClass('d-none')
                        .text(response.Resp_desc);

                    $('#deposit_success_banner').addClass('d-none');
                }
            },
            error: function () {
                $('#deposit_error_banner')
                    .removeClass('d-none')
                    .text('Unable to process request.');
            },
            complete: function () {
                $('#submit_withdrawal')
                    .prop('disabled', false)
                    .html('<i class="mdi mdi-content-save border-0"></i> Submit Withdrawal');
            }
        });
    });

});
</script>