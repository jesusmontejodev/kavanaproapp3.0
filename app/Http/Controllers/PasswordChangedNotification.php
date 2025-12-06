<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChangedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $newPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Tu contraseña ha sido restablecida - ' . config('app.name'))
                    ->markdown('emails.password-changed')
                    ->with([
                        'user' => $this->user,
                        'newPassword' => $this->newPassword,
                        'appName' => config('app.name'),
                        'loginUrl' => route('login'),
                    ]);
    }
}
