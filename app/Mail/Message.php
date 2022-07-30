<?php

namespace App\Mail;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class Message extends Mailable
{
    use Queueable, SerializesModels;
    

    private $user, $contract, $type;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Contract $contract, String $type)
    {
        $this->user = $user;
        $this->contract = $contract;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   

        return $this->view('mail.message',[
            'user' => $this->user,
            'contract' => $this->contract,
            'type' => $this->type,//new,observe,review,'finish'
        ]);
    }
}
