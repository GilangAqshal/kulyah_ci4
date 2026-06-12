<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Transaksi</li>
            <li class="active">Form Peminjaman</li>
        </ol>
    </div>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        .select2-container { width: 100% !important; }
        .select2-selection--single {
            height: 34px !important;
            border: 1px solid #ccc !important;
            border-radius: 4px !important;
        }
        .select2-selection__rendered { line-height: 34px !important; }
        .select2-selection__arrow { height: 34px !important; }
        .panel-step { border-left: 4px solid #337ab7; }
        .empty-keranjang { text-align:center; color:#aaa; padding:30px 0; }
        #tbody-keranjang tr { animation: fadeIn .25s ease; }
        @keyframes fadeIn {
            from { opacity:0; transform:translateY(-4px); }
            to   { opacity:1; transform:translateY(0); }
        }
    </style>

    <!-- STEP 1: PILIH ANGGOTA -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-step">
                <div class="panel-heading">
                    <strong><i class="fa fa-user"></i> Step 1 — Pilih Anggota</strong>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cari Anggota <span class="text-danger">*</span></label>
                                <select id="select-anggota" style="width:100%">
                                    <option value="">-- Ketik ID atau Nama Anggota --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="info-anggota" style="display:none;">
                                <label>Info Anggota</label>
                                <div class="alert alert-info" style="margin:0; padding:10px;">
                                    <i class="fa fa-id-card"></i>
                                    <strong>ID:</strong> <span id="tampil-id-anggota"></span>
                                    &nbsp;|&nbsp;
                                    <strong>Nama:</strong> <span id="tampil-nama-anggota"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STEP 2: PILIH BUKU -->
    <div class="row" id="section-buku" style="display:none;">
        <div class="col-md-12">
            <div class="panel panel-default panel-step">
                <div class="panel-heading">
                    <strong><i class="fa fa-book"></i> Step 2 — Tambah Buku ke Keranjang</strong>
                </div>
                <div class="panel-body">

                    <!-- Form tambah buku -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Cari Buku <span class="text-danger">*</span></label>
                                <select id="select-buku" style="width:100%">
                                    <option value="">-- Ketik Judul atau Pengarang --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label><br>
                            <button id="btn-tambah" class="btn btn-success btn-block" disabled>
                                <i class="fa fa-plus"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </div>

                    <hr>

                    <!-- Tabel keranjang -->
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                        <h4 style="margin:0;">
                            <i class="fa fa-shopping-cart"></i> Keranjang
                            <span id="badge-total" class="badge" style="background:#337ab7; font-size:13px;">0</span>
                        </h4>
                    </div>

                    <div id="loading-keranjang" style="display:none; text-align:center; padding:15px; color:#888;">
                        <i class="fa fa-spinner fa-spin fa-lg"></i> Memuat keranjang...
                    </div>

                    <table class="table table-bordered table-striped table-hover" id="tabel-keranjang">
                        <thead style="background:#f5f5f5;">
                            <tr>
                                <th class="text-center" style="width:5%">No</th>
                                <th>Judul Buku</th>
                                <th class="text-center" style="width:18%">Pengarang</th>
                                <th class="text-center" style="width:8%">Tahun</th>
                                <th class="text-center" style="width:8%">Stok</th>
                                <th class="text-center" style="width:10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-keranjang">
                            <tr id="row-empty">
                                <td colspan="6" class="empty-keranjang">
                                    <i class="fa fa-inbox fa-2x"></i><br>
                                    Belum ada buku di keranjang
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Tombol simpan -->
                    <div id="section-simpan" style="display:none;">
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i>
                            Pastikan data sudah benar. Setelah disimpan, stok buku akan berkurang otomatis.
                        </div>
                        <button id="btn-simpan" class="btn btn-primary btn-lg btn-block">
                            <i class="fa fa-save"></i> Simpan Transaksi Peminjaman
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     SCRIPT — letakkan di bawah setelah jQuery dari footer.php
     sudah dimuat
     ============================================================ -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    const BASE = '<?= base_url() ?>';
    let idAnggota   = '';
    let namaAnggota = '';
    let idBuku      = '';

    // ── SELECT2 ANGGOTA ──────────────────────────────────────
    $('#select-anggota').select2({
        placeholder        : '-- Ketik ID atau Nama Anggota --',
        allowClear         : true,
        minimumInputLength : 1,
        language           : { inputTooShort: () => 'Ketik minimal 1 karakter...' },
        ajax: {
            url      : BASE + '/admin/ajax-cari-anggota',
            type     : 'POST',
            dataType : 'json',
            delay    : 300,
            data     : params => ({ q: params.term }),
            processResults: data => ({ results: data.results }),
            cache    : false
        }
    });

    $('#select-anggota').on('select2:select', function (e) {
        const d   = e.params.data;
        const bag = d.text.split(' - ');
        idAnggota   = d.id;
        namaAnggota = bag[1] ?? d.text;

        $('#tampil-id-anggota').text(idAnggota);
        $('#tampil-nama-anggota').text(namaAnggota);
        $('#info-anggota').fadeIn(200);
        $('#section-buku').fadeIn(200);
        loadKeranjang();
    });

    $('#select-anggota').on('select2:unselect select2:clear', function () {
        idAnggota = '';
        $('#info-anggota').fadeOut(200);
        $('#section-buku').fadeOut(200);
        $('#section-simpan').hide();
        renderKeranjang([]);
    });

    // ── SELECT2 BUKU ─────────────────────────────────────────
    $('#select-buku').select2({
        placeholder        : '-- Ketik Judul atau Pengarang --',
        allowClear         : true,
        minimumInputLength : 1,
        language           : { inputTooShort: () => 'Ketik minimal 1 karakter...' },
        ajax: {
            url      : BASE + '/admin/ajax-cari-buku',
            type     : 'POST',
            dataType : 'json',
            delay    : 300,
            data     : params => ({ q: params.term }),
            processResults: data => ({ results: data.results }),
            cache    : false
        }
    });

    $('#select-buku').on('select2:select', function (e) {
        idBuku = e.params.data.id;
        $('#btn-tambah').prop('disabled', false);
    });

    $('#select-buku').on('select2:unselect select2:clear', function () {
        idBuku = '';
        $('#btn-tambah').prop('disabled', true);
    });

    // ── TAMBAH KE KERANJANG ───────────────────────────────────
    $('#btn-tambah').on('click', function () {
        if (!idAnggota || !idBuku) {
            Swal.fire('Peringatan', 'Pilih anggota dan buku terlebih dahulu!', 'warning');
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menambahkan...');

        $.ajax({
            url      : BASE + '/admin/ajax-tambah-keranjang',
            type     : 'POST',
            data     : { id_anggota: idAnggota, id_buku: idBuku },
            dataType : 'json',
            success  : function (res) {
                if (res.status === 'success') {
                    renderKeranjang(res.keranjang);
                    $('#select-buku').val(null).trigger('change');
                    idBuku = '';
                    Swal.fire({
                        icon: 'success', title: 'Ditambahkan!',
                        text: res.message, timer: 1500, showConfirmButton: false
                    });
                } else {
                    Swal.fire('Gagal!', res.message, 'error');
                }
            },
            error    : () => Swal.fire('Error', 'Terjadi kesalahan server!', 'error'),
            complete : () => btn.prop('disabled', false)
                               .html('<i class="fa fa-plus"></i> Tambah ke Keranjang')
        });
    });

    // ── HAPUS DARI KERANJANG ──────────────────────────────────
    $(document).on('click', '.btn-hapus', function () {
        const idB = $(this).data('buku');
        Swal.fire({
            title: 'Hapus dari keranjang?',
            icon : 'warning',
            showCancelButton  : true,
            confirmButtonColor: '#d33',
            confirmButtonText : 'Ya, Hapus!',
            cancelButtonText  : 'Batal'
        }).then(r => {
            if (!r.isConfirmed) return;
            $.ajax({
                url      : BASE + '/admin/ajax-hapus-keranjang',
                type     : 'POST',
                data     : { id_anggota: idAnggota, id_buku: idB },
                dataType : 'json',
                success  : function (res) {
                    if (res.status === 'success') {
                        renderKeranjang(res.keranjang);
                        Swal.fire({ icon:'success', title:'Dihapus!',
                            text: res.message, timer:1200, showConfirmButton:false });
                    } else {
                        Swal.fire('Gagal!', res.message, 'error');
                    }
                }
            });
        });
    });

    // ── SIMPAN TRANSAKSI ──────────────────────────────────────
    $('#btn-simpan').on('click', function () {
        if (!idAnggota) {
            Swal.fire('Peringatan', 'Pilih anggota terlebih dahulu!', 'warning');
            return;
        }

        Swal.fire({
            title: 'Simpan Transaksi?',
            html : 'Anggota: <strong>' + namaAnggota + '</strong><br>Pastikan data sudah benar!',
            icon : 'question',
            showCancelButton  : true,
            confirmButtonColor: '#337ab7',
            confirmButtonText : '<i class="fa fa-save"></i> Ya, Simpan!',
            cancelButtonText  : 'Batal'
        }).then(r => {
            if (!r.isConfirmed) return;

            const btn = $('#btn-simpan');
            btn.prop('disabled', true)
               .html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url      : BASE + '/admin/ajax-simpan-transaksi',
                type     : 'POST',
                data     : { id_anggota: idAnggota },
                dataType : 'json',
                success  : function (res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon : 'success',
                            title: 'Transaksi Berhasil!',
                            html : 'No. Peminjaman: <strong>' + res.no_peminjaman + '</strong><br>'
                                 + 'Tgl Kembali: <strong>' + getTglKembali() + '</strong>',
                            confirmButtonText: 'Lihat Data Transaksi'
                        }).then(() => {
                            window.location.href = BASE + '/admin/data-transaksi-peminjaman';
                        });
                    } else {
                        Swal.fire('Gagal!', res.message, 'error');
                        btn.prop('disabled', false)
                           .html('<i class="fa fa-save"></i> Simpan Transaksi Peminjaman');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Terjadi kesalahan server!', 'error');
                    btn.prop('disabled', false)
                       .html('<i class="fa fa-save"></i> Simpan Transaksi Peminjaman');
                }
            });
        });
    });

    // ── HELPER: RENDER TABEL KERANJANG ────────────────────────
    function renderKeranjang(data) {
        const tbody = $('#tbody-keranjang');
        tbody.empty();
        $('#badge-total').text(data.length);

        if (!data || data.length === 0) {
            tbody.append(`
                <tr id="row-empty">
                    <td colspan="6" class="empty-keranjang">
                        <i class="fa fa-inbox fa-2x"></i><br>
                        Belum ada buku di keranjang
                    </td>
                </tr>`);
            $('#section-simpan').hide();
            return;
        }

        $.each(data, function (i, item) {
            tbody.append(`
                <tr>
                    <td class="text-center">${i + 1}</td>
                    <td>
                        <strong>${item.judul_buku}</strong><br>
                        <small class="text-muted">${item.penerbit ?? '-'}</small>
                    </td>
                    <td class="text-center">${item.pengarang}</td>
                    <td class="text-center">${item.tahun}</td>
                    <td class="text-center">
                        <span class="badge" style="background:#5cb85c;">${item.jumlah_eksemplar}</span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-xs btn-danger btn-hapus" data-buku="${item.id_buku}">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>`);
        });

        $('#section-simpan').fadeIn(200);
    }

    // ── HELPER: LOAD KERANJANG ────────────────────────────────
    function loadKeranjang() {
        $('#loading-keranjang').show();
        $.ajax({
            url      : BASE + '/admin/ajax-get-keranjang',
            type     : 'GET',
            data     : { id_anggota: idAnggota },
            dataType : 'json',
            success  : res => renderKeranjang(res.keranjang),
            complete : () => $('#loading-keranjang').hide()
        });
    }

    // ── HELPER: TANGGAL +7 ────────────────────────────────────
    function getTglKembali() {
        const d = new Date();
        d.setDate(d.getDate() + 7);
        return d.toISOString().split('T')[0];
    }

});
</script>