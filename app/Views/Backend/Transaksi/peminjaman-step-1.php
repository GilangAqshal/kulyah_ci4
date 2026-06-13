<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Transaksi</li>
            <li class="active">Peminjaman</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Input Anggota</h3>
                    <hr/>
                    <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-circle"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/peminjaman-step-2') ?>" method="post">
                        <div class="form-group col-md-6">
                            <label>ID Anggota</label>
                            <input type="text" class="form-control" name="id_anggota"
                                   placeholder="Masukkan ID Anggota" required>
                            <small class="text-muted">Contoh: ANG001</small>
                        </div>
                        <div style="clear:both;"></div>

                        <div class="form-group col-md-6">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-arrow-right"></i> Next
                            </button>
                            <a href="<?= base_url('admin/data-transaksi-peminjaman') ?>"
                               class="btn btn-danger">
                                <i class="fa fa-times"></i> Batal
                            </a>
                        </div>
                        <div style="clear:both;"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if (session()->getFlashdata('error')): ?>
Swal.fire('Gagal!', '<?= session()->getFlashdata('error') ?>', 'error');
<?php endif; ?>
</script>