<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= base_url('admin/master-data-rak') ?>">Master Data Rak</a></li>
            <li class="active">Tambah Rak</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-plus"></i> Form Tambah Rak</div>
                <div class="panel-body">
                    <form action="<?= base_url('admin/simpan-rak') ?>" method="post">
                        <div class="form-group">
                            <label>Nama Rak</label>
                            <input type="text" name="nama_rak" class="form-control"
                                   placeholder="Contoh: Rak A, Rak Fiksi, dll" required>
                        </div>
                        <hr/>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('admin/master-data-rak') ?>"
                           class="btn btn-default"><i class="fa fa-arrow-left"></i> Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>