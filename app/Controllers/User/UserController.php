<?php

namespace App\Controllers\User;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class UserController extends BaseController
 {

    use ResponseTrait;

    public function __construct()
    {
        parent::__construct();
        $this->returnResponse = [ 'status' => '0', 'response' => '' ];

    }

    public function login() {
        try {
            $phone_number = ( string )$this->request->getPostGet( 'phone_number' );
            $password = ( string )$this->request->getPostGet( 'password' );
            $checkauth = $this->authkey_setting();
            if ( $checkauth == '1' ) {

                if ( $phone_number != '' && $password != '' ) {
                    $condition = [ 'phone'=>$phone_number,'password'=>$password ];
                    $getdetail = $this->UserofferModel->get_all_details( 'customers', $condition );

                    if ( sizeof( $getdetail ) == 1 ) {

                        $isuser = $getdetail[ 0 ];
                        $Dataarr = [
                            'customerphone'=>$phone_number
                        ];
                        $this->LoginhistoryModel->insert_data( $Dataarr );
                        $this->returnResponse[ 'status' ] = '1';
                        $this->returnResponse[ 'response' ] = [
                            'message' => lang( 'app.success' ),
                            'data' =>$isuser,
                        ];
                    } else {
                        $this->returnResponse[ 'response' ] = lang( 'app.invalid_user' );
                    }

                } else {
                    $this->returnResponse[ 'response' ] = $this->get_api_error( 400 );
                }

            } else {
                $this->returnResponse[ 'response' ] = $this->get_api_error( 403 );
            }

        } catch ( MongoException $ex ) {
            $this->returnResponse[ 'response' ] = $this->get_api_error( 401 );
        }
        return $this->setResponseFormat( 'json' )->respond( $this->returnResponse, 200 );

    }

    public function get_coupondetails() {
        $phone_number = ( string )$this->request->getPostGet( 'phone_number' );

        try {
            $checkauth = $this->authkey_setting();
            if ( $checkauth == '1' ) {

                if ( $phone_number != '' ) {

                    $condition = [ 'customerphone'=>$phone_number, 'isClaimed'=>1 ];
                    $totalAvailable = $this->QrcodeModel->get_all_counts( 'qrcodes', $condition );

                    $cond = [ 'customerphone'=>$phone_number, 'isClaimed'=>0 ];
                    $totalUsed = $this->QrcodeModel->get_all_counts( 'qrcodes', $cond );

                    $bAcond = [ 'customerphone'=>$phone_number, 'isClaimed'=>1, 'couponType'=>'Breakfast' ];
                    $bAvailable = $this->QrcodeModel->get_all_counts( 'qrcodes', $bAcond );

                    $bUcond = [ 'customerphone'=>$phone_number, 'isClaimed'=>0, 'couponType'=>'Breakfast' ];
                    $bUsed = $this->QrcodeModel->get_all_counts( 'qrcodes', $bUcond );

                    $lAcond = [ 'customerphone'=>$phone_number, 'isClaimed'=>1, 'couponType'=>'Lunch' ];
                    $lAvailable = $this->QrcodeModel->get_all_counts( 'qrcodes', $lAcond );

                    $lUcond = [ 'customerphone'=>$phone_number, 'isClaimed'=>0, 'couponType'=>'Lunch' ];
                    $lUsed = $this->QrcodeModel->get_all_counts( 'qrcodes', $lUcond );

                    $dAcond = [ 'customerphone'=>$phone_number, 'isClaimed'=>1, 'couponType'=>'Dinner' ];
                    $dAvailable = $this->QrcodeModel->get_all_counts( 'qrcodes', $dAcond );

                    $dUcond = [ 'customerphone'=>$phone_number, 'isClaimed'=>0, 'couponType'=>'Dinner' ];
                    $dUsed = $this->QrcodeModel->get_all_counts( 'qrcodes', $dUcond );

                    $Dataarr = [
                        'totalAvailable'=>$totalAvailable,
                        'totalUsed'=>$totalUsed,
                        'bAvailable'=>$bAvailable,
                        'bUsed'=>$bUsed,
                        'lAvailable'=>$lAvailable,
                        'lUsed'=>$lUsed,
                        'dAvailable'=>$dAvailable,
                        'dUsed'=>$dUsed

                    ];
                    $this->returnResponse[ 'status' ] = '1';
                    $this->returnResponse[ 'response' ] = [
                        'message' => lang( 'app.success' ),
                        'data' => $Dataarr,
                    ];

                } else {
                    $this->returnResponse[ 'response' ] = $this->get_api_error( 400 );
                }

            } else {
                $this->returnResponse[ 'response' ] = $this->get_api_error( 403 );
            }

        } catch ( MongoException $ex ) {
            $this->returnResponse[ 'response' ] = $this->get_api_error( 401 );
        }
        return $this->setResponseFormat( 'json' )->respond( $this->returnResponse, 200 );

    }

    public function get_couponAvailable_details() {
        $phone_number = ( string )$this->request->getPostGet( 'phone_number' );
        $coupon_type = ( string )$this->request->getPostGet( 'coupon_type' );

        try {
            $checkauth = $this->authkey_setting();
            if ( $checkauth == '1' ) {

                if ( $phone_number != '' && $coupon_type != '' ) {

                    $Acond = [ 'customerphone'=>$phone_number, 'isClaimed'=>1, 'isActive'=>1 ,'couponType'=>$coupon_type ];
                    $totalAvailable = $this->QrcodeModel->get_all_counts( 'qrcodes', $Acond );
                    $getAvailable = $this->QrcodeModel->get_all_details( 'qrcodes', $Acond );

                    $Dataarr = [
                        'totalAvailable'=>$totalAvailable,
                        'details'=>$getAvailable,

                    ];

                    $this->returnResponse[ 'status' ] = '1';
                    $this->returnResponse[ 'response' ] = [
                        'message' => lang( 'app.success' ),
                        'data' => $Dataarr,
                    ];

                } else {
                    $this->returnResponse[ 'response' ] = $this->get_api_error( 400 );
                }

            } else {
                $this->returnResponse[ 'response' ] = $this->get_api_error( 403 );
            }

        } catch ( MongoException $ex ) {
            $this->returnResponse[ 'response' ] = $this->get_api_error( 401 );
        }
        return $this->setResponseFormat( 'json' )->respond( $this->returnResponse, 200 );

    }

    public function get_couponUsed_details() {
        $phone_number = ( string )$this->request->getPostGet( 'phone_number' );
        $coupon_type = ( string )$this->request->getPostGet( 'coupon_type' );

        try {
            $checkauth = $this->authkey_setting();
            if ( $checkauth == '1' ) {

                if ( $phone_number != '' && $coupon_type != '' ) {

                    $Acond = [ 'customerphone'=>$phone_number, 'isClaimed'=>0,'isActive'=>0, 'couponType'=>$coupon_type ];
                    $totalUsed = $this->QrcodeModel->get_all_counts( 'qrcodes', $Acond );
                    $getUsed = $this->QrcodeModel->get_all_details( 'qrcodes', $Acond );

                    $Dataarr = [
                        'totalUsed'=>$totalUsed,
                        'details'=>$getUsed,

                    ];

                    $this->returnResponse[ 'status' ] = '1';
                    $this->returnResponse[ 'response' ] = [
                        'message' => lang( 'app.success' ),
                        'data' => $Dataarr,
                    ];

                } else {
                    $this->returnResponse[ 'response' ] = $this->get_api_error( 400 );
                }

            } else {
                $this->returnResponse[ 'response' ] = $this->get_api_error( 403 );
            }

        } catch ( MongoException $ex ) {
            $this->returnResponse[ 'response' ] = $this->get_api_error( 401 );
        }
        return $this->setResponseFormat( 'json' )->respond( $this->returnResponse, 200 );

    }

    public function get_subscription_details() {
        $phone_number = ( string )$this->request->getPostGet( 'phone_number' );

        try {
            $checkauth = $this->authkey_setting();
            if ( $checkauth == '1' ) {

                if ( $phone_number != '' ) {

                    $Acond = [ 'customerphone'=>$phone_number ];
                    $getdata = $this->CouponCustomerModel->get_all_details( 'couponcustomers', $Acond );

                    if ( sizeof( $getdata )> 0 ) {
                        foreach ( $getdata as $subdata ) {

                            if ( $subdata[ 'isBreakfast' ] == 1 ) {
                                $data = 'Breakfast';
                            } else {
                                $data = '';
                            }
                            if ( $subdata[ 'isLunch' ] == 1 ) {
                                $data1 = 'Lunch';
                            } else {
                                $data1 = '';
                            }
                            if ( $subdata[ 'isDinner' ] == 1 ) {
                                $data2 = 'Dinner';
                            } else {
                                $data2 = '';
                            }

                            $coupon_type = $data.','.$data1.','.$data2;

                            $cond = [ 'branch_code'=>$subdata[ 'couponSourcebranch' ] ];
                            $getbranch = $this->BranchModel->get_selected_fields( 'branch', $cond, 'branch' );
                            if ( sizeof( $getbranch ) == 1 ) {
                                $isbranch = $getbranch[ 0 ];
                                $branchcode = $isbranch[ 'branch' ];
                            } else {
                                $branchcode = '';
                            }
                            $Dataarr[] = [

                                'subId'=>$subdata[ 'subId' ],
                                'dop'=>$subdata[ 'purchaseddate' ],
                                'coupontype'=>$coupon_type,
                                'branch'=>$branchcode,
                                'sdate'=>$subdata[ 'couponvalidityStart' ],
                                'edate'=>$subdata[ 'couponvalidityEnd' ],

                            ];
                        }
                        $this->returnResponse[ 'status' ] = '1';
                        $this->returnResponse[ 'response' ] = [
                            'message' => lang( 'app.success' ),
                            'data' => $Dataarr,
                        ];

                    } else {

                        $this->returnResponse[ 'response' ] = lang( 'app.no_data' );

                    }

                } else {
                    $this->returnResponse[ 'response' ] = $this->get_api_error( 400 );
                }

            } else {
                $this->returnResponse[ 'response' ] = $this->get_api_error( 403 );
            }

        } catch ( MongoException $ex ) {
            $this->returnResponse[ 'response' ] = $this->get_api_error( 401 );
        }
        return $this->setResponseFormat( 'json' )->respond( $this->returnResponse, 200 );

    }

    public function changepassword() {
        try {
            $phone_number = ( string )$this->request->getPostGet( 'phone_number' );
            $newpassword = ( string )$this->request->getPostGet( 'newpassword' );
            $checkauth = $this->authkey_setting();
            if ( $checkauth == '1' ) {

                if ( $newpassword != '' && $phone_number != '' ) {

                    $update_data = array( 'password'=> $newpassword );

                    $this->CustomerModel->update_data( 'customers', $update_data, array( 'phone'=>$phone_number ) );

                    $this->returnResponse[ 'status' ] = '1';
                    $this->returnResponse[ 'response' ] = lang( 'app.pass_change' );

                } else {
                    $this->returnResponse[ 'response' ] = $this->get_api_error( 400 );
                }

            } else {
                $this->returnResponse[ 'response' ] = $this->get_api_error( 403 );

            }

        } catch ( MongoException $ex ) {
            $this->returnResponse[ 'response' ] = $this->get_api_error( 401 );
        }
        return $this->setResponseFormat( 'json' )->respond( $this->returnResponse, 200 );

    }

    public function logout() {
        try {
            $checkauth = $this->authkey_setting();
            if ( $checkauth == '1' ) {
                $userid = ( string )$this->request->getPostGet( 'userid' );
                if ( $userid != '' ) {
                    $condition = array( 'userid' => $userid );
                    $checkUser = $this->CustomerModel->get_selected_fields( 'customers', $condition, array( 'userid' ) );
                    if ( sizeof( $checkUser ) == 1 ) {
                        $userarr = $checkUser[ 0 ];
                        $update_data = array( 'lastlogoutDate'=>date( 'Y-m-d' ) );
                        $this->CustomerModel->update_data( 'customers', $update_data, array( 'userid'=>$userarr[ 'userid' ] ) );

                        $this->returnResponse[ 'status' ] = '1';
                        $this->returnResponse[ 'response' ] = lang( 'app.logged_out_successfully' );
                    } else {
                        $this->returnResponse[ 'response' ] = lang( 'app.invalid_user' );
                    }
                } else {
                    $this->returnResponse[ 'response' ] = $this->get_api_error( 400 );
                }

            } else {
                $this->returnResponse[ 'response' ] = $this->get_api_error( 403 );

            }

        } catch ( MongoException $ex ) {
            $this->returnResponse[ 'response' ] = $this->get_api_error( 401 );
        }
        return $this->setResponseFormat( 'json' )->respond( $this->returnResponse, 200 );

    }

    public function editprofile() {
        try {
            $userid = ( string )$this->request->getPostGet( 'userid' );
            $name = ( string )$this->request->getPostGet( 'name' );
            $email = ( string )$this->request->getPostGet( 'email' );
            $location = ( string )$this->request->getPostGet( 'location' );
            $address = ( string )$this->request->getPostGet( 'address' );
            $city = ( string )$this->request->getPostGet( 'city' );
            $state = ( string )$this->request->getPostGet( 'state' );
            $pincode = ( string )$this->request->getPostGet( 'pincode' );
            $country = ( string )$this->request->getPostGet( 'country' );
            $checkauth = $this->authkey_setting();
            if ( $checkauth == '1' ) {
                if ( $userid!='' && $name != '' && $email != '' && $location != '' && $address != '' && $city != '' && $state != '' && $pincode != '' && $country != '' ) {
                   
                    $condition = array( 'userid' => $userid );
                    $checkUser = $this->CustomerModel->get_selected_fields( 'customers', $condition, array( 'userid' ) );

                    if ( sizeof( $checkUser ) == 1 ) {
                        $userarr = $checkUser[ 0 ];
                        $update_data = [
                            'name'=>$name,
                            'email'=>$email,
                            'location'=>$location,
                            'address'=>$address,
                            'city'=>$city,
                            'state'=>$state,
                            'country'=>$country,
                            'pincode'=>$pincode,
                            'updatedat'=>date( 'Y-m-d' )
                        ];

                        $this->CustomerModel->update_data( 'customers', $update_data, array( 'userid'=>$userarr[ 'userid' ] ) );

                        $this->returnResponse[ 'status' ] = '1';
                        $this->returnResponse[ 'response' ] = lang( 'app.update_success' );
                    } else {
                        $this->returnResponse[ 'response' ] = lang( 'app.invalid_user' );
                    }
                } else {
                    $this->returnResponse[ 'response' ] = $this->get_api_error( 400 );
                }

            } else {
                $this->returnResponse[ 'response' ] = $this->get_api_error( 403 );

            }

        } catch ( MongoException $ex ) {
            $this->returnResponse[ 'response' ] = $this->get_api_error( 401 );
        }
        return $this->setResponseFormat( 'json' )->respond( $this->returnResponse, 200 );

    }

}

