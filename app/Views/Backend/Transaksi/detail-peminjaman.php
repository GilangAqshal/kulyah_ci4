<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= base_url('admin/data-transaksi-peminjaman') ?>">Data Peminjaman</a></li>
            <li class="active">Detail Peminjaman</li>
        </ol>
    </div>

    <!-- INFO HEADER -->
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><i class="fa fa-info-circle"></i> Info Peminjaman</strong>
                </div>
                <div class="panel-body">
                    <table class="table table-condensed" style="margin:0;">
                        <tr>
                            <td style="width:45%"><strong>No. Peminjaman</strong></td>
                            <td><?= esc($header['no_peminjaman']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Nama Anggota</strong></td>
                            <td><?= esc($header['nama_anggota'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tgl Pinjam</strong></td>
                            <td><?= $header['tgl_pinjam'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Total Buku</strong></td>
                            <td><span class="badge"><?= $header['total_pinjam'] ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Nama Admin</strong></td>
                            <td><?= esc($header['nama_admin'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status Transaksi</strong></td>
                            <td>
                                <?php if ($header['status_transaksi'] == 'Berjalan'): ?>
                                    <span class="label label-warning">Berjalan</span>
                                <?php else: ?>
                                    <span class="label label-success">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status Ambil Buku</strong></td>
                            <td>
                                <?php if ($header['status_ambil_buku'] == 'Belum Diambil'): ?>
                                    <span class="label label-danger">Belum Diambil</span>
                                    &nbsp;
                                    <?php if (session()->get('ses_level') == '2'): ?>
                                    <button class="btn btn-xs btn-success"
                                            onclick="konfirmasiAmbil('<?= $header['no_peminjaman'] ?>')">
                                        <i class="fa fa-check"></i> Tandai Sudah Diambill
                                    </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="label label-success">Sudah Diambill</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- DETAIL BUKU -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><i class="fa fa-book"></i> Daftar Buku Dipinjam</strong>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:5%">No</th>
                                <th>Judul Buku</th>
                                <th class="text-center">Pengarang</th>
                                <th class="text-center">Penerbit</th>
                                <th class="text-center" style="width:12%">Tgl Kembali</th>
                                <th class="text-center" style="width:15%">Status Pinjam</th>
                                <th class="text-center" style="width:8%">Perpanjangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; foreach ($detail as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><strong><?= esc($row['judul_buku']) ?></strong></td>
                                <td class="text-center"><?= esc($row['pengarang']) ?></td>
                                <td class="text-center"><?= esc($row['penerbit']) ?></td>
                                <td class="text-center"><?= $row['tgl_kembali'] ?></td>
                                <td class="text-center">
                                    <?php if ($row['status_pinjam'] == 'Sedang Dipinjam'): ?>
                                        <span class="label label-warning">Sedang Dipinjam</span>
                                    <?php else: ?>
                                        <span class="label label-success">Sudah Dikembalikan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge"><?= $row['perpanjangan'] ?>x</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="<?= base_url('admin/data-transaksi-peminjaman') ?>"
                       class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if (session()->getFlashdata('success')): ?>
Swal.fire({
    icon: 'success', title: 'Berhasil!',
    text: '<?= session()->getFlashdata('success') ?>',
    timer: 2000, showConfirmButton: false
});
<?php endif; ?>

function konfirmasiAmbil(noPeminjaman) {
    Swal.fire({
        title: 'Konfirmasi Pengambilan Buku',
        html: 'Tandai bahwa buku dengan No. Peminjaman<br><strong>' + noPeminjaman + '</strong><br>sudah diambil oleh anggota?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#5cb85c',
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="fa fa-check"></i> Ya, Sudah Diambil!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('admin/update-status-ambil') ?>/' + noPeminjaman;
        }
    });
}
</script>