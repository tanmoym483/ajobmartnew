<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Cors implements FilterInterface
{
    private string $allowedOrigin = 'http://localhost:8080';

    public function before(RequestInterface $request, $arguments = null)
    {
        if (strtolower($request->getMethod()) === 'options') {
            return service('response')
                ->setStatusCode(204)
                ->setHeader('Access-Control-Allow-Origin', $this->allowedOrigin)
                ->setHeader('Access-Control-Allow-Credentials', 'true')
                ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
                ->setHeader(
                    'Access-Control-Allow-Headers',
                    'Content-Type, X-Requested-With, X-CSRF-TOKEN'
                )
                ->setHeader('Vary', 'Origin');
        }

        return null;
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        $response->setHeader('Access-Control-Allow-Origin', $this->allowedOrigin);
        $response->setHeader('Access-Control-Allow-Credentials', 'true');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->setHeader(
            'Access-Control-Allow-Headers',
            'Content-Type, X-Requested-With, X-CSRF-TOKEN'
        );
        $response->setHeader('Vary', 'Origin');

        return $response;
    }
}
