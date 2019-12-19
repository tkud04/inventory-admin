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
		#dd($req);
        $validator = Validator::make($req, [
                             'email' => 'required|email',
                             'phone' => 'required|numeric',
                             'name' => 'required',
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
             $ret = $this->helpers->appSignup($req);
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
	public function getAccounts(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
		}
		else
		{
			return redirect()->intended('login');
		}
		
		$xf = isset($req['xf']) ? $req['xf'] : $user->email;
		$accounts = $this->helpers->getUsers();
		$account = $this->helpers->getUser($xf);
		$cg = [];
		 if(isset($req['cg'])) $cg = $req['cg'];
		
		if(count($account) < 1) $account = $this->helpers->getUser($user->id);
		$configs = $this->helpers->getConfigs($account['id']);
		 if(isset($req['cg'])) $cg = $this->helpers->getConfig($account['id'],$req['cg']);
		$signals = $this->helpers->signals;
		#dd($account);
    	return view('accounts',compact(['user','accounts','account','cg','configs','signals']));
    }

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getAddConfig(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
		}
		else
		{
			return redirect()->intended('login');
		}
		
		$xf = isset($req['xf']) ? $req['xf'] : $user->email;
		$accounts = $this->helpers->getUsers();
		$account = $this->helpers->getUser($xf);
		$cn = $this->helpers->getConfigNumber();
		if(count($account) < 1) $account = $this->helpers->getUser($user->id);
		$configs = $this->helpers->getConfigs($account['id']);
		$cg = [];
		 if(isset($req['cg'])) $cg = $this->helpers->getConfig($account['id'],$req['cg']);
		$signals = $this->helpers->signals;
		#dd($cg);
    	return view('config',compact(['user','accounts','account','cg','configs','signals','cn']));
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postAddConfig(Request $request)
    {
    	if(Auth::check())
		{
			$user = Auth::user();
		}
		else
        {
        	return redirect()->intended('login');
        }
        
        $req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [
                             'xf' => 'required|numeric',
                             'cn' => 'required|numeric',
                             'acname' => 'required',
                             'acnum' => 'required|numeric',
                             'balance' => 'required|numeric',
                             'status' => 'required|not_in:none',
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             return redirect()->back()->withInput()->with('errors',$messages);
             //dd($messages);
         }
         
         else
         {
             $ret = $this->helpers->addConfig($req);
	        session()->flash("config-status","ok");
			return redirect()->intended('config');
         } 	  
    }

    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postViewAccount(Request $request)
    {
    	if(Auth::check())
		{
			$user = Auth::user();
		}
		else
        {
        	return redirect()->intended('login');
        }
        
        $req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [
                             'acc' => 'required|numeric|not_in:none',
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             return redirect()->intended('accounts?xf='.$user->id);
             //dd($messages);
         }
         
         else
         {
			 $uu = 'accounts?xf='.$req['acc'];
			 if(isset($req['xf'])) $uu = 'accounts?xf='.$req['xf'].'&cg='.$req['acc'];
         	 return redirect()->intended($uu);
         }  
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postViewConfig(Request $request)
    {
    	if(Auth::check())
		{
			$user = Auth::user();
		}
		else
        {
        	return redirect()->intended('login');
        }
        
        $req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [
                             'acc' => 'required|numeric|not_in:none',
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             $uu = 'config';
			 if(isset($req['xf'])) $uu = 'config?xf='.$req['xf'];
         	 return redirect()->intended($uu);
             //dd($messages);
         }
         
         else
         {
         	 $uu = 'config?cg='.$req['acc'];
			 if(isset($req['xf'])) $uu = 'config?xf='.$req['xf'].'&cg='.$req['acc'];
         	 return redirect()->intended($uu);
         }  
    }

  /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postAccounts(Request $request)
    {
    	if(Auth::check())
		{
			$user = Auth::user();
		}
		else
        {
        	return redirect()->intended('login');
        }
        
        $req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [
                             'fname' => 'required',
                             'lname' => 'required',
                             'email' => 'required|email',
                             'phone' => 'required|numeric',
                             'acnum' => 'required|numeric',
                             'balance' => 'required|numeric',
                             'status' => 'required|not_in:none',
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             return redirect()->back()->withInput()->with('errors',$messages);
             //dd($messages);
         }
         
         else
         {
             $ret = $this->helpers->updateUser($req);
	        session()->flash("update-status","ok");
			return redirect()->intended('accounts');
         } 	  
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