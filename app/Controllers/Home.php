<?php
namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Langsung arahkan ke halaman login admin
        return redirect()->to(base_url('admin/login'));
    }
}