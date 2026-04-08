<script src="/Assets/js/jquery-1.11.1.min.js"></script>
<script src="/Assets/js/bootstrap.min.js"></script>
<script src="/Assets/js/chart.min.js"></script>
<script src="/Assets/js/easypiechart.js"></script>
<script src="/Assets/js/bootstrap-datepicker.js"></script>
<script src="/Assets/js/sweetalert2.min.js"></script>

<?php if (session()->getFlashdata('success')) : ?>
<script>
Swal.fire('Success!', '<?= session()->getFlashdata('success') ?>', 'success');
</script>
<?php endif; ?>

<script>
    !function ($) {
        $(document).on("click", "ul.nav li.parent > a", function () {
            $(this).find('.icon em').toggleClass("glyphicon-minus glyphicon-plus");
        });
    }(window.jQuery);

    $(window).on('resize', function () {
        if ($(window).width() > 768) $('#sidebar-collapse').collapse('show');
        if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide');
    });
</script>
</body>
</html>
