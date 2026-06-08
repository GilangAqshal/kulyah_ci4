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
                                <th class="text-center">No</th>
                                <th class="text-center">Cover</th>
                                <th class="text-center">Judul Buku</th>
                                <th class="text-center">Pengarang</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Rak</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; foreach ($data_buku as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-center">
                                    <img src="<?= base_url('Assets/uploads/cover/' . $row['cover_buku']) ?>"
                                         width="50" height="65"
                                         style="object-fit:cover; border-radius:4px;"
                                         onerror="this.src='<?= base_url('Assets/img/no-image.jpg') ?>'">
                                </td>
                                <td><?= esc($row['judul_buku']) ?></td>
                                <td><?= esc($row['pengarang']) ?></td>
                                <td class="text-center"><?= esc($row['nama_kategori']) ?></td>
                                <td class="text-center"><?= esc($row['nama_rak']) ?></td>
                                <td class="text-center"><?= $row['jumlah_eksemplar'] ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/edit-data-buku/' . sha1($row['id_buku'])) ?>">
                                        <button class="btn btn-xs btn-success">
                                            <i class="fa fa-pencil"></i> Edit
                                        </button>
                                    </a>
                                    <button class="btn btn-xs btn-danger"
                                            onclick="doDelete('<?= sha1($row['id_buku']) ?>')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                    <?php if ($row['e_book']): ?>
                                    <a href="<?= base_url('Assets/uploads/ebook/' . $row['e_book']) ?>"
                                       target="_blank">
                                        <button class="btn btn-xs btn-info">
                                            <i class="fa fa-file-pdf-o"></i> E-Book
                                        </button>
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

<script>
function doDelete(id) {
    Swal.fire({
        title: 'Hapus Data Buku?', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
    }).then((r) => {
        if (r.isConfirmed) window.location.href = '<?= base_url() ?>/admin/hapus-data-buku/' + id;
    });
}
</script>