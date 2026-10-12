<?php

namespace App\Controllers;
use App\Controllers\BaseController;


class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Ajobmart - Online Shopping',
        ];

        return $this->view('home/customerindex', $data);
    }
}