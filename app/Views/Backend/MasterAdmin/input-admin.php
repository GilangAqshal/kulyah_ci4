<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= base_url('admin/master-data-admin') ?>">Master Data Admin</a></li>
            <li class="active">Tambah Admin</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-user-plus"></i> Form Tambah Admin</div>
                <div class="panel-body">
                    <form action="<?= base_url('admin/simpan-admin') ?>" method="post">
                        <div class="form-group">
                            <label>Nama Admin</label>
                            <input type="text" name="nama" class="form-control"
                                   placeholder="Masukkan Nama Lengkap" required>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control"
                                   placeholder="Masukkan Username (tanpa spasi)"
                                   onKeyPress="return goodchars(event,
                                   'abcdefghijklmnopqrstuvwxyz_ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',this)"
                                   required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Masukkan Password" required>
                        </div>
                        <div class="form-group">
                            <label>Akses Level</label>
                            <select name="level" class="form-control" required>
                                <option value="">-- Pilih Level --</option>
                                <option value="2">Kepala Perpustakaan</option>
                                <option value="3">Admin Perpustakaan</option>
                            </select>
                        </div>
                        <hr/>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan
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