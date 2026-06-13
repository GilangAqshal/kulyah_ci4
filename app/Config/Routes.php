<?php

use CodeIgniter\Router\RouteCollection;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * @var RouteCollection $routes
 */

// ================================================================
// ROUTE FILE STATIS — WAJIB DI PALING ATAS
// ================================================================
$routes->addPlaceholder('filename', '[a-zA-Z0-9._\-]+');

// Route untuk cover buku
$routes->get('Assets/uploads/cover/(:filename)', function($filename) {
    $path = FCPATH . 'Assets/uploads/cover/' . $filename;
    if (is_file($path)) {
        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
    throw PageNotFoundException::forPageNotFound(); // ← pengganti show_404() di CI4
});

// Route untuk ebook PDF
$routes->get('Assets/uploads/ebook/(:filename)', function($filename) {
    $path = FCPATH . 'Assets/uploads/ebook/' . $filename;
    if (is_file($path)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
    throw PageNotFoundException::forPageNotFound();
});

// Route untuk no-image fallback
$routes->get('Assets/img/(:filename)', function($filename) {
    $path = FCPATH . 'Assets/img/' . $filename;
    if (is_file($path)) {
        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        readfile($path);
        exit;
    }
    throw PageNotFoundException::forPageNotFound();
});

// ================================================================
// LOGIN & DASHBOARD
// ================================================================
$routes->get('/', 'Admin::login');
$routes->get('/admin/login-admin', 'Admin::login');
$routes->post('/admin/autentikasi_login', 'Admin::autentikasi');
$routes->get('/admin/dashboard', 'Admin::dashboard');
$routes->get('/admin/logout', 'Admin::logout');

// ================================================================
// ADMIN MODULE
// ================================================================
$routes->get('/admin/master-data-admin', 'Admin::master_data_admin');
$routes->get('/admin/input-data-admin', 'Admin::input_data_admin');
$routes->post('/admin/simpan-admin', 'Admin::simpan_data_admin');
$routes->get('/admin/edit-data-admin/(:alphanum)', 'Admin::edit_data_admin/$1');
$routes->post('/admin/update-admin', 'Admin::update_data_admin');
$routes->get('/admin/hapus-data-admin/(:alphanum)', 'Admin::hapus_data_admin/$1');

// ================================================================
// ANGGOTA
// ================================================================
$routes->get('/admin/master-data-anggota', 'Anggota::master_data_anggota');
$routes->get('/admin/input-data-anggota', 'Anggota::input_data_anggota');
$routes->post('/admin/simpan-anggota', 'Anggota::simpan_data_anggota');
$routes->get('/admin/edit-data-anggota/(:alphanum)', 'Anggota::edit_data_anggota/$1');
$routes->post('/admin/update-anggota', 'Anggota::update_data_anggota');
$routes->get('/admin/hapus-data-anggota/(:alphanum)', 'Anggota::hapus_data_anggota/$1');

// ================================================================
// KATEGORI
// ================================================================
$routes->get('/admin/master-data-kategori', 'Kategori::master_data_kategori');
$routes->get('/admin/input-data-kategori', 'Kategori::input_data_kategori');
$routes->post('/admin/simpan-kategori', 'Kategori::simpan_data_kategori');
$routes->get('/admin/edit-data-kategori/(:alphanum)', 'Kategori::edit_data_kategori/$1');
$routes->post('/admin/update-kategori', 'Kategori::update_data_kategori');
$routes->get('/admin/hapus-data-kategori/(:alphanum)', 'Kategori::hapus_data_kategori/$1');

// ================================================================
// RAK
// ================================================================
$routes->get('/admin/master-data-rak', 'Rak::master_data_rak');
$routes->get('/admin/input-data-rak', 'Rak::input_data_rak');
$routes->post('/admin/simpan-rak', 'Rak::simpan_data_rak');
$routes->get('/admin/edit-data-rak/(:alphanum)', 'Rak::edit_data_rak/$1');
$routes->post('/admin/update-rak', 'Rak::update_data_rak');
$routes->get('/admin/hapus-data-rak/(:alphanum)', 'Rak::hapus_data_rak/$1');

// ================================================================
// BUKU
// ================================================================
$routes->get('/admin/master-data-buku', 'Buku::master_data_buku');
$routes->get('/admin/input-data-buku', 'Buku::input_data_buku');
$routes->post('/admin/simpan-buku', 'Buku::simpan_data_buku');
$routes->get('/admin/edit-data-buku/(:alphanum)', 'Buku::edit_data_buku/$1');
$routes->post('/admin/update-buku', 'Buku::update_data_buku');
$routes->get('/admin/hapus-data-buku/(:alphanum)', 'Buku::hapus_data_buku/$1');

// === TRANSAKSI PEMINJAMAN (sesuai PDF) ===
$routes->get('/admin/data-transaksi-peminjaman',        'Admin::data_transaksi_peminjaman');
$routes->get('/admin/peminjaman-step-1',                'Admin::peminjaman_step1');
$routes->get('/admin/peminjaman-step-2',                'Admin::peminjaman_step2');
$routes->post('/admin/peminjaman-step-2',               'Admin::peminjaman_step2');
$routes->get('/admin/simpan-temp-pinjam/(:alphanum)',   'Admin::simpan_temp_pinjam/$1');
$routes->get('/admin/hapus-temp/(:alphanum)',           'Admin::hapus_peminjaman/$1');
$routes->get('/admin/simpan-transaksi-peminjaman',      'Admin::simpan_transaksi_peminjaman');
$routes->get('/admin/detail-peminjaman/(:any)',         'Admin::detail_peminjaman/$1');