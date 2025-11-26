<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
  date_default_timezone_set("Asia/Manila");
  
  ob_start();
  $title = isset($_GET['page']) ? ucwords(str_replace("_", ' ', $_GET['page'])) : "Home";
  ?>
  <title><?php echo $title ?> | <?php echo $_SESSION['system']['name'] ?></title><?php ob_end_flush() ?>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- DataTables -->
  <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="assets/plugins/dropzone/min/dropzone.min.css">
  <!-- DateTimePicker -->
  <link rel="stylesheet" href="assets/dist/css/jquery.datetimepicker.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Switch Toggle -->
  <link rel="stylesheet" href="assets/plugins/bootstrap4-toggle/css/bootstrap4-toggle.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="assets/dist/css/styles.css">
	<script src="assets/plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- summernote -->
  <link rel="stylesheet" href="assets/plugins/summernote/summernote-bs4.min.css">
  <!-- Dark mode styles -->
  <style>
    body.dark-mode {
      background-color: #121212 !important;
      color: #e0e0e0 !important;
    }
    body.dark-mode .main-header.navbar,
    body.dark-mode .main-sidebar,
    body.dark-mode .control-sidebar,
    body.dark-mode .card,
    body.dark-mode .callout,
    body.dark-mode .content-wrapper,
    body.dark-mode .content,
    body.dark-mode .table,
    body.dark-mode .modal-content,
    body.dark-mode .dropdown-menu {
      background-color: #1e1e1e !important;
      color: #e0e0e0 !important;
      /* border-color: #2a2a2a !important; */
    }
    body.dark-mode .card a,
    body.dark-mode a,
    body.dark-mode p,
    body.dark-mode label,
    body.dark-mode dt,
    body.dark-mode dd {
      color: #e0e0e0 !important;
    }
    body.dark-mode .badge {
      opacity: 0.95;
    }
    body.dark-mode .table thead th {
      background-color: #222 !important;
      color: #e0e0e0 !important;
    }
    body.dark-mode .form-control {
      background-color: #222 !important;
      color: #e0e0e0 !important;
      border-color: #333 !important;
    }
    body.dark-mode .btn {
      border-color: #333 !important;
    }
    /* Select styling for dark mode */
    body.dark-mode select,
    body.dark-mode .custom-select,
    body.dark-mode .custom-select.custom-select-sm {
      background-color: #1e1e1e !important;
      color: #e0e0e0 !important;
      border-color: #333 !important;
      -webkit-appearance: none !important;
      -moz-appearance: none !important;
      appearance: none !important;
    }
    /* Options in dropdowns (may not affect native OS dropdown in all browsers) */
    body.dark-mode select option,
    body.dark-mode .custom-select option {
      background-color: #1e1e1e !important;
      color: #e0e0e0 !important;
    }
    /* Select2 (bootstrap4 theme) styling */
    body.dark-mode .select2-container--bootstrap4 .select2-selection {
      background: #1e1e1e !important;
      color: #e0e0e0 !important;
      border-color: #333 !important;
    }
    body.dark-mode .select2-container--bootstrap4 .select2-selection__rendered {
      color: #e0e0e0 !important;
    }
    body.dark-mode .select2-dropdown,
    body.dark-mode .select2-container--default .select2-results__option--highlighted {
      background: #1a1a1a !important;
      color: #e0e0e0 !important;
    }
    /* Small box (dashboard count) */
    body.dark-mode .small-box,
    body.dark-mode .small-box.bg-light {
      background-color: #1d1d1d !important;
      color: #e0e0e0 !important;
      border-color: #2a2a2a !important;
      box-shadow: none !important;
    }
    body.dark-mode .small-box .inner h3,
    body.dark-mode .small-box .inner p {
      color: #e0e0e0 !important;
    }
    body.dark-mode .small-box .icon {
      color: #bdbdbd !important;
    }
    /* Buttons and dropdowns */
    body.dark-mode .btn,
    body.dark-mode .btn-default,
    body.dark-mode .btn-flat,
    body.dark-mode .btn-block {
      background-color: #2a2a2a !important;
      color: #e0e0e0 !important;
      border-color: #3a3a3a !important;
    }
    body.dark-mode .btn-primary {
      background-color: #0b5ed7 !important;
      border-color: #0a58ca !important;
      color: #fff !important;
    }
    body.dark-mode .btn-default.dropdown-toggle,
    body.dark-mode .dropdown-toggle {
      background-color: #2a2a2a !important;
      color: #e0e0e0 !important;
      border-color: #3a3a3a !important;
    }
    body.dark-mode .dropdown-menu {
      background-color: #1f1f1f !important;
      color: #e0e0e0 !important;
      border-color: #2a2a2a !important;
    }
    body.dark-mode .dropdown-item {
      color: #e0e0e0 !important;
    }
    body.dark-mode .dropdown-item:hover {
      background-color: #2a2a2a !important;
      color: #fff !important;
    }
    /* DataTables pagination buttons */
    body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button {
      background: #2a2a2a !important;
      border: 1px solid #353535 !important;
      color: #e0e0e0 !important;
    }
    body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
      background: #343434 !important;
      color: #fff !important;
    }
    /* More specific overrides to catch bootstrap-style pagination and disabled states */
    body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button,
    body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button a,
    body.dark-mode .dataTables_wrapper .dataTables_paginate ul.pagination li.page-item .page-link {
      background: #2a2a2a !important;
      color: #e0e0e0 !important;
      border: 1px solid #353535 !important;
    }
    body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    body.dark-mode .dataTables_wrapper .dataTables_paginate ul.pagination li.page-item.disabled .page-link {
      background: #2a2a2a !important;
      color: #9a9a9a !important;
      opacity: 0.7;
    }
    body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    body.dark-mode .dataTables_wrapper .dataTables_paginate ul.pagination li.page-item.active .page-link {
      background: #0b5ed7 !important;
      color: #fff !important;
      border-color: #0a58ca !important;
    }

    /* Table contrast improvements for dark mode */
    body.dark-mode .card {
      background-color: #141414 !important;
      border-color: #1f1f1f !important;
    }
    body.dark-mode .card .card-header {
      background-color: #171717 !important;
      border-bottom: 1px solid #262626 !important;
      color: #e6e6e6 !important;
    }
    body.dark-mode .card .card-body {
      background-color: #0f0f0f !important;
      color: #dcdcdc !important;
    }
    body.dark-mode .table {
      background-color: transparent !important;
      color: #e6e6e6 !important;
    }
    body.dark-mode .table thead th {
      background-color: #1f1f1f !important;
      color: #e6e6e6 !important;
      border-bottom: 2px solid #2b2b2b !important;
    }
    body.dark-mode .table tbody td {
      border-bottom: 1px solid #222 !important;
      color: #dcdcdc !important;
      background-color: transparent !important;
    }
    body.dark-mode .table-hover tbody tr:hover {
      background-color: #222 !important;
      color: #fff !important;
    }
    body.dark-mode .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(255,255,255,0.01) !important;
    }
    /* DataTables controls (search / length) */
    body.dark-mode .dataTables_wrapper .dataTables_filter input,
    body.dark-mode .dataTables_wrapper .dataTables_length select,
    body.dark-mode .dataTables_wrapper .dataTables_length .custom-select {
      background-color: #1b1b1b !important;
      color: #e6e6e6 !important;
      border: 1px solid #333 !important;
    }
    /* SweetAlert2 (modal popup) dark mode overrides */
    body.dark-mode .swal2-popup {
      background: #1e1e1e !important;
      color: #e6e6e6 !important;
      border: 1px solid #2b2b2b !important;
      box-shadow: none !important;
    }
    body.dark-mode .swal2-title,
    body.dark-mode .swal2-html-container,
    body.dark-mode .swal2-content {
      color: #e6e6e6 !important;
    }
    body.dark-mode .swal2-html-container pre {
      background: transparent !important;
      color: #e6e6e6 !important;
      white-space: pre-wrap !important;
    }
    body.dark-mode .swal2-styled {
      background: #2a2a2a !important;
      color: #e6e6e6 !important;
      border: 1px solid #333 !important;
    }
    body.dark-mode .swal2-confirm {
      background: #0b5ed7 !important;
      border-color: #0a58ca !important;
      color: #fff !important;
    }
    body.dark-mode .swal2-cancel {
      background: #2a2a2a !important;
      color: #e0e0e0 !important;
      border-color: #333 !important;
    }
    body.dark-mode .swal2-icon {
      filter: none !important;
    }
    body.dark-mode .swal2-backdrop-show {
      background-color: rgba(0,0,0,0.6) !important;
    }
    body.dark-mode .main-footer,
    body.dark-mode footer {
      background-color: #141414 !important;
      color: #cfcfcf !important;
      border-top: 1px solid #2a2a2a !important;
    }
    body.dark-mode .main-footer a,
    body.dark-mode footer a {
      color: #e0e0e0 !important;
    }
  </style>
  
</head>