<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordReset extends Notification
{
	/**
	 * The password reset token.
	 *
	 * @var string
	 */
	public $token;
	public $nome;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token,$nome)
    {
	    $this->token = $token;
	    $this->nome = $nome;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
	                ->subject('xxxx - Nova senha')
                    ->line('Você está recebendo este email pois recebemos um pedido de troca de senha')
                    ->action('Reset Password', url(config('app.url').route('password.reset', $this->token, false)))
                    ->line('Se você não requisitou o reset, nenhuma ação é requisitada.')
	                ->from('atendimentosite@xxxx.com', 'xxxx')
	                ->view('vendor.notifications.email',['nome' => $this->nome]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
