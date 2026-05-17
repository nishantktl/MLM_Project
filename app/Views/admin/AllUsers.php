<?php
$data = getuserdata();
?>
<div class="container-scroller">
    <?= $this->include('admin/layout/sidebar') ?>
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
                    <table id="usersTable" class="display" style="width:100%">
                    </table>
                    <div id="edit_div" style="display:none"></div>
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
        $('#usersTable').DataTable({
            processing: true,
            serverSide: false, 
            
            ajax: {
                url: "<?= site_url('admin/get_user_list') ?>",
                type: "GET",
                dataSrc: function(json) {
                    console.log('API Response:', json);
                    return json.data.users;
                }
            },
            
            columns: [
                { 
                    data: null,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1; // Row index + 1
                    }
                },
                
                // 2. User ID
                { 
                    data: "user_id",
                    title: "User ID"
                },
                
                // 3. Sponsor ID (parent_id)
                { 
                    data: "parent_id",
                    title: "Sponsor ID",
                    render: function(data) {
                        return data ? data : '<span class="text-muted">None</span>';
                    }
                },
                
                // 4. Name (username)
                { 
                    data: "username",
                    title: "Name"
                },
                
                // 5. Mobile Number (phone)
                { 
                    data: "phone",
                    title: "Mobile Number",
                    render: function(data) {
                        return '<a href="tel:' + data + '">' + data + '</a>';
                    }
                },
                
                // 6. Registration Date (created_at)
                { 
                    data: "created_at",
                    title: "Reg. Date",
                    render: function(data) {
                        // Format date: 2026-05-09 09:49:35 → 09 May 2026
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
                
                // 7. Status
                { 
                    data: "status",
                    title: "Status",
                    render: function(data) {
                        
                        return '<span class="badge">' + data + '</span>';
                    }
                },
                
                // 8. Edit/Actions
                { 
                    data: "id",
                    title: "Actions",
                    orderable: false,
                    render: function(data, type, row) {
                        return `<button 
                            class="btn btn-sm btn-primary me-1 edit-user-btn"
                            data-id="${row.id}"
                            data-user-id="${row.user_id}">
                            Edit
                        </button>`;
                    }
                }
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

        // Edit button click
        $('#usersTable').on('click', '.edit-user-btn', function () {
            var userId = $(this).data('user-id');

            $.ajax({
                url: "<?= site_url('admin/get_user_details') ?>",
                type: "GET",
                data: {
                    user_id: userId
                },
                dataType: "json",
                success: function(response) {
                    if (response.Resp_code === 'RCS') {
                        var user = response.data;

                        function formatDateForInput(dateString) {
                            if (!dateString) return '';

                            // If stored as YYYY-MM-DD or YYYY-MM-DD HH:MM:SS
                            if (dateString.includes('-')) {
                                return dateString.substring(0, 10);
                            }

                            // If stored as MM/DD/YYYY
                            let parts = dateString.split('/');
                            if (parts.length === 3) {
                                return parts[2] + '-' + parts[0].padStart(2, '0') + '-' + parts[1].padStart(2, '0');
                            }

                            return '';
                        }
                        var html = `
                        <form id="edit_user_form">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            <input type="hidden" name="id" value="${user.id}">

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>User ID</label>
                                        <input type="text"
                                            class="form-control"
                                            value="${user.user_id || ''}"
                                            disabled  style="background-color: #2A3038; color: #ffffff; opacity: 1;">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sponsor ID</label>
                                        <input type="text"
                                            class="form-control"
                                            value="${user.parent_id || ''}"
                                            disabled  style="background-color: #2A3038; color: #ffffff; opacity: 1;">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Under Sponsor ID</label>
                                        <input type="text"
                                            name="under_sponsor_id"
                                            class="form-control"
                                            value="${user.parent_id || ''}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text"
                                            name="username"
                                            class="form-control"
                                            value="${user.username || ''}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Father Name</label>
                                        <input type="text"
                                            name="father_name"
                                            class="form-control"
                                            value="${user.father_name || ''}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="text"
                                            name="password"
                                            class="form-control"
                                            value="${user.password || ''}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mobile No.</label>
                                        <input type="text"
                                            name="phone"
                                            class="form-control"
                                            value="${user.phone || ''}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email"
                                            name="email"
                                            class="form-control"
                                            value="${user.email || ''}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date Of Birth</label>
                                        <input type="date"
                                            name="dob"
                                            class="form-control"
                                            value="${user.dob ? formatDateForInput(user.dob) : ''}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select name="gender" class="form-control">
                                            <option value="Male" ${user.gender === 'Male' ? 'selected' : ''}>Male</option>
                                            <option value="Female" ${user.gender === 'Female' ? 'selected' : ''}>Female</option>
                                            <option value="Other" ${user.gender === 'Other' ? 'selected' : ''}>Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea name="address"
                                                class="form-control"
                                                rows="4">${user.address || ''}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text"
                                            name="city"
                                            class="form-control"
                                            value="${user.city || ''}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State</label>
                                        <input type="text"
                                            name="state"
                                            class="form-control"
                                            value="${user.state || ''}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pincode</label>
                                        <input type="text"
                                            name="pincode"
                                            class="form-control"
                                            value="${user.pincode || ''}">
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-success">
                                        Update User
                                    </button>
                                   <button type="button" class="btn btn-secondary" id="backtotbl">
                                        Cancel
                                    </button>
                                </div>

                            </div>
                        </form>
                        `;

                        $('#edit_div').html(html).show();
                        $('#usersTable_wrapper').hide();

                        $('#backtotbl').click(function(e){
                            e.preventDefault();
                            $('#edit_div').html('').hide();
                            $('#usersTable_wrapper').show();
                        })
                        $('html, body').animate({
                            scrollTop: $('#edit_div').offset().top - 50
                        }, 500);
                    } else {
                        alert(response.Resp_desc);
                    }
                }
            });
        });

        // Update user form submit
        $(document).on('submit', '#edit_user_form', function (e) {
            e.preventDefault();

            $.ajax({
                url: "<?= site_url('admin/update_user_details') ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.Resp_code === 'RCS') {
                        alert(response.Resp_desc);
                        $('#backtotbl').trigger('click');
                        $('#usersTable').DataTable().ajax.reload(null, false);
                    } else {
                        alert(response.Resp_desc);
                    }
                },
                error: function () {
                    alert('Failed to update user.');
                }
            });
        });
    });
</script>