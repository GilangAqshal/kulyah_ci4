<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Application Perpustakaan</title>

    <link href="/Assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Assets/css/datepicker3.css" rel="stylesheet">
    <link href="/Assets/css/styles.css" rel="stylesheet">
    <link href="/Assets/css/sweetalert2.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">Log in</div>
                <div class="panel-body">
                    <!-- PERBAIKAN: Menambahkan tanda petik penutup pada method="post" -->
                    <!-- PERBAIKAN: Sesuaikan action dengan nama fungsi di Controller (autentikasi) -->
                    <form role="form" action="<?= base_url('admin/autentikasi_login'); ?>" method="post">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="Username" name="username" type="text" autofocus="" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" required>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Load Scripts -->
    <script src="/Assets/js/jquery-1.11.1.min.js"></script>
    <script src="/Assets/js/bootstrap.min.js"></script>
    <script src="/Assets/js/bootstrap-datepicker.js"></script>
    <script src="/Assets/js/sweetalert2.min.js"></script>

    <script>
        // Script untuk UI Sidebar (jika diperlukan)
        !function ($) {
            $(document).on("click", "ul.nav li.parent > a > span.icon", function() {        
                $(this).find('em:first').toggleClass("glyphicon-minus");      
            }); 
            $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
        }(window.jQuery);

        $(window).on('resize', function () {
            if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
            if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
        });

        // PERBAIKAN: SweetAlert ditempatkan di luar blok script UI agar tidak bentrok
        // Menggunakan session() helper CI4 untuk mengambil flashdata
    </script>

    <!-- Notifikasi SweetAlert -->
    <?php if (session()->getFlashdata('success')) : ?>
        <script>
            swal("Success!", "<?= session()->getFlashdata('success') ?>", "success");
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <script>
            swal("Sorry!", "<?= session()->getFlashdata('error') ?>", "error");
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('warning')) : ?>
        <script>
            swal("Warning!", "<?= session()->getFlashdata('warning') ?>", "warning");
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('info')) : ?>
        <script>
            swal("Info!", "<?= session()->getFlashdata('info') ?>", "info");
        </script>
    <?php endif; ?>
</body>
</html>
