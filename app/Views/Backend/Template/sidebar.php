<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
    <ul class="nav menu">

        <!-- DASHBOARD -->
        <li>
            <a href="<?= base_url('admin/dashboard') ?>">
                <span class="fa fa-tachometer"></span> Dashboard
            </a>
        </li>

        <!-- MASTER DATA -->
        <li class="parent">
            <a href="#sub-item-master" data-toggle="collapse" aria-expanded="false">
                <span class="fa fa-th-large"></span> Master Data
                <em class="glyphicon glyphicon-plus pull-right" style="margin-top:3px;"></em>
            </a>
            <ul class="children collapse" id="sub-item-master">
                <li>
                    <a href="<?= base_url('admin/master-data-admin') ?>">
                        <span class="fa fa-user-secret"></span> Data Admin
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/master-data-anggota') ?>">
                        <span class="fa fa-users"></span> Data Anggota
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/master-data-kategori') ?>">
                        <span class="fa fa-tags"></span> Data Kategori
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/master-data-rak') ?>">
                        <span class="fa fa-archive"></span> Data Rak
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/master-data-buku') ?>">
                        <span class="fa fa-book"></span> Data Buku
                    </a>
                </li>
            </ul>
        </li>

        <!-- TRANSAKSI -->
<li class="parent">
    <a href="#sub-item-transaksi" data-toggle="collapse" aria-expanded="false">
        <span class="fa fa-exchange"></span> Transaksi
        <em class="glyphicon glyphicon-plus pull-right" style="margin-top:3px;"></em>
    </a>
    <ul class="children collapse" id="sub-item-transaksi">
        <li>
            <a href="<?= base_url('admin/peminjaman-step-1') ?>">
                <span class="fa fa-plus-circle"></span> Input Peminjaman
            </a>
        </li>
        <li>
            <a href="<?= base_url('admin/data-transaksi-peminjaman') ?>">
                <span class="fa fa-list-alt"></span> Data Peminjaman
            </a>
        </li>
    </ul>
</li>

        <li role="presentation" class="divider"></li>

        <!-- LOGOUT -->
        <li>
            <a href="<?= base_url('admin/logout') ?>">
                <span class="fa fa-sign-out"></span> Logout
            </a>
        </li>

    </ul>
    <div class="attribution">Template by
        <a href="http://www.medialoot.com/item/lumino-admin-bootstrap-template/">Medialoot</a>
    </div>
</div>