<?php
$data = getuserdata();
?>
<div class="container-scroller">
  <!-- SIDEBAR -->
  <?= $this->include('admin/layout/sidebar') ?>

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
              <i class="mdi mdi-wallet-plus text-success me-2"></i> Add Qr Code
            </h4>
          </div>

          <!-- FORM START -->
          <form id="qrUploadForm" method="post" enctype="multipart/form-data">
    <!-- CSRF Token -->
    <input type="hidden"
           name="<?= csrf_token() ?>"
           value="<?= csrf_hash() ?>"
           id="csrf_token_input">

    <div class="row">

        <!-- ================= LEFT COLUMN: INPUTS ================= -->
        <div class="col-lg-6 pr-lg-4">

            <!-- QR Code Name -->
            <div class="form-group mb-3">
                <label class="font-weight-bold text-white">
                    QR Code Name <span class="text-danger">*</span>
                </label>
                <input type="text"
                       class="form-control p_input text-white"
                       id="qr_name"
                       name="qr_name"
                       placeholder="Enter QR Code Name (e.g. PhonePe QR)">
                <div class="error-message text-danger small mt-1 font-weight-bold"
                     id="qr_name_error"></div>
            </div>

            <!-- File Upload -->
            <div class="form-group mb-3">
                <label class="font-weight-bold text-white">
                    Upload QR Image <span class="text-danger">*</span>
                </label>
                <input type="file"
                       class="form-control p_input text-white"
                       id="qr_image"
                       name="qr_image"
                       accept="image/png,image/jpeg,image/jpg,image/webp">
                <div class="error-message text-danger small mt-1 font-weight-bold"
                     id="qr_image_error"></div>
            </div>

            <!-- Buttons -->
            <div class="form-group mt-4 mb-0">
                <button type="button"
                        class="btn btn-success px-4 py-2 font-weight-bold me-2 text-white"
                        id="upload_qr_btn">
                    <i class="mdi mdi-upload"></i> Upload QR Code
                </button>

                <button type="reset"
                        class="btn btn-outline-light px-4 py-2 text-white"
                        id="reset_qr_btn">
                    <i class="mdi mdi-refresh"></i> Reset
                </button>
            </div>

        </div>

        <!-- ================= RIGHT COLUMN: IMAGE PREVIEW ================= -->
        <div class="col-lg-6 border-left d-flex flex-column justify-content-center align-items-center text-center mt-4 mt-lg-0">

            <h6 class="font-weight-bold text-white mb-3">
                QR Code Preview
            </h6>

            <div class="p-3 border rounded bg-dark shadow-sm d-inline-block"
                 id="preview_container"
                 style="min-height: 220px; min-width: 220px; display:flex; align-items:center; justify-content:center;">

                <?php if (!empty($qr_code_url)): ?>
                    <img src="<?= $qr_code_url ?>"
                         alt="QR Code"
                         class="img-fluid rounded"
                         id="preview_image"
                         style="max-height: 200px;">
                <?php else: ?>
                    <div id="preview_placeholder" class="text-muted">
                        <i class="mdi mdi-image-outline" style="font-size: 50px;"></i>
                        <p class="mb-0 mt-2">No image selected</p>
                    </div>
                    <img src=""
                         alt="QR Preview"
                         class="img-fluid rounded d-none"
                         id="preview_image"
                         style="max-height: 200px;">
                <?php endif; ?>

            </div>

            <small class="text-light mt-3 d-block font-weight-bold">
                <i class="mdi mdi-information-outline text-warning"></i>
                Allowed formats: JPG, JPEG, PNG, WEBP
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
$('#upload_qr_btn').on('click', function () {
    $('.error-message').html('');

    let form = document.getElementById('qrUploadForm');
    let formData = new FormData(form);

    $.ajax({
        url: "<?= base_url('admin/save_qr_code') ?>",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",

        success: function (response) {
            // Update CSRF token if returned
            if (response.csrf_hash) {
                $('#csrf_token_input').val(response.csrf_hash);
            }

            if (response.Resp_code === 'RCS') {
                alert(response.Resp_desc);

                // Update preview with saved image URL
                if (response.data.qr_image_url) {
                    $('#preview_placeholder').addClass('d-none');
                    $('#preview_image')
                        .attr('src', response.data.qr_image_url + '?t=' + new Date().getTime())
                        .removeClass('d-none');
                }

                // Reset text/file inputs
                $('#qrUploadForm')[0].reset();
            } else {
                if (response.data && typeof response.data === 'object') {
                    $.each(response.data, function (key, value) {
                        $('#' + key + '_error').html(value);
                    });
                } else {
                    alert(response.Resp_desc);
                }
            }
        },

        error: function (xhr) {
            alert('Something went wrong while uploading QR code.');
            console.log(xhr.responseText);
        }
    });
});
// Reset Form
$('#reset_qr_btn').on('click', function () {
    $('#preview_image')
        .attr('src', '')
        .addClass('d-none');

    $('#preview_placeholder').removeClass('d-none');
    $('.error-message').html('');
});


});
</script>