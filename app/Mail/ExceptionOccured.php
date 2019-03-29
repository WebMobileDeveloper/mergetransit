<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExceptionOccured extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }    

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      
        return $this->view('email_template.error_exception')               
            ->subject('Error Mergetransit')
            ->with([ 'data' => $this->data ]);
    }
}
