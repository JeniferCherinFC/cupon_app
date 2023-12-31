<?php

namespace App\Models;

use CodeIgniter\Model;

class CouponCustomerModel extends BaseModel {
    protected $DBGroup          = 'default';
    protected $table            = 'couponcustomers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [

        'id',
        'customerPhone',
        'couponSourcebranch',
        'couponCode',
        'subId',
        'couponvalidityStart',
        'couponvalidityEnd',
        'cuponType',
        'isBreakfast',
        'isLunch',
        'isDinner',
        'allowedCouponPerclaim',
        'allowedCouponPerday',
        'purchaseddate',
        'isActive',
        'createdby',
        'modeofpayment',
        'amountPaid',
        'paymentDetail',
        'remarks'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
