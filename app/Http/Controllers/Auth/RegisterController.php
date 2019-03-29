<?php

namespace App\Http\Controllers\Auth;

use App\Customer;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Stripe\Customer as Stripe_Customer;
use Stripe\Stripe;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('guest');

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator($data)
    {
        return Validator::make($data, [
            'company_name' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'zip_code' => 'required|string|max:5',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'mc_num' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function create_customer(array $data)
    {

        // return User::create([
        //     'company_name' => $data['company_name'],
        //     'zip_code' => $data['zip_code'],
        //     'city' => $data['city'],
        //     'street' => $data['street'],
        //     'state' => $data['state'],
        //     'mc_num' => $data['mc_num'],
        //     'firstname' => $data['firstname'],
        //     'lastname' => $data['lastname'],
        //     'phone' => $data['phone'],
        //     'email' => $data['email'],
        //     'password' => bcrypt($data['password'])

        // ]);

        $users = User::where('users.email', $data['email'])->get();
        if (count($users) > 0) {
            $result['status'] = false;
            $result['message'] = 'Email is already registered. pleaes check email again.';
            return $result;
            // return redirect()->back()->withInput($data)->withErrors($validate);
        } else {
            // check email with neverbounce API

            $nb_email_valid = false;
            $nb_apikey = env("NEVERBOUNCE_API_KEY");

            $curl = curl_init();
            $nb_api_check_url = 'https://api.neverbounce.com/v4/single/check?key='.$nb_apikey.'&email='.$data['email'];

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

            if ($nb_email_valid ) {
                DB::beginTransaction();
                try {
                    $user = new User();
                    $user->firstname = $data['firstname'];
                    $user->lastname = $data['lastname'];
                    $user->email = $data['email'];
                    $user->phone = $data['phone'];
                    $user->password = bcrypt($data['password']);
                    $user->role = 4;
                    $user->is_active = 1;
                    $user->save();

                    $customer = new Customer();
                    $customer->user_id = $user->id;
                    $customer->company = $data['company_name'];
                    $customer->firstname = $data['firstname'];
                    $customer->lastname = $data['lastname'];
                    $customer->street = $data['street'];
                    $customer->city = $data['city'];
                    $customer->state = $data['state'];
                    $customer->zipcode = $data['zip_code'];
                    $customer->email = $data['email'];
                    $customer->phone = $data['phone'];
                    $customer->image_path = '';
                    $customer->description = $data['company_name'];
                    $customer->manual_invoice = 0;

                    if (isset($data['service'])) {
                        $customer->member_type = $data['service'];
                        $customer->card_token = $data['stripe_token'];
                    } else {
                        $customer->member_type = 1;
                        $customer->card_token = "Free";
                    }

                    $customer->upgrade_date = date('Y-m-d');
                    $customer->save();


                
                    // dd($user->id);
                    if (isset($data['service']) && $data['service'] != 1) {

                        $stripe_key = env("STRIPE_SECRET_KEY");
                        Stripe::setApiKey($stripe_key);

                        
                        if ($data['service'] == 3) {
                            // $optimization_plan = 'plan_E5m9MVc5XFmXlU';
                            // $name = 'Merget Transit - Optimization';
                            // $plan_id = $optimization_plan;

                            // $user->newSubscription($name, $plan_id)->create($data['stripe_token'], [
                            //     'email' => $data['email'],
                            //     'description' => 'Merget Transit - Optimization',
                            // ]);

                            $user->createAsStripeCustomer($data['stripe_token'], [
                                'email' => $data['email'],
                                "description" => "Mergetransit"
                            ]);
                            $user->save();
                                            
                            // $user->charge(12500, [
                            //     'description' => 'Merget Transit - Optimization',
                            // ]);

                        } else if ($data['service'] == 2) {                       

                            $user->createAsStripeCustomer($data['stripe_token'], [
                                'email' => $data['email'],
                                "description" => "Mergetransit"
                            ]);
                            $user->save();
                                            
                            $user->charge(9900, [
                                'description' => 'Merget Transit - Organization',
                            ]);
                        }
                    }
                

                    DB::commit();
                    $success = true;
                    $result['status'] = true;
                    $result['message'] = '';
                    return $result;
                } catch (\Exception $e) {
                    // dd($e->getMessage());
                    $success = false;
                    DB::rollback();
                
                    $result['status'] = false;
                    $result['message'] = $e->getMessage();
                    return $result;
                
                }
            } else {
                $result['status'] = false;
                $result['message'] = 'You entered Invalid Email. Please enter Valid Email';
                return $result;
            }

            
        }
    }

    public function showRegistrationForm()
    {

        return view("register");
    }

    public function register(Request $request)
    {

        $validate = $this->validator($request->all());

        if ($validate->passes()) {

            $result = $this->create_customer($request->all());

            if ($result['status']) {
                return redirect(url("/sadmin"));
            } else {
                return redirect()->back()->withInput($request->all())->with("status", $result['message']);
            }

        } else {
            return redirect()->back()->withInput($request->all())->withErrors($validate);
        }

    }

    // Auth::user()->firstname
}
