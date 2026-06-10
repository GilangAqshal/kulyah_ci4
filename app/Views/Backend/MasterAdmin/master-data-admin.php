<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active">Master Data Admin</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Data Admin
                        <a href="<?= base_url('admin/input-data-admin') ?>">
                            <button class="btn btn-sm btn-primary pull-right">
                                <i class="fa fa-user-plus"></i> Tambah Admin
                            </button>
                        </a>
                    </h3>
                    <hr/>
                    <table class="table table-bordered table-striped table-hover"
                           data-toggle="table" data-search="true" data-pagination="true">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:7%">No</th>
                                <th class="text-center">Nama Admin</th>
                                <th class="text-center">Username</th>
                                <th class="text-center" style="width:20%">Akses Level</th>
                                <th class="text-center" style="width:22%">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; foreach ($data_user as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= esc($row['nama_admin']) ?></td>
                                <td class="text-center">
                                    <span class="label label-info"><?= esc($row['username_admin']) ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['akses_level'] == '1'): ?>
                                        <span class="label label-danger">Super Admin</span>
                                    <?php elseif ($row['akses_level'] == '2'): ?>
                                        <span class="label label-warning">Kepala Perpustakaan</span>
                                    <?php else: ?>
                                        <span class="label label-success">Admin Perpustakaan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <!-- HAPUS kondisi ses_level == "1", tampilkan selalu -->
                                    <a href="<?= base_url('admin/edit-data-admin/'.sha1($row['id_admin'])) ?>"
                                       class="btn btn-xs btn-success">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                    <button class="btn btn-xs btn-danger"
                                            onclick="doDelete('<?= sha1($row['id_admin']) ?>')">
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
        text: 'Data admin ini akan dihapus dari sistem aktif!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('admin/hapus-data-admin') ?>/' + id;
        }
    });
}
</script>