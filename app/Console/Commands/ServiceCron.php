<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Driver;
use App\Customer;
use App\User;
use App\Detail;
use Illuminate\Http\Request;
use Stripe\Stripe;

class ServiceCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servicecron:start {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
		
		$stripe_key = env("STRIPE_SECRET_KEY");
		Stripe::setApiKey($stripe_key);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    private function get_type () {
        return $this->argument("type");
    }
    public function handle()
    {            
                               
        $type = $this->get_type();
        if ($type == 'optimization') {
            // Optimization schedule
            
            $start_date=date("Y-m-d", strtotime('-6 days'));
            $end_date = date("Y-m-d");
        
            $res = Detail::join('drivers', 'drivers.id', 'details.driver_id')
                            ->join('customers','customers.id','drivers.company_id')
                            ->join('users','users.email','customers.email')
                            ->select('users.id', 'customers.card_token', 'customers.email')
                            ->where('customers.member_type', 3)->whereBetween('details.put_date', [$start_date, $end_date])->get();
                
            
            $res_json = json_encode($res); 
            $array = json_decode($res_json);

            $users = array_reduce($array, function($carry, $item) { 
                if(!isset($carry[$item->id])) {
                    $carry[$item->id] = $item;
                }
                return $carry;
            });
                
            if ($users ){
                $users = array_values($users);	
                
                foreach ($users as $user) {
                    if ($user->card_token) {
                        $stripe_user = User::find($user->id);
                        $stripe_user->charge(12500, [                        
                            'description' => 'Merget Transit - Optimization'
                        ]);
                    }
                }
            }
        } else {
            $res = Detail::join('drivers', 'drivers.id', 'details.driver_id')
                    ->join('customers','customers.id','drivers.company_id')
                    ->join('users','users.email','customers.email')
                    ->select('users.id', 'customers.card_token', 'customers.email')
                    ->where('customers.member_type', 2)
                    ->where('customers.upgrade_date', date("Y-m-d", strtotime('-30 days')))->get();

            $res_json = json_encode($res); 
            $array = json_decode($res_json);

            $users = array_reduce($array, function($carry, $item) { 
                if(!isset($carry[$item->id])) {
                    $carry[$item->id] = $item;
                }
                return $carry;
            });
          
            if ($users ){
                $users = array_values($users);	
                
                foreach ($users as $user) {
                  
                    if ($user->card_token) {
                        $stripe_user = User::find($user->id);
                        $stripe_user->charge(9900, [
                            'description' => 'Merget Transit - Organization'
                        ]);

                        $customer = Customer::where('user_id', $user->id)->get();
                        $customer[0]->upgrade_date = date("Y-m-d");
                        $customer[0]->save();
                    }
                   
                }
            }
        }
		
    }
}
