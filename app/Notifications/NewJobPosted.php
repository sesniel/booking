<?php

namespace App\Notifications;

use App\JobPost;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewJobPosted extends Notification implements ShouldQueue
{
    use Queueable;

    public $jobPost;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(JobPost $jobPost)
    {
        $this->jobPost = JobPost::whereId($jobPost->id)
        ->with('userProfile')->first(['id', 'user_id', 'job_category_id']);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        //return ['mail', 'database'];
        return ['database'];
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
        ->line('The introduction to the notification.')
        ->action('Notification Action', url('/'))
        ->line('Thank you for using our application!');
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $body = sprintf(
            '%s looking for %s',
            $this->jobPost->userProfile->title,
            $this->jobPost->category->name
        );

        return [
            'title' => 'Job Alert',
            'body' => $body,
            'avatar' => $this->jobPost->userProfile->profile_avatar,
            'jobPostId' => $this->jobPost->id,
        ];
    }
}
