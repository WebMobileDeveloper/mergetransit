<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactEmail;

class MailController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function send(Request $request)
    {

      
        $recaptcha = $request['g-recaptcha-response'];
      
        $captchaSecretkey = env("CAPTCHA_SECRET_KEY");
        // verify
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$captchaSecretkey.'&response='.$recaptcha);
        $responseData = json_decode($verifyResponse);
        
        if(!$responseData->success) {
            return redirect()->back()->withInput($request->all())->with("status", " Google recaptcah verify is failed.");
        }


        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'name' => 'required|string',
            'phone' => 'required|min:10|numeric',
            'message' => 'required|string',
        ]);

        if ($validator->passes()) {
            $data = array(
				'subject'=>'ContactUs',
                'email' => $request->email,
                'name' => $request->name,
                'phone' => $request->phone,
                'company' => $request->company,
                //'message'=>stripslashes($request->message)
                'message' => explode("\n", $request->message),
            );

            $nb_email_valid = false;
            $nb_apikey = env("NEVERBOUNCE_API_KEY");

            $curl = curl_init();
            $nb_api_check_url = 'https://api.neverbounce.com/v4/single/check?key='.$nb_apikey.'&email='.$request->email;

            curl_setopt_array($curl, array(
                CURLOPT_URL =>$nb_api_check_url ,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            $res = json_decode($response);

            $valid_result = ['valid', 'catchall', 'unknown'];
            $invalid_result = ['invalid', 'disaposable'];

            if ($res->status == 'success') {
                if (in_array($res->result, $valid_result)) {
                    $nb_email_valid = true;
                }
            }
           
            if ($nb_email_valid) {
                Mail::to(env("ADMIN_EMAIL"))->send(new ContactEmail($data));

                //    Mail::send('mail',$data,function($message) use ($data){
                //         $message->to(env("ADMIN_EMAIL"),'Mergetransit')->subject('Contactus');
                //         $message->from($data['email'], $data['name']);
                //    });
    
                //info@mergetransit.com
                return back()->with('status', "Thank you for your message.");
            } else {
                return redirect()->back()->withInput($request->all())-> with('status', "You entered Invalid Email. Please enter Valid Email.");
            }
          
            
        } else {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
    }
}
// info@mergetransit.com
