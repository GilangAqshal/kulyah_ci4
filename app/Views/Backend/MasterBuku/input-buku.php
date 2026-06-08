<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= base_url('admin/master-data-buku') ?>">Master Data Buku</a></li>
            <li class="active">Tambah Buku</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-plus"></i> Form Tambah Buku</div>
                <div class="panel-body">
                    <!-- enctype wajib untuk upload file -->
                    <form action="<?= base_url('admin/simpan-buku') ?>"
                          method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Judul Buku</label>
                                    <input type="text" name="judul_buku" class="form-control"
                                           placeholder="Masukkan Judul Buku" required>
                                </div>
                                <div class="form-group">
                                    <label>Pengarang</label>
                                    <input type="text" name="pengarang" class="form-control"
                                           placeholder="Masukkan Nama Pengarang" required>
                                </div>
                                <div class="form-group">
                                    <label>Penerbit</label>
                                    <input type="text" name="penerbit" class="form-control"
                                           placeholder="Masukkan Penerbit" required>
                                </div>
                                <div class="form-group">
                                    <label>Tahun Terbit</label>
                                    <input type="text" name="tahun" class="form-control"
                                           placeholder="Contoh: 2023" maxlength="4" required>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Eksemplar</label>
                                    <input type="number" name="jumlah_eksemplar" class="form-control"
                                           min="1" placeholder="Masukkan Jumlah" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="id_kategori" class="form-control" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php foreach ($data_kategori as $kat): ?>
                                        <option value="<?= $kat['id_kategori'] ?>">
                                            <?= esc($kat['nama_kategori']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Rak</label>
                                    <select name="id_rak" class="form-control" required>
                                        <option value="">-- Pilih Rak --</option>
                                        <?php foreach ($data_rak as $rak): ?>
                                        <option value="<?= $rak['id_rak'] ?>">
                                            <?= esc($rak['nama_rak']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Cover Buku</label>
                                    <input type="file" name="cover_buku" class="form-control"
                                           accept="image/*">
                                    <small class="text-muted">Format: JPG/PNG, Maks 2MB</small>
                                </div>
                                <div class="form-group">
                                    <label>E-Book (PDF)</label>
                                    <input type="file" name="e_book" class="form-control"
                                           accept=".pdf">
                                    <small class="text-muted">Format: PDF, Maks 10MB</small>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control"
                                              rows="3" placeholder="Deskripsi singkat buku..."></textarea>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('admin/master-data-buku') ?>"
                           class="btn btn-default"><i class="fa fa-arrow-left"></i> Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>