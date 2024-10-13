<?php

namespace App\Notifications;

use App\Models\Actualite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActualiteNotification extends Notification implements  ShouldQueue
{
    use Queueable;

    private $actualite;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Actualite $actualite)
    {
        $this->actualite=$actualite;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toBroadcast($notifiable)
    {
        return $this->toArray();
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
            id=>$this->actualite->id
        ];
    }
}
