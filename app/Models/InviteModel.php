<?php

namespace App\Models;

use CodeIgniter\Model;

class InviteModel extends Model {
    protected $table = 'invites';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tender_id', 'supplier_email'];
}