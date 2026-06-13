<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Transaksi</li>
            <li class="active">Peminjaman</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Data Anggota</h3>
                    <hr/>

                    <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-circle"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                    <?php endif; ?>

                    <div class="form-group col-md-6">
                        <label>ID Anggota</label>
                        <p class="form-control-static">
                            <strong><?= session()->get('idAgt') ?></strong>
                        </p>
                    </div>
                    <div style="clear:both;"></div>

                    <div class="form-group col-md-6">
                        <label>Nama Anggota</label>
                        <p class="form-control-static">
                            <strong><?= $dataAnggota['nama_anggota'] ?? '-' ?></strong>
                        </p>
                    </div>
                    <div style="clear:both;"></div>

                    <br>
                    <h3>
                        <i class="fa fa-shopping-cart"></i> Keranjang Peminjaman Buku
                        <span class="badge" style="background:#337ab7;"><?= $jumlahTemp ?></span>
                    </h3>
                    <table class="table table-bordered table-striped table-hover"
                           data-toggle="table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:5%">No</th>
                                <th>Judul Buku</th>
                                <th class="text-center">Pengarang</th>
                                <th class="text-center">Penerbit</th>
                                <th class="text-center" style="width:8%">Tahun</th>
                                <th class="text-center" style="width:10%">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; foreach ($dataTemp as $data): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= esc($data['judul_buku']) ?></td>
                                <td class="text-center"><?= esc($data['pengarang']) ?></td>
                                <td class="text-center"><?= esc($data['penerbit']) ?></td>
                                <td class="text-center"><?= esc($data['tahun']) ?></td>
                                <td class="text-center">
                                    <a href="#"
                                       onclick="doHapus('<?= sha1($data['id_buku']) ?>')"
                                       class="btn btn-xs btn-warning">
                                        <i class="fa fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($dataTemp)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding:20px;">
                                    <i class="fa fa-inbox fa-2x"></i><br>
                                    Belum ada buku di keranjang
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if ($jumlahTemp > 0): ?>
                    <a href="<?= base_url('admin/simpan-transaksi-peminjaman') ?>">
                        <button class="btn btn-primary btn-block" style="margin-bottom:15px;">
                            <i class="fa fa-save"></i> Simpan Transaksi Peminjaman Buku
                        </button>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL DAFTAR BUKU -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><i class="fa fa-book"></i> Daftar Buku Tersedia</strong>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-hover"
                           data-toggle="table"
                           data-show-refresh="true"
                           data-show-toggle="true"
                           data-show-columns="true"
                           data-search="true"
                           data-pagination="true"
                           data-sort-order="asc">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:5%">No</th>
                                <th>Judul Buku</th>
                                <th class="text-center">Pengarang</th>
                                <th class="text-center">Penerbit</th>
                                <th class="text-center" style="width:6%">Tahun</th>
                                <th class="text-center" style="width:6%">Stok</th>
                                <th class="text-center" style="width:12%">Kategori</th>
                                <th class="text-center" style="width:8%">Rak</th>
                                <th class="text-center" style="width:10%">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; foreach ($dataBuku as $data): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= esc($data['judul_buku']) ?></td>
                                <td class="text-center"><?= esc($data['pengarang']) ?></td>
                                <td class="text-center"><?= esc($data['penerbit']) ?></td>
                                <td class="text-center"><?= esc($data['tahun']) ?></td>
                                <td class="text-center">
                                    <span class="badge" style="background:<?= $data['jumlah_eksemplar'] > 0 ? '#5cb85c' : '#d9534f' ?>;">
                                        <?= $data['jumlah_eksemplar'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="label label-info"><?= esc($data['nama_kategori']) ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="label label-default"><?= esc($data['nama_rak']) ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($data['jumlah_eksemplar'] != '0'): ?>
                                    <a href="<?= base_url('admin/simpan-temp-pinjam/'.sha1($data['id_buku'])) ?>"
                                       class="btn btn-xs btn-primary">
                                        <i class="fa fa-plus"></i> Pinjam
                                    </a>
                                    <?php else: ?>
                                    <span class="label label-danger">Stok Habis</span>
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
<?php if (session()->getFlashdata('success')): ?>
Swal.fire({
    icon: 'success', title: 'Berhasil!',
    text: '<?= session()->getFlashdata('success') ?>',
    timer: 2000, showConfirmButton: false
});
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
Swal.fire('Gagal!', '<?= session()->getFlashdata('error') ?>', 'error');
<?php endif; ?>

function doHapus(id) {
    Swal.fire({
        title: 'Hapus dari keranjang?',
        text: 'Stok buku akan dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('admin/hapus-temp') ?>/' + id;
        }
    });
}
</script>