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
                        <a href="<?= base_url('admin/input-data-buku') ?>">
                            <button class="btn btn-sm btn-primary pull-right">
                                <i class="fa fa-plus"></i> Tambah Buku
                            </button>
                        </a>
                    </h3>
                    <hr/>
                    <table class="table table-bordered table-striped table-hover"
                           data-toggle="table" data-search="true" data-pagination="true">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:5%">No</th>
                                <th class="text-center" style="width:8%">Cover</th>
                                <th class="text-center">Judul Buku</th>
                                <th class="text-center">Pengarang</th>
                                <th class="text-center" style="width:12%">Kategori</th>
                                <th class="text-center" style="width:10%">Rak</th>
                                <th class="text-center" style="width:6%">Stok</th>
                                <th class="text-center" style="width:20%">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; foreach ($data_buku as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-center">
                                    <?php
                                    // Gunakan /Assets/... bukan base_url('Assets/...')
                                    $coverFile = FCPATH . 'Assets/uploads/cover/' . $row['cover_buku'];
                                    $coverSrc  = (file_exists($coverFile) && $row['cover_buku'] != 'no-image.jpg' && $row['cover_buku'] != '')
                                        ? '/Assets/uploads/cover/' . $row['cover_buku']
                                        : '/Assets/img/no-image.jpg';
                                    ?>
                                    <img src="<?= $coverSrc ?>"
                                         width="45" height="60"
                                         style="object-fit:cover; border-radius:4px; border:1px solid #ddd;">
                                </td>
                                <td><?= esc($row['judul_buku']) ?><br>
                                    <small class="text-muted"><?= esc($row['penerbit']) ?> (<?= esc($row['tahun']) ?>)</small>
                                </td>
                                <td><?= esc($row['pengarang']) ?></td>
                                <td class="text-center">
                                    <span class="label label-info"><?= esc($row['nama_kategori']) ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="label label-default"><?= esc($row['nama_rak']) ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge"><?= $row['jumlah_eksemplar'] ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/edit-data-buku/'.sha1($row['id_buku'])) ?>"
                                       class="btn btn-xs btn-success">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                    <button class="btn btn-xs btn-danger"
                                            onclick="doDelete('<?= sha1($row['id_buku']) ?>')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                    <?php if (!empty($row['e_book'])): ?>
                                    <!-- Pakai /Assets/... bukan base_url('Assets/...') -->
                                    <a href="/Assets/uploads/ebook/<?= $row['e_book'] ?>"
                                       target="_blank" class="btn btn-xs btn-info">
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
        icon: 'success', title: 'Berhasil!',
        text: '<?= session()->getFlashdata('success') ?>',
        timer: 2000, showConfirmButton: false
    });
<?php endif; ?>

function doDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data buku ini akan dihapus dari sistem aktif!',
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