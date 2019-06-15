<?php

namespace App\Notifications;

use App\JobPost;
use App\JobQuote;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class JobQuoteUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $jobQuote;
    public $jobPost;
    public $jobQuoteOwnerProfile;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(JobQuote $jobQuote)
    {
        $this->jobQuote = $jobQuote;
        $this->jobPost = JobPost::whereId($jobQuote->job_post_id)
        ->with('category', 'userProfile')->first(['id', 'user_id', 'job_category_id']);
        $this->jobQuoteOwnerProfile = $this->jobQuote->user->vendorProfile;
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
        $title = sprintf('UPDATED QUOTE FROM %s', $this->jobQuoteOwnerProfile->business_name);
        $body = sprintf(
            'You have received an updated quote for "%s looking for %s"',
            $this->jobPost->userProfile->title,
            $this->jobPost->category->name
        );

        return [
            'title' => $title,
            'body' => $body,
            'quoteOwnerAvatar' => $this->jobQuoteOwnerProfile->profile_avatar,
            'jobQuoteId' => $this->jobQuote->id,
            'jobPostId' => $this->jobPost->id
        ];
    }
}
