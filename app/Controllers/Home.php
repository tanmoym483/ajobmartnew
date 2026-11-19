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

        $data['banners'] = $this->db
            ->table('ajobmart_home_banners')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return $this->view('home/customerindex', $data);
    }
}