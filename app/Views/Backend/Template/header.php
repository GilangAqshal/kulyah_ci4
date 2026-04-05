<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $title ?? 'Dashboard' ?></title>

<link href="/Assets/css/bootstrap.min.css" rel="stylesheet">
<link href="/Assets/css/datepicker3.css" rel="stylesheet">
<link href="/Assets/css/bootstrap-table.css" rel="stylesheet">
<link href="/Assets/css/styles.css" rel="stylesheet">
<link href="/Assets/css/sweetalert2.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="#">
                <span>Perpus</span>Admin
            </a>

            <ul class="user-menu">
                <li class="dropdown pull-right">
                    
                    <!-- USER DROPDOWN -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-user"></span> 
                        <?= session()->get('nama') ?? 'Guest'; ?> 
                        <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="#">
                                <span class="glyphicon glyphicon-user"></span> Profile
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="glyphicon glyphicon-cog"></span> Settings
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/logout'); ?>">
                                <span class="glyphicon glyphicon-log-out"></span> Logout
                            </a>
                        </li>
                    </ul>

                </li>
            </ul>

        </div>
    </div>
</nav>

<script src="/Assets/js/jquery-1.11.1.min.js"></script>
<script src="/Assets/js/bootstrap.min.js"></script>