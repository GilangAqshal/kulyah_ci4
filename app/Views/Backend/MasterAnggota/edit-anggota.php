<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= base_url('admin/master-data-anggota') ?>">Master Data Anggota</a></li>
            <li class="active">Edit Anggota</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-edit"></i> Form Edit Anggota</div>
                <div class="panel-body">
                    <form action="<?= base_url('admin/update-anggota') ?>" method="post">
                        <input type="hidden" name="id_anggota"
                               value="<?= $data_anggota['id_anggota'] ?>">
                        <div class="form-group">
                            <label>Nama Anggota</label>
                            <input type="text" name="nama" class="form-control"
                                   value="<?= esc($data_anggota['nama_anggota']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" <?= $data_anggota['jenis_kelamin']=='L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= $data_anggota['jenis_kelamin']=='P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="text" name="noTelp" class="form-control"
                                   value="<?= esc($data_anggota['noTelp']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control"
                                      rows="3" required><?= esc($data_anggota['alamat']) ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update
                        </button>
                        <a href="<?= base_url('admin/master-data-anggota') ?>"
                           class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>