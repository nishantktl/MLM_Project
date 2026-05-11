<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User Dashboard</title>
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
            <div class="card col-lg-6 mx-auto">
              <div class="card-body px-5 py-5">
                <h3 class="card-title text-left mb-3">User Dashboard</h3>
                <p>Welcome, <strong><?= esc(session()->get('username')) ?></strong></p>
                <p>Email: <?= esc(session()->get('email')) ?></p>
                <p>Phone: <?= esc(session()->get('phone')) ?></p>
                <p>Role: <?= esc(session()->get('role')) ?></p>
                <p>Parent ID: <?= esc(session()->get('parent_id')) ?></p>
                <div class="text-center mt-4">
                  <a href="<?= base_url('logout') ?>" class="btn btn-danger btn-block">Logout</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
