<?php
namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mail;

class UpdatePasswordController extends Controller
{
   /*
    * Ensure the user is signed in to access this page
    */
   public function __construct() {

       $this->middleware('auth')->except(['reset','new_password']);

   }

   /**
    * Update the password for the user.
    *
    * @param  Request  $request
    * @return Response
    */
   public function update(Request $request)
   {
       $this->validate($request, [
           'old' => 'required',
           'password' => 'required|min:6|confirmed',
       ]);

       $user = User::find(Auth::id());
       $hashedPassword = $user->password;

       if (Hash::check($request->old, $hashedPassword)) {
           //Change the password
           $user->fill([
               'password' => Hash::make($request->password)
           ])->save();

           $request->session()->flash('success', 'Your password has been changed.');

           return back();
       }

       $request->session()->flash('failure', 'Your password has not been changed.');

       return back();


   }

   public function reset(Request $request) {
   
       $validator = Validator::make($request->all(), [
        'email' => 'required|string|email'
       ]);

       $user = User::where (['email' => $request->email])->get();
      
       $user_count = $user->count();
       if ($validator->passes()) {

            // Auth::guard('web_seller')
            if ( $user_count <= 0 ) {
                return redirect()->back()->withInput($request->all())->with("status", " Email is not exist.");
            } else {
                $enc_key =  $this->encripted($user[0]->email);  
                
                $result = $this->send_validate_email($enc_key, $user[0]);
                if ( $result ) {
                    return redirect()->back()->with("message", "Success. Please check your registered email and click on ther reset password link.");
                } else {
                    return redirect()->back()->with("status", "Email not sent.");
                }
                
            }
        
        } else {
            return redirect()->back()->withInput($request->only("email"))->withErrors($validator);
        }
   }

    private function encripted($data) {
        $key1 = '644CBEF595BC9';
        $final_data = $key1.'|'.$data;
        $val = base64_encode(base64_encode(base64_encode($final_data)));
        return $val;
    }
    
    private function decripted($data) {
        $val = base64_decode(base64_decode(base64_decode($data)));
        $final_data = explode('|', $val);
        return $final_data[1];
    }

    private function send_validate_email($enc_key, $user) {
        $data = array(
            'key' => $enc_key,
            'user'=>$user         
        );


        $mail_status = Mail::send('email_template.resetpassword_email', $data,function($message) use($data){
            $message->to($data['user']->email, $data['user']->firstname." ".$data['user']->lastname)->subject('Reset password');
            $message->from(env("ADMIN_EMAIL"),'Mergetransit');
            $message->replyTo(env("ADMIN_EMAIL"),'Mergetransit');
        });
       
        
        if(count(Mail::failures()) > 0){
            return false;
        }else{
            return true;
            // return redirect()->to('/forgotpassword')->with('message', 'Success. Please check your registered email and click on ther reset password link');
        }
    }


    public function new_password(Request $request) {       

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->passes()) {

            $email = $this->decripted($request->action_key);

            $user = User::where (['email' => $email])->get();
            $user_id = $user[0]->id;

            $new_user = User::find($user_id);
            $new_user->password = bcrypt($request->password);
            $new_user->save();
            return redirect()->to('/thankyou')->with("message", "Your password updated successfully. Please login.");

     
        }else{
            return redirect()->back()->withInput($request->all())->withErrors($validate);
        }
    }
}