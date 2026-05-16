<?php
$data = getuserdata();
?>
<div class="container-scroller">
    <?= $this->include('user/layout/sidebar') ?>
    <div class="container-fluid page-body-wrapper">
    <nav class="navbar p-0 fixed-top d-flex flex-row">
        <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
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
                <div class="dropdown-divider"></div>
                <p class="p-3 mb-0 text-center">Advanced settings</p>
            </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-format-line-spacing"></span>
        </button>
        </div>
    </nav>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <table id="user_transaction_tbl" class="display" style="width:100%">
                </table>
                <div id="edit_div"></div>
            </div>
        </div>
        <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <!-- <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2020</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin templates</a> from Bootstrapdash.com</span> -->
        </div>
        </footer>
    </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Flatpickr Datepicker CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Flatpickr Datepicker JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() { 
        $('#user_transaction_tbl').DataTable({
            processing: true,
            serverSide: false, 
            
            ajax: {
                url: "<?= site_url('user/withdraw_history_tbl') ?>",
                type: "GET",
                dataSrc: function(json) {
                    console.log('API Response:', json);
                    return json.data;
                }
            },
            
            columns: [
                // 1. Row Index
                { 
                    data: null,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1; 
                    }
                },
                
                // 2. User ID
                { 
                    data: "user_id",
                    title: "User ID"
                },
                
                // 3. Fund Amount
                { 
                    data: "req_amt",
                    title: "Req. Amount",
                    render: function(data) {
                        return data ? '₹' + data : '<span class="text-muted">None</span>';
                    }
                },
                
                // 4. UTR (👉 FIXED: Changed uppercase UTR to lowercase utr)
                { 
                    data: "charged_amt",
                    title: "Charged Amount",
                },

                // 5. Portal Txn ID
                {
                    data: "paid_amt",
                    title: "Paid Amount",
                },
                
                // 6. Date
                { 
                    data: "created_at",
                    title: "Payout Date",
                    render: function(data) {
                        if (!data) return '-';
                        var date = new Date(data);
                        return date.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                },
                
                // 7. Status (👉 FIXED: Added dynamic background colors for visibility)
                { 
                    data: "status",
                    title: "Status",
                    render: function(data) {
                        if (!data) {
                            return '-';
                        }

                        return data.charAt(0).toUpperCase() + data.slice(1).toLowerCase();
                    }   
                },
            ],

            buttons: [
                {
                    extend: 'csv',
                    text: '📥 Download CSV',
                    className: 'btn btn-success btn-sm', // Bootstrap classes
                    titleAttr: 'Download as CSV' // Tooltip
                },
                {
                    extend: 'print',
                    text: '🖨️ Print Table',
                    className: 'btn btn-primary btn-sm'
                }
            ],
            
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            
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
    });
</script>