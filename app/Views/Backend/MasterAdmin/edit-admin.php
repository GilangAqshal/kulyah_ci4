<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= base_url('admin/master-data-admin') ?>">Master Data Admin</a></li>
            <li class="active">Edit Admin</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-edit"></i> Form Edit Admin</div>
                <div class="panel-body">
                    <form action="<?= base_url('admin/update-admin') ?>" method="post">
                        <input type="hidden" name="id_admin"
                               value="<?= $data_admin['id_admin'] ?>">
                        <div class="form-group">
                            <label>Nama Admin</label>
                            <input type="text" name="nama" class="form-control"
                                   value="<?= esc($data_admin['nama_admin']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control"
                                   value="<?= esc($data_admin['username_admin']) ?>"
                                   readonly>
                            <small class="text-muted">Username tidak dapat diubah</small>
                        </div>
                        <div class="form-group">
                            <label>Password Baru <small class="text-muted">(kosongkan jika tidak ingin mengubah)</small></label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Isi jika ingin mengganti password">
                        </div>
                        <div class="form-group">
                            <label>Akses Level</label>
                            <select name="level" class="form-control" required>
                                <option value="">-- Pilih Level --</option>
                                <option value="2" <?= $data_admin['akses_level']=='2' ? 'selected' : '' ?>>
                                    Kepala Perpustakaan
                                </option>
                                <option value="3" <?= $data_admin['akses_level']=='3' ? 'selected' : '' ?>>
                                    Admin Perpustakaan
                                </option>
                            </select>
                        </div>
                        <hr/>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update
                        </button>
                        <a href="<?= base_url('admin/master-data-admin') ?>"
                           class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>