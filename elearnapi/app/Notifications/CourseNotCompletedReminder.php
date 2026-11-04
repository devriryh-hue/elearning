<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseNotCompletedReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $courseId,
        public string $courseTitle,
        public int $progress,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Continuez votre cours : {$this->courseTitle}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Vous avez terminé {$this->progress}% du cours « {$this->courseTitle} ».")
            ->line('Reprenez là où vous vous êtes arrêté pour le terminer.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'         => 'course_not_completed',
            'course_id'    => $this->courseId,
            'course_title' => $this->courseTitle,
            'progress'     => $this->progress,
            'message'      => "Vous avez terminé {$this->progress}% de « {$this->courseTitle} ».",
        ];
    }
}