<?php

namespace App\Controllers\Biller;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class BillerController extends BaseController
{

    
    use ResponseTrait;

    public function __construct()
    {
        parent::__construct();
        $this->returnResponse = ['status' => '0', 'response' => ''];

    }

    public function biller_login() {
        try {
			$phone_number = (string)$this->request->getPostGet('phone_number');
			$password = (string)$this->request->getPostGet('password');
            $checkauth= $this->authkey_setting();
            if($checkauth == "1"){
             
                if ($phone_number != '' && $password != '') {
               
                
                    $condition=['phone'=>$phone_number,'password'=>$password];
                    $getdetail = $this->UserofferModel->get_all_details("adminusers", $condition);

                 
                       if(sizeof($getdetail)==1){
                       $isuser=$getdetail[0];
                       $name=$isuser['username'];
                       $Dataarr=[
                        'admin_phone'=>$phone_number,
                        'admin_username'=>$name,
                        'logintime'=>date('Y-m-d H:i:s'),

                       ];
                        $this->AdminLoginHisModel->insert_data($Dataarr);
                        $this->returnResponse['status'] = '1';
                        $this->returnResponse['response'] = [
                            "message" => lang('app.success'),
                            "data" =>$isuser,
                        ];
                       }
                    //    elseif(sizeof($getdetail)>1){
                    //     $this->returnResponse['response'] = lang('app.phone_number_exist');
                    //    } 
                       else{
                        $this->returnResponse['response'] = lang('app.admin_user');
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





   






}

