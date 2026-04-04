<?php

namespace App\Controllers;
// load models
use App\Models\M_Admin;

class Admin extends BaseController
{
    public function login()
    {
        return view('Backend/Login/login');
    }

    public function dashboard(){
        echo view('Backend/Template/header');
        echo view('Backend/Template/sidebar');
        echo view('Backend/Login/dashboardAdmin');
        echo view('Backend/Template/footer');
    }


}