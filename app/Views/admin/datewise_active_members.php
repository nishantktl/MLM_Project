<?php
$data = getuserdata();
?>
<div class="container-scroller">
    <?= $this->include('admin/layout/sidebar') ?>
    <div class="container-fluid page-body-wrapper">
        <nav class="navbar p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
                <a class="navbar-brand brand-logo-mini" href="index.html">
                    <img src="assets/images/logo-mini.svg" alt="logo" />
                </a>
            </div>

            <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center"
                        type="button"
                        data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>

                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown">
                        <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                            <div class="navbar-profile">
                                <img class="img-xs rounded-circle"
                                     src="<?= base_url('assets/images/faces/face15.jpg') ?>"
                                     alt="">
                                <p class="mb-0 d-none d-sm-block navbar-profile-name">
                                    <?= isset($data['username']) ? $data['username'] : 'User'; ?>
                                </p>
                                <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                            </div>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                             aria-labelledby="profileDropdown">
                            <h6 class="p-3 mb-0">Profile</h6>
                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-settings text-success"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">Settings</p>
                                </div>
                            </a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item preview-item"
                               href="<?= base_url('logout') ?>">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-logout text-danger"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">Log out</p>
                                </div>
                            </a>

                            <div class="dropdown-divider"></div>
                            <p class="p-3 mb-0 text-center">Advanced settings</p>
                        </div>
                    </li>
                </ul>

                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                        type="button"
                        data-toggle="offcanvas">
                    <span class="mdi mdi-format-line-spacing"></span>
                </button>
            </div>
        </nav>

        <div class="main-panel">
            <div class="content-wrapper">

                <!-- Filter Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Active Users By Date</h4>

                        <form id="date_filter_form">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date"
                                               class="form-control"
                                               id="from_date"
                                               name="from_date">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date"
                                               class="form-control"
                                               id="to_date"
                                               name="to_date">
                                    </div>
                                </div>

                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit"
                                            class="btn btn-primary mr-2">
                                        Search
                                    </button>

                                    <button type="button"
                                            class="btn btn-secondary"
                                            id="reset_filter">
                                        Reset
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                <!-- DataTable -->
                <div class="row">
                    <table id="usersTable"
                           class="display"
                           style="width:100%">
                    </table>
                </div>

            </div>

            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                </div>
            </footer>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Flatpickr Datepicker CSS -->
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Flatpickr Datepicker JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
$(document).ready(function() {

    // Initialize DataTable (empty initially)
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: false,
        searching: false,
        paging: true,
        ordering: true,
        data: [],

        columns: [
            {
                data: null,
                title: "#",
                orderable: false,
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }
            },

            {
                data: "user_id",
                title: "User ID"
            },

            {
                data: "username",
                title: "Username"
            },

            {
                data: "phone",
                title: "Phone",
                render: function(data) {
                    return data
                        ? '<a href="tel:' + data + '">' + data + '</a>'
                        : '-';
                }
            },

            {
                data: "email",
                title: "Email"
            },

            {
                data: "created_at",
                title: "Created Date",
                render: function(data) {
                    if (!data) return '-';

                    var date = new Date(data);

                    return date.toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                }
            }
        ],

        buttons: [
            {
                extend: 'csv',
                text: '📥 Download CSV',
                className: 'btn btn-success btn-sm',
                titleAttr: 'Download as CSV'
            },
            {
                extend: 'print',
                text: '🖨️ Print Table',
                className: 'btn btn-primary btn-sm'
            }
        ],

        pageLength: 10,

        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"]
        ],

        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ users",
            infoEmpty: "No users found",
            infoFiltered: "(filtered from _MAX_ total users)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });

    // Search Form Submit
    $('#date_filter_form').submit(function(e) {
        e.preventDefault();

        var fromDate = $('#from_date').val();
        var toDate   = $('#to_date').val();

        if (fromDate === '' || toDate === '') {
            alert('Please select both From Date and To Date.');
            return;
        }

        $.ajax({
            url: "<?= site_url('admin/get_active_users_by_date') ?>",
            type: "POST",
            dataType: "json",
            data: {
                from_date: fromDate,
                to_date: toDate,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(response) {

                if (response.Resp_code === 'RCS') {
                    table.clear();
                    table.rows.add(response.data);
                    table.draw();
                } else {
                    alert(response.Resp_desc || 'No records found.');
                    table.clear().draw();
                }

                // Update CSRF token if returned by backend
                if (response.csrf_hash) {
                    $('input[name="<?= csrf_token() ?>"]').val(response.csrf_hash);
                }
            },
            error: function(xhr) {
                alert('Something went wrong.');
                table.clear().draw();
            }
        });
    });

    // Reset Button
    $('#reset_filter').click(function() {
        $('#from_date').val('');
        $('#to_date').val('');
        table.clear().draw();
    });

});
</script>