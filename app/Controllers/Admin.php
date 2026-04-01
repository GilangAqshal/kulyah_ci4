<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function login()
    {
        return view('Backend/Template/login');
    }


}