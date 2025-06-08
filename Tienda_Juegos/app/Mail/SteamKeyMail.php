<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SteamKeyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

public function build()
{
    $mail = $this
        ->subject('Tu clave de Steam')
        ->markdown('emails.steam.key')    // tu plantilla Markdown (puede dejarse sencilla)
        ->with(['key' => $this->key]);

    // Instrucciones a Mailjet para que use TU template ID
    $mail->withSwiftMessage(function ($message) {
        $headers = $message->getHeaders();
        $headers->addTextHeader('X-MJ-TemplateID', '7054146');
        $headers->addTextHeader('X-MJ-TemplateLanguage', 'true');
    });

    return $mail;
}
}
