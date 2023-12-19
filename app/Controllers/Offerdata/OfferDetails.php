<?php

namespace App\Controllers\Offerdata;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class OfferDetails extends BaseController
{

    use ResponseTrait;

    public function __construct()
    {
        parent::__construct();
        $this->returnResponse = ['status' => '0', 'response' => ''];

    }

    // public function create_data() {
    //     try {
    //         $phone_number = (string)$this->request->getPostGet('phone_number');
    //         if ($phone_number != '') {

    //                 $user_id = $this->UUID4();
    //                 $arr = array( "a"=>"Dosai-50% offer", "b"=>"Idly-50% offer", "c"=>"Snacks-50% offer", "d"=>"Starters-50% offer" );
    //                 $key = array_rand($arr);
    //                 $offer_data=$arr[$key];
    //                 $mobile= $phone_number;

    //                     $Dataarr=[
    //                         'userid'=>$user_id,
    //                         'mobile'=>$mobile,
    //                         'offerdata'=>$offer_data,
    //                         // 'claimstatus'=>0
    //                     ];

    //                 $this->UserofferModel->insert_data($Dataarr);
    //                 $this->returnResponse['status'] = '1';
    //                 $this->returnResponse['response'] = [
    //                     "message" => lang('app.success'),
    //                     "data" => $Dataarr,
    //                 ];

    //         } else {
    //             $this->returnResponse['response'] = $this->get_api_error(400);
    //         }

    //     } catch (MongoException $ex) {
    //         $this->returnResponse['response'] = $this->get_api_error(401);
    //     }
    //     return $this->setResponseFormat('json')->respond($this->returnResponse, 200);

    // }

    public function updateqr_status()
    {
        try {
            $qr_id = (string) $this->request->getPostGet('qr_id');
            $claimed_branch = (string) $this->request->getPostGet('claimed_branch');
            $admin_user = (string) $this->request->getPostGet('admin_user');
            $admin_id = (string) $this->request->getPostGet('admin_id');

            $checkauth = $this->authkey_setting();
            if ($checkauth == '1') {
                if ($qr_id != "" && $claimed_branch != "" && $admin_user != "") {

                    $condition = ['qrId' => $qr_id];
                    $getqrDetails = $this->QrcodeModel->get_all_details("qrcodes", $condition);

                    if (sizeof($getqrDetails) == 1) {

                        $coupdata = $getqrDetails[0];
                        $type = $coupdata['couponType'];
                        $phone = $coupdata['customerphone'];

                        if ($coupdata['isClaimed'] == 1) {

                            $qrValidation = $this->qr_validation($type, $phone);

                            if ($qrValidation['status'] == "1") {

                                $this->returnResponse['response'] = lang('app.restrict_claim') . "$type";

                            } else {

                                $cond = ['qrId' => $qr_id];
                                $update_data = [
                                    'adminuser' => $admin_user,
                                    'claimedbranch' => $claimed_branch,
                                    'isClaimed' => 0,
                                    'isActive' => 0,
                                    'claimeddate' => date('Y-m-d H:i:s'),
                                    'claimedtime' => date('H:i:s'),

                                    'adminid' => $admin_id,

                                ];

                                $this->UserofferModel->update_data("qrcodes", $update_data, $cond);
                                $this->returnResponse['status'] = '1';
                                $this->returnResponse['response'] = lang('app.coupon_success');

                            }

                        } else {

                            $this->returnResponse['response'] = lang('app.already_claim');

                        }

                    } else {
                        $this->returnResponse['response'] = lang('app.invalid_qr');
                    }

                } else {
                    $this->returnResponse['response'] = $this->get_api_error(400);
                }

            } else {
                $this->returnResponse['response'] = $this->get_api_error(403);
            }

        } catch (MongoException $ex) {
            $this->returnResponse['response'] = $this->get_api_error(401);
        }
        return $this->setResponseFormat('json')->respond($this->returnResponse, 200);

    }

    public function admincoupon_details()
    {
        try {

            $admin_id = (string) $this->request->getPostGet('admin_id');
            $claimedDate = (string) $this->request->getPostGet('claimedDate');
            $checkauth = $this->authkey_setting();
            if ($checkauth == '1') {

                if ($admin_id != "" && $claimedDate != "") {

                    $condition = ['adminid' => $admin_id, 'claimeddate' => $claimedDate];
                    $getqrDetails = $this->QrcodeModel->get_all_details("qrcodes", $condition);
                    $getqrcount = $this->QrcodeModel->get_all_counts("qrcodes", $condition);

                    if (sizeof($getqrDetails) > 0) {

                        foreach ($getqrDetails as $admindata) {

                            $cond = ['phone' => $admindata['customerphone']];
                            $getuserDetails = $this->QrcodeModel->get_selected_fields("customers", $cond, 'name');

                            $Dataarr[] = [
                                'qrid' => $admindata['qrId'],
                                'name' => $getuserDetails[0]['name'],
                            ];

                        }

                        $this->returnResponse['status'] = '1';
                        $this->returnResponse['response'] = [
                            'message' => lang('app.success'),
                            'totalcounts' => $getqrcount,
                            'data' => $Dataarr,
                        ];

                    } else {
                        $this->returnResponse['response'] = lang('app.no_data');
                    }

                } else {
                    $this->returnResponse['response'] = $this->get_api_error(400);
                }

            } else {
                $this->returnResponse['response'] = $this->get_api_error(403);
            }

        } catch (MongoException $ex) {
            $this->returnResponse['response'] = $this->get_api_error(401);
        }
        return $this->setResponseFormat('json')->respond($this->returnResponse, 200);

    }

    public function qr_validation($type, $phone)
    {
        try {

        $today_date = date('Y-m-d');
        $condition = ['claimeddate' => $today_date, 'couponType' => $type, 'customerphone' => $phone];
        $getqrDetails = $this->QrcodeModel->get_all_details("qrcodes", $condition);

        if (sizeof($getqrDetails) >= 2) {

            $this->returnResponse['status'] = '1';

        } else {
            $this->returnResponse['status'] = '0';
        }

        } catch (MongoException $ex) {
            $this->returnResponse['response'] = $this->get_api_error(401);
        }
        return $this->returnResponse;

    }

}
