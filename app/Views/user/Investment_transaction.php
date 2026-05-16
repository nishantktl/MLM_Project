<?php
$data = getuserdata();
?>
<div class="container-scroller">
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="index.html"><img src="assets/images/logo.svg" alt="logo" /></a>
        <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
    </div>
    <ul class="nav">
        <li class="nav-item profile">
            <div class="profile-desc">
                <div class="profile-pic">
                <div class="count-indicator">
                    <img class="img-xs rounded-circle " src="assets/images/faces/face15.jpg" alt="">
                    <span class="count bg-success"></span>
                </div>
                <div class="profile-name">
                    <h5 class="mb-0 font-weight-normal"><?= isset($data['username']) ? $data['username'] : 'User'; ?></h5>
                    <span>Gold Member</span>
                </div>
                </div>
                <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
                <a href="#" class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                        <i class="mdi mdi-settings text-primary"></i>
                    </div>
                    </div>
                    <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                        <i class="mdi mdi-onepassword  text-info"></i>
                    </div>
                    </div>
                    <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                        <i class="mdi mdi-calendar-today text-success"></i>
                    </div>
                    </div>
                    <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1 text-small">To-do list</p>
                    </div>
                </a>
                </div>
            </div>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">Navigation</span>
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
    <div class="container-fluid page-body-wrapper">
    <nav class="navbar p-0 fixed-top d-flex flex-row">
        <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav w-100">
            <li class="nav-item w-100">
            <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
                <input type="text" class="form-control" placeholder="Search products">
            </form>
            </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown d-none d-lg-block">
            <a class="nav-link btn btn-success create-new-button" id="createbuttonDropdown" data-toggle="dropdown" aria-expanded="false" href="#">+ Create New Project</a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="createbuttonDropdown">
                <h6 class="p-3 mb-0">Projects</h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-file-outline text-primary"></i>
                    </div>
                </div>
                <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1">Software Development</p>
                </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-web text-info"></i>
                    </div>
                </div>
                <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1">UI Development</p>
                </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-layers text-danger"></i>
                    </div>
                </div>
                <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1">Software Testing</p>
                </div>
                </a>
                <div class="dropdown-divider"></div>
                <p class="p-3 mb-0 text-center">See all projects</p>
            </div>
            </li>
            <li class="nav-item nav-settings d-none d-lg-block">
            <a class="nav-link" href="#">
                <i class="mdi mdi-view-grid"></i>
            </a>
            </li>
            <li class="nav-item dropdown border-left">
            <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-email"></i>
                <span class="count bg-success"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                <h6 class="p-3 mb-0">Messages</h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <img src="<?= base_url('assets/images/faces/face4.jpg') ?>" alt="image" class="rounded-circle profile-pic">
                </div>
                <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1">Mark send you a message</p>
                    <p class="text-muted mb-0"> 1 Minutes ago </p>
                </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <img src="<?= base_url('assets/images/faces/face2.jpg') ?>" alt="image" class="rounded-circle profile-pic">
                </div>
                <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1">Cregh send you a message</p>
                    <p class="text-muted mb-0"> 15 Minutes ago </p>
                </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <img src="<?= base_url('assets/images/faces/face3.jpg') ?>" alt="image" class="rounded-circle profile-pic">
                </div>
                <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1">Profile picture updated</p>
                    <p class="text-muted mb-0"> 18 Minutes ago </p>
                </div>
                </a>
                <div class="dropdown-divider"></div>
                <p class="p-3 mb-0 text-center">4 new messages</p>
            </div>
            </li>
            <li class="nav-item dropdown border-left">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell"></i>
                <span class="count bg-danger"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <h6 class="p-3 mb-0">Notifications</h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-calendar text-success"></i>
                    </div>
                </div>
                <div class="preview-item-content">
                    <p class="preview-subject mb-1">Event today</p>
                    <p class="text-muted ellipsis mb-0"> Just a reminder that you have an event today </p>
                </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-settings text-danger"></i>
                    </div>
                </div>
                <div class="preview-item-content">
                    <p class="preview-subject mb-1">Settings</p>
                    <p class="text-muted ellipsis mb-0"> Update dashboard </p>
                </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-link-variant text-warning"></i>
                    </div>
                </div>
                <div class="preview-item-content">
                    <p class="preview-subject mb-1">Launch Admin</p>
                    <p class="text-muted ellipsis mb-0"> New admin wow! </p>
                </div>
                </a>
                <div class="dropdown-divider"></div>
                <p class="p-3 mb-0 text-center">See all notifications</p>
            </div>
            </li>
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
                url: "<?= site_url('user/income_history_tbl') ?>",
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
                    data: "from_user_id",
                    title: "From User"
                },

                { 
                    data: "to_user_id",
                    title: "From User"
                },
                
                // 3. Fund Amount
                { 
                    data: "investment_amount",
                    title: "Investment Amount",
                    render: function(data) {
                        return data ? '₹' + data : '<span class="text-muted">--</span>';
                    }
                },
    
                
                {
                    data:"type",
                    title: "Type",
                    render: function(data) {
                        if (data === 'initial_topup') {
                            return 'Investment';
                        } else {
                            return 'Re-Topup';
                        }
                    }
                },
                // 6. Date
                { 
                    data: "created_at",
                    title: "Date",
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