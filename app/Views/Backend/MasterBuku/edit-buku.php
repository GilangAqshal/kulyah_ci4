<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= base_url('admin/master-data-buku') ?>">Master Data Buku</a></li>
            <li class="active">Edit Buku</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-edit"></i> Form Edit Buku</div>
                <div class="panel-body">
                    <form action="<?= base_url('admin/update-buku') ?>"
                          method="post" enctype="multipart/form-data">
                        <!-- Hidden fields untuk ID dan file lama -->
                        <input type="hidden" name="id_buku"    value="<?= $data_buku['id_buku'] ?>">
                        <input type="hidden" name="cover_lama" value="<?= $data_buku['cover_buku'] ?>">
                        <input type="hidden" name="ebook_lama" value="<?= $data_buku['e_book'] ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Judul Buku</label>
                                    <input type="text" name="judul_buku" class="form-control"
                                           value="<?= esc($data_buku['judul_buku']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Pengarang</label>
                                    <input type="text" name="pengarang" class="form-control"
                                           value="<?= esc($data_buku['pengarang']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Penerbit</label>
                                    <input type="text" name="penerbit" class="form-control"
                                           value="<?= esc($data_buku['penerbit']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Tahun Terbit</label>
                                    <input type="text" name="tahun" class="form-control"
                                           value="<?= esc($data_buku['tahun']) ?>"
                                           maxlength="4" required>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Eksemplar</label>
                                    <input type="number" name="jumlah_eksemplar" class="form-control"
                                           value="<?= $data_buku['jumlah_eksemplar'] ?>"
                                           min="1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="id_kategori" class="form-control" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php foreach ($data_kategori as $kat): ?>
                                        <option value="<?= $kat['id_kategori'] ?>"
                                            <?= $data_buku['id_kategori']==$kat['id_kategori'] ? 'selected' : '' ?>>
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
                                        <option value="<?= $rak['id_rak'] ?>"
                                            <?= $data_buku['id_rak']==$rak['id_rak'] ? 'selected' : '' ?>>
                                            <?= esc($rak['nama_rak']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Cover Buku</label>
                                    <?php if ($data_buku['cover_buku']): ?>
                                    <div class="mb-2">
                                        <img src="<?= base_url('Assets/uploads/cover/'.$data_buku['cover_buku']) ?>"
                                             height="80" style="border-radius:4px;"
                                             onerror="this.style.display='none'">
                                        <small class="text-muted d-block">Cover saat ini</small>
                                    </div>
                                    <?php endif; ?>
                                    <input type="file" name="cover_buku" class="form-control"
                                           accept="image/*">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah cover</small>
                                </div>
                                <div class="form-group">
                                    <label>E-Book (PDF)</label>
                                    <?php if ($data_buku['e_book']): ?>
                                    <div class="mb-1">
                                        <a href="<?= base_url('Assets/uploads/ebook/'.$data_buku['e_book']) ?>"
                                           target="_blank" class="btn btn-xs btn-info">
                                            <i class="fa fa-file-pdf-o"></i> Lihat E-Book Saat Ini
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    <input type="file" name="e_book" class="form-control"
                                           accept=".pdf">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah e-book</small>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control"
                                              rows="3"><?= esc($data_buku['keterangan']) ?></textarea>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update
                        </button>
                        <a href="<?= base_url('admin/master-data-buku') ?>"
                           class="btn btn-default"><i class="fa fa-arrow-left"></i> Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>