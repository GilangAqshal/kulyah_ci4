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
                                <th class="text-center" style="width: 5%">No</th>
                                <th class="text-center" style="width: 10%">Cover</th>
                                <th class="text-center" style="width: 25%">Judul Buku</th>
                                <th class="text-center" style="width: 15%">Pengarang</th>
                                <th class="text-center" style="width: 12%">Kategori</th>
                                <th class="text-center" style="width: 12%">Rak</th>
                                <th class="text-center" style="width: 6%">Stok</th>
                                <th class="text-center" style="width: 15%">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; foreach ($data_buku as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-center">
                                    <img src="<?= base_url('Assets/cover/' . $row['cover_buku']) ?>"
                                         width="50" height="65"
                                         style="object-fit:cover; border-radius:4px; box-shadow: 0 1px 4px rgba(0,0,0,0.2);"
                                         onerror="this.src='<?= base_url('Assets/img/no-image.jpg') ?>'">
                                </td>
                                <td><strong><?= esc($row['judul_buku']) ?></strong></td>
                                <td><?= esc($row['pengarang']) ?></td>
                                <td class="text-center"><span class="label label-default"><?= esc($row['nama_kategori'] ?? 'Tidak Ada') ?></span></td>
                                <td class="text-center"><span class="label label-info"><?= esc($row['nama_rak'] ?? 'Tidak Ada') ?></span></td>
                                <td class="text-center"><?= $row['jumlah_eksemplar'] ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/edit-data-buku/' . sha1($row['id_buku'])) ?>" class="btn btn-xs btn-success" title="Edit Data">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                    
                                    <button class="btn btn-xs btn-danger" onclick="doDelete('<?= sha1($row['id_buku']) ?>')" title="Hapus Data">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                    
                                    <?php if (!empty($row['e_book'])): ?>
                                    <a href="<?= base_url('Assets/ebook/' . $row['e_book']) ?>" target="_blank" class="btn btn-xs btn-info" title="Lihat E-Book">
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
// 1. Deteksi Flashdata Sukses dari Redirect Controller secara Otomatis
<?php if (session()->getFlashdata('success')) : ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?= session()->getFlashdata('success'); ?>',
        timer: 2000,
        showConfirmButton: false
    });
<?php endif; ?>

// 2. Handler Konfirmasi Penghapusan (Soft-Delete)
function doDelete(id) {
    Swal.fire({
        title: 'Hapus Data Buku?',
        text: "Data buku yang dihapus akan dialihkan ke dalam sistem arsip data.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#777',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((r) => {
        if (r.isConfirmed) {
            // Pembersihan penggabungan URL untuk mencegah tabrakan rute double slash
            window.location.href = '<?= base_url('admin/hapus-data-buku') ?>/' + id;
        }
    });
}
</script>