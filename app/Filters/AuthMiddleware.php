<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\JWT as JWTConfig;

class AuthMiddleware implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $cookie = $request->getCookie('token');

        if (!$cookie) {
            return redirect()->to('/login');
        }

        try {
            $key = (new JWTConfig())->jwt_key;
            $decoded = JWT::decode($cookie, new Key($key, 'HS256'));

            $role = $decoded->data->role ?? null;
            $currentPath = $request->getUri()->getPath();
            if ($currentPath === '/') {
                if ($role == 'contractor') {
                    return redirect()->to('/tender/list_contractor');
                } elseif ($role == 'supplier') {
                    return redirect()->to('/tender/list_supplier');
                }
            }
        } catch (\Exception $e) {
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}