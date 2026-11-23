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

        $categories = $this->db->table('ajobmart_categories')
            ->select('id, name, slug, sort_order')
            ->where('status', 'active')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $data['categories'] = $categories;

        return $this->view('home/customerindex', $data);
    }
}