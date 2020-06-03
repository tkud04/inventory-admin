<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Helpers\Contracts\HelperContract; 
use Session; 
use Validator; 
use Carbon\Carbon; 

class MobileAppController extends Controller {

	protected $helpers; //Helpers implementation
    
    public function __construct(HelperContract $h)
    {
    	$this->helpers = $h;                     
    }

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getIndex()
    {
       	return json_encode(['status' => "ok",'version'=>"1.0"]);
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function getLogin(Request $request)
    {
    	$user = null;
        
        $req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [
                             'username' => 'required|min:6',
                             'password' => 'required|min:6',                        
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             $ret = ['status' => "error",'message'=>"Invalid username or password."];
             //dd($messages);
         }
         
         else
         {
             $ret = $this->helpers->appLogin($req);
         }

         return json_encode($ret);		 
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function getSignup(Request $request)
    {
    	$user = null;
        
        $req = $request->all();
		//dd($req);
        $validator = Validator::make($req, [
                             'email' => 'required|email',
                             'phone' => 'required|numeric',
                             'name' => 'required',
                             'password' => 'required|min:6',                        
         ]);
         
         if($validator->fails())
         {
             //$messages = $validator->messages();
             $ret = ['status' => "error",'message'=>"Validation error"];
             //dd($messages);
         }
         
         else
         {
             $ret = $this->helpers->appSignup($req);
         }

         return json_encode($ret);		 
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function getUpdateProfile(Request $request)
    {
    	$user = null;
        
        $req = $request->all();
		//dd($req);
        $validator = Validator::make($req, [
                             'email' => 'required|email',
                             'phone' => 'required|numeric',
                             'name' => 'required',
                             'password' => 'min:6',                        
         ]);
         
         if($validator->fails())
         {
             //$messages = $validator->messages();
             $ret = ['status' => "error",'message'=>"Validation error"];
             //dd($messages);
         }
         
         else
         {
             $ret = $this->helpers->appUpdateProfile($req);
         }

         return json_encode($ret);		 
    }
    
    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postAppSync(Request $request)
    {
    	$user = null;
        
        $req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [
                             'username' => 'required',
                             'type' => 'required',
                             'dt' => 'required|json',
                             'password' => 'required|min:6',                        
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             $ret = ['status' => "error",'message'=>"Validation error"];
             //dd($messages);
         }
         
         else
         {
             $ret = $this->helpers->appSync($req);
         }

         return json_encode($ret);		 
    }
	
	
    
    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getZoho()
    {
        $ret = "1535561942737";
    	return $ret;
    }
    
    
    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getPractice()
    {
		$url = "http://www.kloudtransact.com/cobra-deals";
	    $msg = "<h2 style='color: green;'>A new deal has been uploaded!</h2><p>Name: <b>My deal</b></p><br><p>Uploaded by: <b>A Store owner</b></p><br><p>Visit $url for more details.</><br><br><small>KloudTransact Admin</small>";
		$dt = [
		   'sn' => "Tee",
		   'em' => "kudayisitobi@gmail.com",
		   'sa' => "KloudTransact",
		   'subject' => "A new deal was just uploaded. (read this)",
		   'message' => $msg,
		];
    	return $this->helpers->bomb($dt);
    }   


}