<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Helpers\TwilioHelper;
use chillerlan\QRCode\QRCode;

date_default_timezone_set('Asia/Kolkata');


/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    
    Public $Authkey = '';
    protected $helpers = [];
    protected $uri;
    protected $BaseModel;
    protected $LoginModel;
    protected $UserofferModel;
    protected $LoginhistoryModel;
    protected $AdminUserModel;
	protected $AdminLoginHisModel;
	protected $CustomerModel;
	protected $CouponCustomerModel;
	protected $BranchModel;
	protected $QrcodeModel;

	

    // protected $TwilioService;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */





     public function __construct(){


		$this->UserofferModel = new \App\Models\UserofferModel();
		$this->LoginModel = new \App\Models\LoginModel();
		$this->LoginhistoryModel = new \App\Models\LoginhistoryModel();
		$this->AdminUserModel = new \App\Models\AdminUserModel();
		$this->AdminLoginHisModel = new \App\Models\AdminLoginHisModel();
		$this->CustomerModel = new \App\Models\CustomerModel();
		$this->CouponCustomerModel = new \App\Models\CouponCustomerModel();
		$this->QrcodeModel = new \App\Models\QrcodeModel();

		
		$this->BranchModel = new \App\Models\BranchModel();




		
        $this->request = \Config\Services::request();

		helper(['api']);
        helper(['request']);
		helper(['date']);

        $response = service('response');
        $request = service('request');
		

    
        // $headers = $request->getHeader('Authkey');
        // $response->setHeader('Authkey', $headers);

        // print_r($headers );
        $router = service('router');
	
	}


	public  function authkey_setting(){
        $response = service('response');
        $request = service('request');
        $authheader = $request->getHeader('Authkey');
        $userheader = $request->getHeader('Usertype');
            $headers = $this->request->headers();
            array_walk($headers, function(&$value, $key) {
                $value = $value->getValue();
            });

         
            if($authheader =="" || $userheader==""){
                $status="Authentication Failure";
                  return $status;
            }else{
                if( $headers['Usertype']=='uSer'&& $headers['Authkey']=='ABC123'){
                   $status="1";
                   $this->authheader= $headers['Usertype'];
                   $this->userheader= $headers['Authkey'];
                   return $status;
                }elseif( $headers['Usertype']=='aDmin'&& $headers['Authkey']=='ABC123'){
                  $status="1";
                  $this->authheader= $headers['Usertype'];
                  $this->userheader= $headers['Authkey'];
                  return $status;
                }elseif( $headers['Usertype']=='cAptain'&& $headers['Authkey']=='ABC123'){
					$status="1";
					$this->authheader= $headers['Usertype'];
					$this->userheader= $headers['Authkey'];
					return $status;
				  }elseif( $headers['Usertype']=='bIller'&& $headers['Authkey']=='ABC123'){
					$status="1";
					$this->authheader= $headers['Usertype'];
					$this->userheader= $headers['Authkey'];
					return $status;
				  }else{
                  $status="0";
                  return $status;
                }
            }
    }



	public function UUID4() {
		$data = $data ?? random_bytes(16);
		assert(strlen($data) == 16);
		// Set version to 0100
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		// Set bits 6-7 to 10
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
		// Output the 36 character UUID.
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}


    public function generateQRCode($data)
    {
        // Create a QRCode instance
        $qrcode = new QRCode();
        // Set additional options if needed
        // $qrcode->setModuleValues([1, 255]);
        // $qrcode->setSize(300);
        
        // Generate the QR code as a PNG image
        $image = $qrcode->render($data);

        // Output the QR code image
        // header('Content-Type: image/png');
        // $this->returnResponse['response'] = [
        //     "message" => lang('app.success'),
        //     "data" =>$image,
        // ];

		return $image;
        // return $this->setResponseFormat('json')->respond($this->returnResponse, 200);


        
    }




	public function get_subid($str, $len = 0) { 
      
		$pass = ""; 
		$str_length = strlen($str); 
		if($len == 0 || $len > $str_length){ 
			$len = $str_length; 
		} 
		for($i = 0;  $i < $len; $i++){ 
			$pass .=  $str[rand(0, $str_length)]; 
		} 
		return $pass; 
	} 



		public	function generatePassword($length) {
			$uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$lowercase = 'abcdefghijklmnopqrstuvwxyz';
			$numbers = '0123456789';
		
			$characters = $uppercase . $lowercase . $numbers;
			$charLength = strlen($characters) - 1;
		
			// Add one uppercase letter
			$password = $uppercase[mt_rand(0, strlen($uppercase) - 1)];
		
			// Add a mix of numbers and lowercase letters
			for ($i = 1; $i < $length - 1; $i++) {
				$randIndex = mt_rand(0, $charLength);
				$password .= $characters[$randIndex];
			}
		
			// Add one number
			$password .= $numbers[mt_rand(0, strlen($numbers) - 1)];
		
			return $password ; // Shuffle the password to randomize character positions
		}
		
	



   

  public  function get_api_error($status_code=NULL,$status_message=NULL,$show=FALSE) {
		if($status_message==NULL){
			switch($status_code){
				case '400':
					$message = 'Some informations missing. Please try again later.';
				break;
				case '401':
					$message = 'Something went wrong. Please try again later.';
				break;
				case '402':
					$message = 'Authentication Failure.';
				break;
				case '403':
					$message = 'Authentication Missing.';
				break;
				case '404':
					$message = 'Unknown Method.';
				break;
				case '405':
					$message = 'Account modified, Login again.';
				break;
				case '406':
					$message = 'Account was used in another device.';
				break;
				case '407':
					$message = 'Server couldnot process this request.';
				break;
			}
		}else{
			$message = $status_message;
		}
		if($show){
			$response = service('response');
			$response->setStatusCode($status_code)->setBody(json_encode(['error'=>$message]))->setHeader('Content-type', 'application/json')->noCache()->send();
			exit();
		}
		return $message;
	}


    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }





}
