<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key; 
use Config\JWT as JWTConfig;
use App\Models\UserModel;

class AuthController extends ResourceController {


    public function index()
    {
        return view('login', ['title' => 'Login']);
    }

    public function signUp()
    {
        return view('register', ['title' => 'register']);
    }
    public function register()
    {
        $username = $this->request->getVar('username');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $role = $this->request->getVar('role') ?? 'supplier';
    
        $userModel = new UserModel();
    
        if ($userModel->where('username', $username)->first()) {
            return $this->respond(['status' => 'error', 'message' => 'Username already exists'], 409);
        }
    
        if ($userModel->where('email', $email)->first()) {
            return $this->respond(['status' => 'error', 'message' => 'Email already exists'], 409);
        }
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        $userModel->insert([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ]);
    
        return $this->respond(['status' => 'success', 'message' => 'Registration successful'], 201);
    }

    public function login() {
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
    
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
    
        if ($user && password_verify($password, $user['password'])) {
            // Tạo JWT token
            $key = (new JWTConfig())->jwt_key;
            $payload = [
                'iss' => 'localhost', 
                'aud' => 'localhost', 
                'iat' => time(),
                'exp' => time() + 3600, 
                'data' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                ]
            ];
            $token = JWT::encode($payload, $key, 'HS256');

            return $this->respond([
                'status' => 'success',
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ], 200);
        } else {
            return $this->respond(['status' => 'error', 'message' => 'Thông tin đăng nhập không chính xác'], 401);
        }
    }

    public function getUserInfo() {
        $authHeader = $this->request->getHeader('Authorization');
        $token = null;
        if ($authHeader) {
            $headerValue = $authHeader->getValue();
            if (preg_match('/Bearer\s(\S+)/', $headerValue, $matches)) {
                $token = $matches[1];
            }
        }
        if (!$token) {
            return $this->respond(['status' => 'error', 'message' => 'Token không được cung cấp'], 401);
        }

        try {
            $key = (new JWTConfig())->jwt_key;
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $userData = (array) $decoded->data;

            return $this->respond(['status' => 'success', 'user' => $userData], 200);

        } catch (\Exception $e) {
            return $this->respond(['status' => 'error', 'message' => 'Token không hợp lệ'], 401);
        }
    }


    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'You have been logged out successfully.');
    }

}