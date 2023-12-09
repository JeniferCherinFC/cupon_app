<?php

namespace App\Controllers\User;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Login extends BaseController
{

    
    use ResponseTrait;

    public function __construct()
    {
        parent::__construct();
        $this->returnResponse = ['status' => '0', 'response' => ''];

    }

    public function login() {
        try {
			$phone_number = (string)$this->request->getPostGet('phone_number');
			$password = (string)$this->request->getPostGet('password');
            $checkauth= $this->authkey_setting();
            if($checkauth == "1"){
             
                if ($phone_number != '' && $password != '') {
               
                    // $arr = array( "a"=>"Dosai-50% offer", "b"=>"Idly-50% offer", "c"=>"Snacks-50% offer", "d"=>"Starters-50% offer" );
                    // $key = array_rand($arr);
                    // $offer_data=$arr[$key];
                    $condition=['phone'=>$phone_number];
                    $getdetail = $this->UserofferModel->get_all_details("customers", $condition);


                       if(sizeof($getdetail)==1){

                       $isuser=$getdetail[0];
                       $Dataarr=[
                        'customerphone'=>$phone_number
                       ];
                        $this->LoginhistoryModel->insert_data($Dataarr);
                        $this->returnResponse['status'] = '1';
                        $this->returnResponse['response'] = [
                            "message" => lang('app.success'),
                            "data" =>$isuser,
                        ];
                       }else{
                        $this->returnResponse['response'] = lang('app.invalid_user');
                       } 
               
            } else {
                $this->returnResponse['response'] = $this->get_api_error(400);
            }

        }else{
            $this->returnResponse['response'] = $this->get_api_error(403);
        }


        } catch (MongoException $ex) {
            $this->returnResponse['response'] = $this->get_api_error(401);
        }
        return $this->setResponseFormat('json')->respond($this->returnResponse, 200);

    }





    // public function updateoffer_status() {
    //     try {
	// 		$user_id = (string)$this->request->getPostGet('user_id');
	// 		$phone_number = (string)$this->request->getPostGet('phone_number');

    //         if ($phone_number != '' && $user_id!="" ) {


    //             $condition = ['claimstatus'=>0,'userid'=>$user_id];
    //             $getpost = $this->UserofferModel->get_all_details("useroffer", $condition);
             
    //             if (sizeof($getpost) >= 1) {

    //                 $cond = ['userid'=>$user_id];
    //                 $update_data= ['claimstatus' => 1];
    //                 $this->UserofferModel->update_data("useroffer", $update_data,$cond);
    //                 $this->returnResponse['status'] = '1';
    //                 $this->returnResponse['response'] ="Sucessfully claimed";
    //             } else {
    //                 $this->returnResponse['response'] ="Already claimed";
    //             }
               
    //         } else {
    //             $this->returnResponse['response'] = $this->get_api_error(400);
    //         }

    //     } catch (MongoException $ex) {
    //         $this->returnResponse['response'] = $this->get_api_error(401);
    //     }
    //     return $this->setResponseFormat('json')->respond($this->returnResponse, 200);

    // }







}

