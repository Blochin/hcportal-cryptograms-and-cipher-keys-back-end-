<?php

namespace App\Mail;

use App\Models\CipherKey;
use App\Models\Cryptogram;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateCryptogramStateMail extends Mailable
{
    use Queueable, SerializesModels;

    private $cryptogram;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Cryptogram $cryptogram)
    {
        $this->cryptogram = $cryptogram;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.update_cryptogram_state', [
            'cryptogram' => $this->cryptogram
        ])->subject(trans('emails.update_cryptogram_state'));
    }
}
