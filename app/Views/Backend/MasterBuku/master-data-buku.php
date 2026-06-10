<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active">Master Data Buku</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Data Buku
                        <?php if (session()->get('ses_level') == '2'): ?>
                        <a href="<?= base_url('admin/input-data-buku') ?>">
                            <button class="btn btn-sm btn-primary pull-right">
                                <i class="fa fa-plus"></i> Tambah Buku
                            </button>
                        </a>
                        <?php endif; ?>
                    </h3>
                    <hr/>
                    <table class="table table-bordered table-striped table-hover"
                           data-toggle="table" data-search="true" data-pagination="true">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:5%">No</th>
                                <th class="text-center" style="width:7%">Cover</th>
                                <th class="text-center">Judul Buku</th>
                                <th class="text-center">Pengarang</th>
                                <th class="text-center" style="width:12%">Kategori</th>
                                <th class="text-center" style="width:10%">Rak</th>
                                <th class="text-center" style="width:5%">Stok</th>
                                <th class="text-center" style="width:20%">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; foreach ($data_buku as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>

                                <!-- COVER -->
                                <td class="text-center">
                                    <?php
                                    $namaCover   = trim($row['cover_buku'] ?? '');
                                    $lokasiCover = FCPATH . 'Assets/uploads/cover/' . $namaCover;
                                    if (!empty($namaCover) && $namaCover !== 'no-image.jpg' && is_file($lokasiCover)) {
                                        $urlCover = base_url('Assets/uploads/cover/' . $namaCover);
                                    } else {
                                        $urlCover = base_url('Assets/img/no-image.jpg');
                                    }
                                    ?>
                                    <img src="<?= $urlCover ?>"
                                         alt="Cover"
                                         width="45" height="60"
                                         style="object-fit:cover; border:1px solid #ddd; border-radius:4px;"
                                         onerror="this.src='<?= base_url('Assets/img/no-image.jpg') ?>'">
                                </td>

                                <!-- JUDUL -->
                                <td>
                                    <strong><?= esc($row['judul_buku']) ?></strong><br>
                                    <small class="text-muted">
                                        <?= esc($row['penerbit']) ?> &bull; <?= esc($row['tahun']) ?>
                                    </small>
                                </td>

                                <!-- PENGARANG -->
                                <td><?= esc($row['pengarang']) ?></td>

                                <!-- KATEGORI -->
                                <td class="text-center">
                                    <span class="label label-info"><?= esc($row['nama_kategori']) ?></span>
                                </td>

                                <!-- RAK -->
                                <td class="text-center">
                                    <span class="label label-default"><?= esc($row['nama_rak']) ?></span>
                                </td>

                                <!-- STOK -->
                                <td class="text-center">
                                    <span class="badge"><?= $row['jumlah_eksemplar'] ?></span>
                                </td>

                                <!-- OPSI -->
                                <td class="text-center">

                                    <?php if (session()->get('ses_level') == '2'): ?>
                                    <a href="<?= base_url('admin/edit-data-buku/'.sha1($row['id_buku'])) ?>"
                                       class="btn btn-xs btn-success">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                    <button class="btn btn-xs btn-danger"
                                            onclick="doDelete('<?= sha1($row['id_buku']) ?>')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                    <?php endif; ?>

                                    <?php
                                    $namaEbook   = trim($row['e_book'] ?? '');
                                    $lokasiEbook = FCPATH . 'Assets/uploads/ebook/' . $namaEbook;
                                    ?>
                                    <?php if (!empty($namaEbook) && is_file($lokasiEbook)): ?>
                                    <a href="<?= base_url('Assets/uploads/ebook/' . $namaEbook) ?>"
                                       target="_blank"
                                       class="btn btn-xs btn-info">
                                        <i class="fa fa-file-pdf-o"></i> E-Book
                                    </a>
                                    <?php endif; ?>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if (session()->getFlashdata('success')) : ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '<?= session()->getFlashdata('success') ?>',
    timer: 2000,
    showConfirmButton: false
});
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '<?= session()->getFlashdata('error') ?>'
});
<?php endif; ?>

function doDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data buku ini akan dihapus dari sistem!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('admin/hapus-data-buku') ?>/' + id;
        }
    });
}
</script>