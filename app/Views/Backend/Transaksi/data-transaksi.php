<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Transaksi</li>
            <li class="active">Data Peminjaman</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Transaksi Peminjaman Buku
                        <a href="<?= base_url('admin/transaksi-peminjaman') ?>"
                           class="btn btn-sm btn-primary pull-right">
                            <i class="fa fa-plus"></i> Tambah Transaksi
                        </a>
                    </h3>
                    <hr/>
                    <table class="table table-bordered table-striped table-hover"
                           data-toggle="table" data-search="true" data-pagination="true">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:5%">No</th>
                                <th class="text-center">No. Peminjaman</th>
                                <th class="text-center">Nama Anggota</th>
                                <th class="text-center">Tgl Pinjam</th>
                                <th class="text-center" style="width:8%">Total Buku</th>
                                <th class="text-center">Status Transaksi</th>
                                <th class="text-center">Status Ambil</th>
                                <th class="text-center" style="width:12%">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; foreach ($data_peminjaman as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-center">
                                    <span class="label label-default">
                                        <?= esc($row['no_peminjaman']) ?>
                                    </span>
                                </td>
                                <td><?= esc($row['nama_anggota']) ?></td>
                                <td class="text-center"><?= $row['tgl_pinjam'] ?></td>
                                <td class="text-center">
                                    <span class="badge"><?= $row['total_pinjam'] ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['status_transaksi'] == 'Berjalan'): ?>
                                        <span class="label label-warning">Berjalan</span>
                                    <?php else: ?>
                                        <span class="label label-success">Selesai</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['status_ambil_buku'] == 'Belum Diambil'): ?>
                                        <span class="label label-danger">Belum Diambil</span>
                                    <?php else: ?>
                                        <span class="label label-success">Sudah Diambil</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/detail-peminjaman/'.$row['no_peminjaman']) ?>"
                                       class="btn btn-xs btn-info">
                                        <i class="fa fa-eye"></i> Detail
                                    </a>
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
<?php if (session()->getFlashdata('success')): ?>
Swal.fire({ icon:'success', title:'Berhasil!',
    text:'<?= session()->getFlashdata('success') ?>',
    timer:2000, showConfirmButton:false });
<?php endif; ?>
</script>