<?php

namespace App\Mail;

use App\Models\CipherKey;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCipherKeyMail extends Mailable
{
    use Queueable, SerializesModels;

    private $key;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(CipherKey $key)
    {
        $this->key = $key;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.new_cipher_key', [
            'key' => $this->key
        ])->subject(trans('emails.new_cipher_key'));
    }
}
