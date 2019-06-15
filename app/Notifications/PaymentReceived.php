<?php

namespace App\Notifications;

use App\Invoice;
use App\JobPost;
use App\Payment;
use App\JobQuote;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public $jobQuote;
    public $jobPost;
    public $invoice;
    public $payment;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(JobQuote $jobQuote, Invoice $invoice, Payment $payment)
    {
        $this->payment = $payment;
        $this->invoice = $invoice;
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
        $body = sprintf(
            '%s Sent you a payment of $%s',
            $this->jobPost->userProfile->title,
            number_format(($this->payment->api_response['amount'] /100), 2, '.', ',')
        );

        return [
            'title' => 'Payment Received',
            'body' => $body,
            'avatar' => $this->jobPost->userProfile->profile_avatar,
            'jobQuoteId' => $this->jobQuote->id,
            'jobPostId' => $this->jobPost->id,
            'invoiceId' => $this->invoice->id,
        ];
    }
}
