<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= base_url('admin/master-data-kategori') ?>">Kategori</a></li>
            <li class="active">Edit Kategori</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-edit"></i> Form Edit Kategori</div>
                <div class="panel-body">
                    <form action="<?= base_url('admin/update-kategori') ?>" method="post">
                        <input type="hidden" name="id_kategori"
                               value="<?= $data_kategori['id_kategori'] ?>">
                        <div class="form-group">
                            <label>Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control"
                                   value="<?= esc($data_kategori['nama_kategori']) ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update
                        </button>
                        <a href="<?= base_url('admin/master-data-kategori') ?>"
                           class="btn btn-default"><i class="fa fa-arrow-left"></i> Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>