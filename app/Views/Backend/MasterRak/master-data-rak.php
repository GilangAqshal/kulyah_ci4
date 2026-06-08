<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active">Master Data Rak</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Data Rak
                        <a href="<?= base_url('admin/input-data-rak') ?>">
                            <button class="btn btn-sm btn-primary pull-right">
                                <i class="fa fa-plus"></i> Tambah Rak
                            </button>
                        </a>
                    </h3>
                    <hr/>
                    <table class="table table-bordered table-striped table-hover"
                           data-toggle="table" data-search="true" data-pagination="true">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 7%">No</th>
                                <th class="text-center" style="width: 15%">ID Rak</th>
                                <th class="text-center">Nama Rak</th>
                                <th class="text-center" style="width: 20%">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; foreach ($data_rak as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-center"><span class="label label-info"><?= esc($row['id_rak']) ?></span></td>
                                <td><?= esc($row['nama_rak']) ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/edit-data-rak/'.sha1($row['id_rak'])) ?>" class="btn btn-xs btn-success">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                    <button class="btn btn-xs btn-danger" onclick="doDelete('<?= sha1($row['id_rak']) ?>')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
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
// 1. Ambil & Tampilkan Flashdata Sukses Otomatis
<?php if (session()->getFlashdata('success')) : ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?= session()->getFlashdata('success'); ?>',
        timer: 2000,
        showConfirmButton: false
    });
<?php endif; ?>

// 2. Konfirmasi Hapus Data dengan Desain Modis
function doDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data rak ini akan dihapus dari sistem aktif!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perbaikan URL redirect tanpa double slash bawaan base_url()
            window.location.href = '<?= base_url('admin/hapus-data-rak') ?>/' + id;
        }
    });
}
</script>