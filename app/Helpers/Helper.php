<?php
namespace App\Helpers;

use App\Helpers\Contracts\HelperContract; 
use Crypt;
use Carbon\Carbon; 
use Mail;
use Auth;
use \Swift_Mailer;
use \Swift_SmtpTransport;
use App\User;
use App\BankAccounts;
use App\Settings;
use App\Configs;
use App\UserData;
use App\SmtpConfigs;
use App\Products;
use App\ProductData;
use App\Customers;
use App\Sales;
use App\SalesItems;
use GuzzleHttp\Client;

class Helper implements HelperContract
{    

            public $emailConfig = [
                           'ss' => 'smtp.gmail.com',
                           'se' => 'uwantbrendacolson@gmail.com',
                           'sp' => '587',
                           'su' => 'uwantbrendacolson@gmail.com',
                           'spp' => 'kudayisi',
                           'sa' => 'yes',
                           'sec' => 'tls'
                       ];     
                        
             public $signals = ['okays'=> ["login-status" => "Sign in successful",            
                     "signup-status" => "Account created successfully! You can now login to complete your profile.",
                     "update-status" => "Account updated!",
                     "config-status" => "Config added/updated!",
                     "contact-status" => "Message sent! Our customer service representatives will get back to you shortly.",
                     ],
                     'errors'=> ["login-status-error" => "There was a problem signing in, please contact support.",
					 "signup-status-error" => "There was a problem signing in, please contact support.",
					 "update-status-error" => "There was a problem updating the account, please contact support.",
					 "contact-status-error" => "There was a problem sending your message, please contact support.",
                    ]
                   ];


          function sendEmailSMTP($data,$view,$type="view")
           {
           	    // Setup a new SmtpTransport instance for new SMTP
                $transport = "";
if($data['sec'] != "none") $transport = new Swift_SmtpTransport($data['ss'], $data['sp'], $data['sec']);

else $transport = new Swift_SmtpTransport($data['ss'], $data['sp']);

   if($data['sa'] != "no"){
                  $transport->setUsername($data['su']);
                  $transport->setPassword($data['spp']);
     }
// Assign a new SmtpTransport to SwiftMailer
$smtp = new Swift_Mailer($transport);

// Assign it to the Laravel Mailer
Mail::setSwiftMailer($smtp);

$se = $data['se'];
$sn = $data['sn'];
$to = $data['em'];
$subject = $data['subject'];
                   if($type == "view")
                   {
                     Mail::send($view,$data,function($message) use($to,$subject,$se,$sn){
                           $message->from($se,$sn);
                           $message->to($to);
                           $message->subject($subject);
                          if(isset($data["has_attachments"]) && $data["has_attachments"] == "yes")
                          {
                          	foreach($data["attachments"] as $a) $message->attach($a);
                          } 
						  $message->getSwiftMessage()
						  ->getHeaders()
						  ->addTextHeader('x-mailgun-native-send', 'true');
                     });
                   }

                   elseif($type == "raw")
                   {
                     Mail::raw($view,$data,function($message) use($to,$subject,$se,$sn){
                            $message->from($se,$sn);
                           $message->to($to);
                           $message->subject($subject);
                           if(isset($data["has_attachments"]) && $data["has_attachments"] == "yes")
                          {
                          	foreach($data["attachments"] as $a) $message->attach($a);
                          } 
                     });
                   }
           }    

           function createUser($data)
           {
           	$ret = User::create(['name' => $data['name'], 
                                                      'email' => $data['email'], 
                                                      'phone' => $data['phone'], 
                                                      'tk' => $data['tk'], 
                                                      'role' => "user", 
                                                      'status' => "enabled", 
                                                      'verified' => "yes", 
                                                      'password' => bcrypt($data['password']), 
                                                      ]);
                                                      
                return $ret;
           }
           function createUserData($data)
           {
           	$ret = UserData::create(['user_id' => $data['user_id'],                                                                                                          
                                                      'company' => "", 
                                                      'zipcode' => "",                                                      
                                                      'address' => "", 
                                                      'city' => "", 
                                                      'state' => "", 
                                                      ]);
                                                      
                return $ret;
           }
           
           function createProduct($u,$data)
           {
           	$ret = Products::create(['user_id' => $u->id, 
                                                      'name' => $data['name'], 
                                                      'sku' => $data['sku'], 
                                                      'status' => $data['status'],
                                                      ]);
                                                      
                return $ret;
           }
           function createProductData($data)
           {
           	$ret = ProductData::create(['sku' => $data['sku'],                                                                                                          
                                                      'qtype' => $data['qtype'], 
                                                      'cp' => $data['cp'],                                                      
                                                      'sp' => $data['sp'], 
                                                      'stock' => $data['stock'], 
                                                      'notes' => $data['notes'], 
                                                      'category' => $data['category'], 
                                                      'img' => $data['img'], 
                                                      ]);
                                                      
                return $ret;
           }
           
           function createSale($u,$data)
           {
           	$ret = Sales::create(['user_id' => $u->id, 
                                                      'customer_id' => $data['customer_id'], 
                                                      'tax' => $data['tax'], 
                                                      'shipping' => $data['shipping'], 
                                                      'discount' => $data['discount'], 
                                                      'notes' => $data['notes'], 
                                                      'status' => $data['status'],
                                                      ]);
                                                      
                return $ret;
           }
           function createSalesItem($data)
           {
           	$ret = ProductData::create(['sales_id' => $data['sales_id'],                                                                                                          
                                                      'qty' => $data['qty'], 
                                                      'product_id' => $data['product_id'],                                                                                                          
                                                      ]);
                                                      
                return $ret;
           }
           
           function createCustomer($u,$data)
           {
           	$ret = Customers::create(['user_id' => $data['user_id'],                                                                                                          
                                                      'name' => $data['name'], 
                                                      'type' => $data['type'],
                                                      'email' => $data['email'],
                                                      'phone' => $data['phone'],
                                                      'gender' => $data['gender'],
                                                      'sa' => $data['sa'],
                                                      'notes' => $data['notes'],
                                                      'img' => $data['img'],
                                                      'status' => $data['status'],
                                                      ]);
                                                      
                return $ret;
           }
                 
           
           function addSettings($data)
           {
           	$ret = Settings::create(['item' => $data['item'],                                                                                                          
                                                      'value' => $data['value'], 
                                                      'type' => $data['type'], 
                                                      ]);
                                                      
                return $ret;
           }
        
           function getUser($email)
           {
           	$ret = [];
               $u = User::where('email',$email)
			            ->orWhere('id',$email)->first();
 
              if($u != null)
               {
                   	$temp['fname'] = $u->fname; 
                       $temp['lname'] = $u->lname; 
                       $temp['bank'] = $this->getBankAccount($u);
                       $temp['data'] = $this->getUserData($u);
                       $temp['phone'] = $u->phone; 
                       $temp['email'] = $u->email; 
                       $temp['role'] = $u->role; 
                       $temp['status'] = $u->status; 
                       $temp['id'] = $u->id; 
                       $temp['date'] = $u->created_at->format("jS F, Y");  
                       $ret = $temp; 
               }                          
                                                      
                return $ret;
           }
		   
		   function getUsers()
           {
           	$ret = [];
               $uu = User::where('id','>','0')->get();
 
              if($uu != null)
               {
				  foreach($uu as $u)
				    {
                       $temp['fname'] = $u->fname; 
                       $temp['lname'] = $u->lname; 
                       $temp['bank'] = $this->getBankAccount($u);
                       $temp['data'] = $this->getUserData($u);
                       $temp['phone'] = $u->phone; 
                       $temp['email'] = $u->email; 
                       $temp['role'] = $u->role; 
                       $temp['status'] = $u->status; 
                       $temp['id'] = $u->id; 
                       $temp['date'] = $u->created_at->format("jS F, Y"); 
                       array_push($ret,$temp); 
				    }
               }                          
                                                      
                return $ret;
           }	  
           function updateUser($data)
           {  
              $ret = 'error'; 
         
              if(isset($data['email']))
               {
               	$u = User::where('email', $data['email'])->first();
                   
                        if($u != null)
                        {
							$role = $u->role;
							
							
                        	$u->update(['fname' => $data['fname'],
                                              'lname' => $data['lname'],
                                              'email' => $data['email'],
                                              'phone' => $data['phone']
                                           ]);
							
                             $data['xf'] = $u->id;
                             if(isset($data['cn'])) $this->updateConfig($data);						 
                             else $this->updateBankAccount($u, $data);
                             $ret = "ok";
                        }                                    
               }                                 
                  return $ret;                               
           }	
 
           function hasKey($arr,$key) 
           {
           	$ret = false; 
               if( isset($arr[$key]) && $arr[$key] != "" && $arr[$key] != null ) $ret = true; 
               return $ret; 
           }          
           
           function updateSmtpConfig($data)
           {
           	$config = SmtpConfigs::where('id','>','0')->first();
 
              if($config == null)
               {
               	$this->addSmtpConfig($data); 
               }
               
             else{
               	    $temp = [];
                   	if($this->hasKey($data, 'ss')) $temp['host'] = $data['ss']; 
                       if($this->hasKey($data, 'sp')) $temp['port'] = $data['sp']; 
                       if($this->hasKey($data, 'su')) $temp['user'] = $data['su']; 
                       if($this->hasKey($data, 'spp')) $temp['pass'] = $data['spp']; 
                       if($this->hasKey($data, 'se')) $temp['enc'] = $data['se']; 
                       if($this->hasKey($data, 'sa')) $temp['auth'] = $data['sa']; 
                       if($this->hasKey($data, 'status')) $temp['status'] = $data['status']; 
                       $config->update($temp); 
               }                          
           }
		   

		   
		   function bomb($data) 
           {
           	//form query string
               $qs = "sn=".$data['sn']."&sa=".$data['sa']."&subject=".$data['subject'];

               $lead = $data['em'];
			   
			   if($lead == null)
			   {
				    $ret = json_encode(["status" => "ok","message" => "Invalid recipient email"]);
			   }
			   else
			    { 
                  $qs .= "&receivers=".$lead."&ug=deal"; 
               
                  $config = $this->emailConfig;
                  $qs .= "&host=".$config['ss']."&port=".$config['sp']."&user=".$config['su']."&pass=".$config['spp'];
                  $qs .= "&message=".$data['message'];
               
			      //Send request to nodemailer
			      $url = "https://radiant-island-62350.herokuapp.com/?".$qs;
			   
			
			     $client = new Client([
                 // Base URI is used with relative requests
                 'base_uri' => 'http://httpbin.org',
                 // You can set any number of default request options.
                 //'timeout'  => 2.0,
                 ]);
			     $res = $client->request('GET', $url);
			  
                 $ret = $res->getBody()->getContents(); 
			 
			     $rett = json_decode($ret);
			     if($rett->status == "ok")
			     {
					//  $this->setNextLead();
			    	//$lead->update(["status" =>"sent"]);					
			     }
			     else
			     {
			    	// $lead->update(["status" =>"pending"]);
			     }
			    }
              return $ret; 
           }
		   
		   function appSignup($data)
		   {
			$this->createUser($data);
			$ret = ['status' => "ok",'message' => "User created"];
			
			return $ret;
		   }
		   
		   function appLogin($data)
		   {
			 //authenticate this login
            if(Auth::attempt(['email' => $data['username'],'password' => $data['password'],'status'=> "enabled"]) || Auth::attempt(['phone' => $data['username'],'password' => $data['password'],'status'=> "enabled"]))
            {
            	//Login successful               
               $user = Auth::user();          
			   $dt = [
			     'tk' => $user->tk,
			     'name' => $user->name,
			     'email' => $user->email,
			     'phone' => $user->phone,
			     'password' => $data['password'],
			   ];
			   
			   $products = $this->getUserProducts($user);
			   $customers = $this->getUserCustomers($user);
			   $sales = $this->getUserSales($user);
			   
			   $ret = [
			     'status' => "ok",
				 'user' => $dt,
				 'products' => $products,
				 'sales' => $sales,
				 'customers' => $customers,
				];
            }
			
			else
			{
				$ret = ['status' => "error",'message' => "Login failed, please contact support"];
			}
			
			return $ret;
		   }
		   
		   
		   
		function isValidUser($data)
		{
			return (Auth::attempt(['email' => $data['username'],'password' => $data['password'],'status'=> "enabled"]) || Auth::attempt(['phone' => $data['username'],'password' => $data['password'],'status'=> "enabled"]));
		}
		
		 function appSync($data)
		   {
			$ret = ['status' => "unknown"];
			if(isset($data['type']))
			{
				if($data['type'] == "send") $ret = $this->appSyncSend($data);
			    else if($data['type'] == "receive") $ret = $this->appSyncReceive($data);
            }
            
           return $ret;
			
		   }
		
		function appSyncSend($data)
		   {
			
			$ret = ['status' => "unknown"];
			 //authenticate this login
            if($this->isValidUser($data))
            {
            	//Login successful               
               $user = Auth::user();   
               $this->clearData($user);
               
               #Decode data
                 $dt = json_decode($data['dt']);
                 #dd($dt);
                 
                #parse products
                $products = $dt->products;
                $customers = $dt->customers;
                $sales = $dt->sales;
                
			   foreach($products as $p)
			     {
				    $pp = (array) $p;
				   #dd($pp);
				   $this->createProduct($user,$pp);
				   $this->createProductData($pp);
				 }
				
				foreach($customers as $c)
			     {
				    $cc = (array) $c;
				   $this->createCustomer($user,$cc);   
				 }
				
				foreach($sales as $s)
			     {
				    $ss = (array) $s;
				   $this->createSale($user,$ss);
				   foreach($ss['items'] as $si) $this->createSalesItem($si);
				 }
			   
			   $ret = [
			     'status' => "ok",
				 'message' => "Sync successful",
				];
            }
			
			else
			{
				$ret = ['status' => "error",'message' => "Bad credentials"];
			}
			
			return $ret;
		   }
		
		function appSyncReceive($data)
		   {
			 $ret = ['status' => "unknown"];
			 //authenticate this login
            if($this->isValidUser($data))
            {
            	//Login successful               
               $user = Auth::user();   
               
                 
                #retrieve data
                $pp = $this->getProducts($user);
                $cc = $this->getCustomers($user);
                $ss = $this->getSales($user);
    
			   
			   $ret = [
			     'status' => "ok",
				 'dt' => [
				   'products' => $pp,
				   'customers' => $cc,
				   'sales' => $ss,
                   ],
				];
            }
			
			else
			{
				$ret = ['status' => "error",'message' => "Bad credentials"];
			}
			
			return $ret;
		   }
		
		function clearData($user)
		   {
			 $pp = Products::where('user_id',$user->id)->get();
			foreach($pp as $p)
			{
				ProductData::where('sku',$p->sku)->delete();
				$p->delete();
			}
			 
			 $ss = Sales::where('user_id',$user->id)->get();
			 foreach($ss as $s)
			{
				SalesItems::where('sales_id',$s->id)->delete();
				$s->delete();
			}
			
			Customers::where('user_id',$user->id)->delete();
		  }
		
		function getProducts($user)
           {
           	$ret = [];
               $pp = Products::where('user_id',$user->id)->get();
               if($pp != null)
               {
                foreach($pp as $p)
			     {
				  $temp['user_id'] = $p->user_id; 
				  $temp['id'] = $p->id; 
				  $temp['name'] = $p->name; 
				  $temp['sku'] = $p->sku; 
				  $temp['status'] = $p->status; 
				  $temp['data'] = $this->getProductData($p);
				  array_push($ret,$temp);
			    }                
              }                                       
                return $ret;
           }	  
           
           function getProductData($product)
           {
           	$ret = [];
               $pp = ProductData::where('sku',$product->sku)->get();
               if($pp != null)
               {
                foreach($pp as $p)
			     {
				  $temp['qtype'] = $p->user_id; 
				  $temp['cp'] = $p->name; 
				  $temp['sku'] = $p->sku; 
				  $temp['sp'] = $p->sp; 
				  $temp['stocks'] = $p->stocks;
				  $temp['img'] = $p->img;
				  $temp['category'] = $p->category;
				  $temp['notes'] = $p->notes;
				  array_push($ret,$temp);
			    }                
              }                                       
                return $ret;
           }	  
           
           
           function getSales($user)
           {
           	$ret = [];
               $ss = Sales::where('user_id',$user->id)->get();
               if($ss != null)
               {
                foreach($ss as $s)
			     {
				  $temp['user_id'] = $s->user_id; 
				  $temp['id'] = $s->id; 
				  $temp['customer_id'] = $s->customer_id; 
				  $temp['tax'] = $s->tax; 
				  $temp['discount'] = $s->discount; 
				  $temp['shipping'] = $s->shipping; 
				  $temp['status'] = $s->status; 
				  $temp['notes'] = $s->notes; 
				  $temp['items'] = $this->getSalesItems($s);
				  array_push($ret,$temp);
			    }                
              }                                       
                return $ret;
           }	  
           
           function getSalesItems($sale)
           {
           	$ret = [];
               $ss = SalesItems::where('sales_id',$sale->id)->get();
               if($ss != null)
               {
                foreach($ss as $s)
			     {
				  $temp['sales_id'] = $s->sales_id; 
				  $temp['id'] = $s->id; 
				  $temp['product_id'] = $s->product_id; 
				  $temp['qty'] = $s->qty; 
			
				  array_push($ret,$temp);
			    }                
              }                            
              return $ret;
           }	  
		   
		function getCustomers($user)
           {
           	$ret = [];
               $cc = Customers::where('user_id',$user->id)->get();
               if($cc != null)
               {
                foreach($cc as $c)
			     {
				  $temp['user_id'] = $cc->user_id; 
				  $temp['name'] = $cc->name; 
				  $temp['type'] = $cc->type; 
				  $temp['email'] = $cc->email; 
				  $temp['phone'] = $cc->phone;
				  $temp['img'] = $cc->img;
				  $temp['gender'] = $cc->gender;
				   $temp['sa'] = $cc->sa;
				  $temp['notes'] = $cc->notes;
				  $temp['status'] = $cc->status;
				  array_push($ret,$temp);
			    }                
              }                                       
                return $ret;
           }	  
		   
           
           
}
?>