<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;



class AdminController extends BaseController
{

    
    use ResponseTrait;

    public function __construct()
    {
        parent::__construct();
        $this->returnResponse = ['status' => '0', 'response' => ''];

    }

    public function admin_login() {
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




    public function create_user() {
        try {
			$phone_number = (string)$this->request->getPostGet('phone_number');
			$name = (string)$this->request->getPostGet('name');
			$email = (string)$this->request->getPostGet('email');
			$customerbranch = (string)$this->request->getPostGet('customerbranch');
			$location = (string)$this->request->getPostGet('location');
			$address = (string)$this->request->getPostGet('address');
			$city = (string)$this->request->getPostGet('city');
			$state = (string)$this->request->getPostGet('state');
			$pincode = (string)$this->request->getPostGet('pincode');
			$country = (string)$this->request->getPostGet('country');
			$startdate= (string)$this->request->getPostGet('startdate');
			$enddate = (string)$this->request->getPostGet('enddate');
			$coupontype = (string)$this->request->getPostGet('coupontype');
            $paymentmode=(string)$this->request->getPostGet('paymentmode');
            $amountpaid=(string)$this->request->getPostGet('amountpaid');
            $createdby=(string)$this->request->getPostGet('createdby');

            

//userid,password,cuponcode

            $checkauth= $this->authkey_setting();
            if($checkauth == "1"){
             
                if ($phone_number != '' && $name != ''&& $email != ''&& $customerbranch != ''&& $location != ''&& $address != ''&& $city != ''&& $state != ''&& $pincode != ''&& $country != ''&& $startdate != ''&& $enddate != ''&& $coupontype != ''&& $paymentmode != ''&& $amountpaid != '') {
               
                
                    $condition=['phone'=>$phone_number];
                    $getdetail = $this->CustomerModel->get_all_details("customers", $condition);

                    $condition=['branch'=>$customerbranch];
                    $getbranch = $this->BranchModel->get_all_details("branch", $condition);

                    if(sizeof($getbranch)==1){
                        $isbranch=$getbranch[0];
                        $branchcode=$isbranch['branch_code'];
                        if(sizeof($getdetail)>=1){
                            $this->returnResponse['response'] = lang('app.phone_number_exist');
                           }else{
    
                            $userid=$this->UUID4();
                            // $str =$name; 
                            // $password= $this->get_password($str, 8);
                            $str =  
"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789"; 
$password= $this->get_password($str, 8);  


    // $password="hhhhhhhhhhhhh";
                            $userdata=[
                                    'name'=>$name,
                                    'phone'=>$phone_number,
                                    'userid'=>$userid,
                                    'email'=>$email,
                                    'location'=>$location,
                                    'customerbranch'=>$branchcode,
                                    'address'=>$address,
                                    'city'=>$city,
                                    'state'=>$state,
                                    'country'=>$country,
                                    'pincode'=>$pincode,
                                    'password'=>$password       
                            ];
    
    
    
    
                            $data="$branchcode".","."$coupontype";
    
                            $couponcodegeneration= $this->generateQRCode($data);
    
                  
                            $Dataarr=[
                                'customerPhone'=>$phone_number,
                                'couponSourcebranch'=>$branchcode,
                                'couponCode'=>$couponcodegeneration,
                                'couponvalidityStart'=>date('Y-m-d'),
                                'couponvalidityEnd'=>date('Y-m-d'),
                                'cuponType'=>$coupontype,
                                'purchaseddate'=>date('Y-m-d'),
                                'createdby'=>$createdby,
                                'modeofpayment'=>$paymentmode,
                                'amountPaid'=>$amountpaid
                            ];
     
     
     
                             $this->CustomerModel->insert_data($userdata);
                            //  $this->CouponCustomerModel->insert_data($Dataarr);
                             $this->returnResponse['status'] = '1';
                             $this->returnResponse['response'] = [
                                 "message" => lang('app.success'),
                                 "data" =>$password,
                             ];
                           } 
                   

                    }else{
                        $this->returnResponse['response'] ="Branch doesnot exists"; 
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

