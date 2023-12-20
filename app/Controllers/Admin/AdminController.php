<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\I18n\Time;



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
			$coupontype = $this->request->getPostGet('coupontype');
			$isBreakfast = (int)$this->request->getPostGet('isBreakfast');
			$isLunch =(int) $this->request->getPostGet('isLunch');
			$isDinner =(int) $this->request->getPostGet('isDinner');
            $paymentmode=(string)$this->request->getPostGet('paymentmode');
            $amountpaid=(string)$this->request->getPostGet('amountpaid');
            $createdby=(string)$this->request->getPostGet('createdby');

            $checkauth= $this->authkey_setting();
            if($checkauth == "1"){
             
                if ($phone_number != '' && $name != ''&& $email != ''&& $customerbranch != ''&& $location != ''&& $address != ''&& $city != ''&& $state != ''&& $pincode != ''&& $country != ''&& $startdate != ''&& $enddate != ''&& ($isBreakfast  != ''||$isLunch != ''||$isDinner != '')&& $paymentmode != ''&& $amountpaid != '') {
               
                
                    $condition=['phone'=>$phone_number];
                    $getdetail = $this->CustomerModel->get_all_details("customers", $condition);

                    $condition=['branch'=>$customerbranch];
                    $getbranch = $this->BranchModel->get_all_details("branch", $condition);

                    if(sizeof($getbranch)==1){
                        $isbranch=$getbranch[0];
                        $branchcode=$isbranch['branch_code'];

                        if(sizeof($getdetail)>=1){


                            // if($isBreakfast==1){

                            //     $type1="Breakfast";
                            //     $this->generate_coupon($startdate,$enddate,$type1,$phone_number);
                            
                            // }else{
                            //     $type1="";
                            // }

                            // if($isLunch==1){
                            //     $type2="Lunch";
                            //     $this->generate_coupon($startdate,$enddate,$type2,$phone_number);

                               
                            // }
                            // else{
                            //     $type2="";
                            // }

                            // if($isDinner==1){
                            //     $type3="Dinner";
                            //     $this->generate_coupon($startdate,$enddate,$type3,$phone_number);
                            // }else{
                            //     $type3="";
                            // }


                            // $coupontype="$type1"."$type2"."$type3"; 

                            // $data="$branchcode".","."$coupontype";
                            // $couponcodegeneration= $this->generateQRCode($data);

                            //  $str="ABCDEFGHIJKL1234567890MNOPQRSTUVWXYZ";
                            //  $Id=$this->get_subid($str,6);
                            //  $subId="SUB".$Id;

                            //  $stimestamp = strtotime($startdate);
                            //  $etimestamp = strtotime($enddate);

                            // $Dataarr=[
                            //     'customerPhone'=>$phone_number,
                            //     'couponSourcebranch'=>$branchcode,
                            //     'couponCode'=>(string)$couponcodegeneration,
                            //     'couponvalidityStart'=>date('Y-m-d',$stimestamp),
                            //     'couponvalidityEnd'=>date('Y-m-d',$etimestamp),
                            //     'cuponType'=>$coupontype,
                            //     'isBreakfast'=>$isBreakfast,
                            //     'isLunch'=>$isLunch,
                            //     'isDinner'=>$isDinner,
                            //     'purchaseddate'=>date('Y-m-d'),
                            //     'createdby'=>$createdby,
                            //     'modeofpayment'=>$paymentmode,
                            //     'amountPaid'=>$amountpaid,
                            //     'subId'=>$subId
                            // ];

                            //  $this->CouponCustomerModel->insert_data($Dataarr);
                            $this->returnResponse['response'] = lang('app.phone_number_exist');
                           }else{
    
                            $userid=$this->UUID4();
                            $password= $this->generatePassword(8);  

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
                            if($isBreakfast==1){

                                $type1="Breakfast";
                                $this->generate_coupon($startdate,$enddate,$type1,$phone_number);
                            
                            }else{
                                $type1="";
                            }

                            if($isLunch==1){
                                $type2="Lunch";
                                $this->generate_coupon($startdate,$enddate,$type2,$phone_number);

                               
                            }
                            else{
                                $type2="";
                            }

                            if($isDinner==1){
                                $type3="Dinner";
                                $this->generate_coupon($startdate,$enddate,$type3,$phone_number);
                            }else{
                                $type3="";
                            }


                            $coupontype="$type1"."$type2"."$type3"; 

                            $data="$branchcode".","."$coupontype";
                            $couponcodegeneration= $this->generateQRCode($data);

                             $str="ABCDEFGHIJKL1234567890MNOPQRSTUVWXYZ";
                             $Id=$this->get_subid($str,6);
                             $subId="SUB".$Id;

                             $stimestamp = strtotime($startdate);
                             $etimestamp = strtotime($enddate);

                            $Dataarr=[
                                'customerPhone'=>$phone_number,
                                'couponSourcebranch'=>$branchcode,
                                'couponCode'=>(string)$couponcodegeneration,
                                'couponvalidityStart'=>date('Y-m-d',$stimestamp),
                                'couponvalidityEnd'=>date('Y-m-d',$etimestamp),
                                'cuponType'=>$coupontype,
                                'isBreakfast'=>$isBreakfast,
                                'isLunch'=>$isLunch,
                                'isDinner'=>$isDinner,
                                'purchaseddate'=>date('Y-m-d'),
                                'createdby'=>$createdby,
                                'modeofpayment'=>$paymentmode,
                                'amountPaid'=>$amountpaid,
                                'subId'=>$subId
                            ];

                             $this->CouponCustomerModel->insert_data($Dataarr);
                             $this->CustomerModel->insert_data($userdata);
                             $this->returnResponse['status'] = '1';
                             $this->returnResponse['response'] = [
                                 "message" => lang('app.coupon_gen'),
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
   

    public function generate_coupon($startdate,$enddate,$couponType,$phone){

           
            $startingDate = new \CodeIgniter\I18n\Time($startdate);
            // Example: Get another date (replace this with your specific date)
            $endingDate = new \CodeIgniter\I18n\Time($enddate);
            // Calculate the difference in days
            $daysCount= $startingDate ->difference($endingDate)->getDays();
           
            for ($i = 1; $i <= $daysCount; $i++) {
                $qr_id=$this->UUID4();
                // $data="$phone".","."$couponType".","."$qr_id";
                $data=$qr_id;

                $couponcodegenerations= $this->generateQRCode($data);

                    $coupondata=[
                        'customerphone'=>$phone,
                        'QR'=>$couponcodegenerations,
                        'qrId'=>$qr_id,
                        'couponType'=>$couponType
                    ];
                   
                    $this->QrcodeModel->insert_data($coupondata); 
            }

          
    }




    
   
}

