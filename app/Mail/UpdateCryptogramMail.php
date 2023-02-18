<?php

namespace App\Mail;

use App\Models\CipherKey;
use App\Models\Cryptogram;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateCryptogramMail extends Mailable
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
        return $this->view('emails.edit_cryptogram', [
            'cryptogram' => $this->cryptogram
        ])->subject(trans('emails.edit_cryptogram'));
    }
}
