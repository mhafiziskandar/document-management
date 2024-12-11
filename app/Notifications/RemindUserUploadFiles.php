<?php

namespace App\Notifications;

use App\Models\Folder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RemindUserUploadFiles extends Notification implements ShouldQueue
{
    use Queueable;

    public $folder;

    /**
     * Create a new notification instance.
     */
    public function __construct(Folder $folder)
    {
        $this->folder = $folder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $notifiable->is_email_valid ? ['mail'] : [];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Subject: Hub Data - Peringatan untuk muat naik fail')
            ->markdown('mail.users.reminder', ['notifiable' => $notifiable, 'project' => $this->folder]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
