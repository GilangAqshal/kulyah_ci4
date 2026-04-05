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

</body>
</html>