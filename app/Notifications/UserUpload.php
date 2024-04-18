<?php

namespace App\Notifications;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserUpload extends Notification implements ShouldQueue
{
    use Queueable;

    public $file, $folder;

    /**
     * Create a new notification instance.
     */
    public function __construct(File $file)
    {
        $this->file = $file;
        $this->folder = $file->folder;
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
            ->subject('Subject: Hub Data - Fail baru telah dimuat naik & memerlukan pengesahan')
            ->markdown('mail.users.upload', ['file' => $this->file, 'folder' => $this->folder, 'notifiable' => $notifiable]);
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
