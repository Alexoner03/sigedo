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
    

    private $user, $contract;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Contract $contract)
    {
        $this->user = $user;
        $this->contract = $contract;
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
            'contract' => $this->contract
        ]);
    }
}
