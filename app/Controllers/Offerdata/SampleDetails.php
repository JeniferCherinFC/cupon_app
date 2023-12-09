<?php

namespace App\Controllers\Offerdata;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class SampleDetails extends BaseController
{

    
    use ResponseTrait;

    public function __construct()
    {
        parent::__construct();
        $this->returnResponse = ['status' => '0', 'response' => ''];

    }

    public function create_data() {
        try {
			$phone_number = (string)$this->request->getPostGet('phone_number');
            if ($phone_number != '') {
               
                    $user_id = $this->UUID4();
                    $arr = array( "a"=>"Dosai-50% offer", "b"=>"Idly-50% offer", "c"=>"Snacks-50% offer", "d"=>"Starters-50% offer" );
                    $key = array_rand($arr);
                    $offer_data=$arr[$key];
                    $mobile= $phone_number;
                                        
                        $Dataarr=[
                            'userid'=>$user_id,
                            'mobile'=>$mobile,
                            'offerdata'=>$offer_data,
                            // 'claimstatus'=>0
                        ];

                    $this->UserofferModel->insert_data($Dataarr);
                    $this->returnResponse['status'] = '1';
                    $this->returnResponse['response'] = [
                        "message" => lang('app.success'),
                        "data" => $Dataarr,
                    ];
               
            } else {
                $this->returnResponse['response'] = $this->get_api_error(400);
            }

        } catch (MongoException $ex) {
            $this->returnResponse['response'] = $this->get_api_error(401);
        }
        return $this->setResponseFormat('json')->respond($this->returnResponse, 200);

    }





    public function updateoffer_status() {
        try {
			$user_id = (string)$this->request->getPostGet('user_id');
			$phone_number = (string)$this->request->getPostGet('phone_number');

            if ($phone_number != '' && $user_id!="" ) {


                $condition = ['claimstatus'=>0,'userid'=>$user_id];
                $getpost = $this->UserofferModel->get_all_details("useroffer", $condition);
             
                if (sizeof($getpost) >= 1) {

                    $cond = ['userid'=>$user_id];
                    $update_data= ['claimstatus' => 1];
                    $this->UserofferModel->update_data("useroffer", $update_data,$cond);
                    $this->returnResponse['status'] = '1';
                    $this->returnResponse['response'] ="Sucessfully claimed";
                } else {
                    $this->returnResponse['response'] ="Already claimed";
                }
               
            } else {
                $this->returnResponse['response'] = $this->get_api_error(400);
            }

        } catch (MongoException $ex) {
            $this->returnResponse['response'] = $this->get_api_error(401);
        }
        return $this->setResponseFormat('json')->respond($this->returnResponse, 200);

    }







}