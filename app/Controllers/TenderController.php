<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\JWT as JWTConfig;
use App\Models\TenderModel;
use App\Models\InviteModel;

class TenderController extends ResourceController
{
    protected $tenderModel;

    public function __construct()
    {
        $this->tenderModel = new TenderModel();
    }

    public function create()
    {
        return view('tender/create', ['title' => 'Create Tender']);
    }

    public function createTender()
    {
        $authHeader = $this->request->getHeader('Authorization');
        $token = null;

        if ($authHeader) {
            $headerValue = $authHeader->getValue();
            if (preg_match('/Bearer\s(\S+)/', $headerValue, $matches)) {
                $token = $matches[1];
            }
        }

        if (!$token) {
            return $this->respond(['status' => 'error', 'message' => 'Token not provided'], 401);
        }

        try {
            $key = (new JWTConfig())->jwt_key;
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            $userId = $decoded->data->id;

            $title = $this->request->getVar('title');
            $description = $this->request->getVar('description');
            $visibility = $this->request->getVar('visibility') ?? 'public';
            $invitedSuppliers = $this->request->getVar('invited_suppliers');

            // Validate required fields
            if (empty($title) || empty($description)) {
                return $this->respond(['status' => 'error', 'message' => 'Title and description are required'], 400);
            }

            // Save tender to tenders table
            $tenderId = $this->tenderModel->insert([
                'title' => $title,
                'description' => $description,
                'visibility' => $visibility,
                'creator_id' => $userId
            ]);

            // Save all invited_suppliers to invites table, regardless of visibility
            if (!empty($invitedSuppliers) && is_array($invitedSuppliers)) {
                $inviteModel = new InviteModel();
                foreach ($invitedSuppliers as $email) {
                    $inviteModel->insert([
                        'tender_id' => $tenderId,
                        'supplier_email' => $email
                    ]);
                }
            }

            return $this->respond(['status' => 'success', 'message' => 'Tender created successfully'], 201);
        } catch (\Exception $e) {
            return $this->respond(['status' => 'error', 'message' => 'Invalid token'], 401);
        }
    }

    public function listContractor()
    {
        $authHeader = $this->request->getHeader('Authorization');
        $token = null;
        if ($authHeader) {
            $headerValue = $authHeader->getValue();
            if (preg_match('/Bearer\s(\S+)/', $headerValue, $matches)) {
                $token = $matches[1];
            }
        } elseif ($this->request->getCookie('token')) {
            $token = $this->request->getCookie('token');
        }

        if (!$token) {
            return redirect()->to('/login')->with('error', 'You need to log in.');
        }

        try {
            $key = (new JWTConfig())->jwt_key;
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $userId = $decoded->data->id;

            $tenders = $this->tenderModel->where('creator_id', $userId)->findAll();

            return view('tender/list_contractor', [
                'title' => 'My Tenders (Contractor)',
                'tenders' => $tenders
            ]);
        } catch (\Exception $e) {
            return redirect()->to('/login')->with('error', 'Invalid or expired token.');
        }
    }

    public function listSupplier()
    {
        $authHeader = $this->request->getHeader('Authorization');
        $token = null;
        if ($authHeader) {
            $headerValue = $authHeader->getValue();
            if (preg_match('/Bearer\s(\S+)/', $headerValue, $matches)) {
                $token = $matches[1];
            }
        } elseif ($this->request->getCookie('token')) {
            $token = $this->request->getCookie('token');
        }

        if (!$token) {
            return redirect()->to('/login')->with('error', 'You need to log in.');
        }

        try {
            $key = (new JWTConfig())->jwt_key;
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $userId = $decoded->data->id;
            $userEmail = $decoded->data->email;

            $publicTenders = $this->tenderModel
                ->where('visibility', 'public')
                ->where('creator_id !=', $userId)
                ->findAll();

            $inviteModel = new InviteModel();
            $invites = $inviteModel->where('supplier_email', $userEmail)->findAll();
            $invitedTenderIds = array_column($invites, 'tender_id');

            $privateTenders = [];
            if (!empty($invitedTenderIds)) {
                $privateTenders = $this->tenderModel->whereIn('id', $invitedTenderIds)->where('visibility', 'private')->findAll();
            }

            return view('tender/list_supplier', [
                'title' => 'Available Tenders (Supplier)',
                'publicTenders' => $publicTenders,
                'privateTenders' => $privateTenders
            ]);
        } catch (\Exception $e) {
            return redirect()->to('/login')->with('error', 'Invalid or expired token.');
        }
    }

    public function editTender($id)
    {
        $tender = $this->tenderModel->find($id);
        if (!$tender) {
            return redirect()->to('/tender/list_contractor')->with('error', 'Tender not found.');
        }

        $inviteModel = new InviteModel();
        $invitedSuppliers = $inviteModel->where('tender_id', $id)->findAll();
        $invitedEmails = array_column($invitedSuppliers, 'supplier_email');

        return view('tender/edit', [
            'tender' => $tender,
            'invitedSuppliers' => $invitedEmails
        ]);
    }

    public function updateTender($id)
    {
        $tenderModel = new TenderModel();
        $inviteModel = new InviteModel();

        $data = $this->request->getJSON(true);
        $title = $data['title'];
        $description = $data['description'];
        $visibility = $data['visibility'];
        $invitedSuppliers = $data['invited_suppliers'] ?? [];

        if (empty($title) || empty($description)) {
            return $this->respond(['status' => 'error', 'message' => 'Title and description are required.'], 400);
        }

        $tenderModel->update($id, [
            'title' => $title,
            'description' => $description,
            'visibility' => $visibility
        ]);

        $inviteModel->where('tender_id', $id)->delete();
        foreach ($invitedSuppliers as $email) {
            $inviteModel->insert([
                'tender_id' => $id,
                'supplier_email' => $email
            ]);
        }

        return $this->respond(['status' => 'success', 'message' => 'Tender updated successfully.']);
    }

    private function extractToken($authHeader)
    {
        $token = null;

        if ($authHeader) {
            $headerValue = $authHeader->getValue();
            if (preg_match('/Bearer\s(\S+)/', $headerValue, $matches)) {
                $token = $matches[1];
            }
        } elseif ($this->request->getCookie('token')) {
            $token = $this->request->getCookie('token');
        }

        return $token;
    }

    public function deleteTender($id)
    {
        $authHeader = $this->request->getHeader('Authorization');
        $token = $this->extractToken($authHeader);

        if (!$token) {
            return $this->respond(['status' => 'error', 'message' => 'You need to log in.'], 401);
        }

        try {
            $key = (new JWTConfig())->jwt_key;
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $userId = $decoded->data->id;

            $tender = $this->tenderModel->find($id);
            if (!$tender || $tender['creator_id'] != $userId) {
                return $this->respond(['status' => 'error', 'message' => 'Tender not found or you do not have permission to delete this tender.'], 403);
            }

            $this->tenderModel->delete($id);
            $inviteModel = new InviteModel();
            $inviteModel->where('tender_id', $id)->delete();

            return $this->respond(['status' => 'success', 'message' => 'Tender deleted successfully.']);
        } catch (\Exception $e) {
            return $this->respond(['status' => 'error', 'message' => 'Invalid or expired token.'], 401);
        }
    }

    public function viewTender($id)
{
    $tender = $this->tenderModel->find($id);
    if (!$tender) {
        return redirect()->to('/tender/list_supplier')->with('error', 'Tender not found.');
    }
    $invitedSuppliers = [];
    if ($tender['visibility'] === 'private') {
        $inviteModel = new InviteModel();
        $invitedSuppliers = $inviteModel->where('tender_id', $id)->findAll();
    }

    return view('tender/view', [
        'tender' => $tender,
        'invitedSuppliers' => $invitedSuppliers
    ]);
}
}