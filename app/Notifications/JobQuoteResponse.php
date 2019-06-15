<?php

namespace App\Notifications;

use App\JobPost;
use App\JobQuote;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class JobQuoteResponse extends Notification implements ShouldQueue
{
    use Queueable;

    public $jobQuote;
    public $jobPost;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(JobQuote $jobQuote)
    {
        $this->jobQuote = $jobQuote;
        $this->jobPost = JobPost::whereId($jobQuote->job_post_id)
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
        $title = $body = '';

        if ($this->jobQuote->status === 4) {
            $title = sprintf('%s HAS ACCEPTED YOUR QUOTE', $this->jobPost->userProfile->title);
            $body = 'Submit the invoice to confirm the booking & get paid';
        } elseif ($this->jobQuote->status === 3) {
            $title = sprintf('%s HAS REQUESTED CHANGES TO THE QUOTE', $this->jobPost->userProfile->title);
            $body = 'Review their change requests';
        } elseif ($this->jobQuote->status === 8) {
            $title = sprintf('YOUR QUOTE HAS BEEN DECLINED BY %s', $this->jobPost->userProfile->title);
            $body = sprintf(
                'Your quote for "%s looking for %s" was declined',
                 $this->jobPost->userProfile->title,
                $this->jobPost->category->name
            );
        }

        return [
            'title' => $title,
            'body' => $body,
            'avatar' => $this->jobPost->userProfile->profile_avatar,
            'jobQuoteId' => $this->jobQuote->id,
            'jobPostId' => $this->jobPost->id,
            'jobPostUserId' => $this->jobPost->user_id,
            'status' => $this->jobQuote->status,
        ];
    }
}
