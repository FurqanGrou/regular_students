<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscribeNotification extends Notification
{
    use Queueable;
    private $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
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
        $relation = 'student';
        if ($this->details->form_type == 'stopped-students'){
            $relation = 'student';
        }

        if ($this->details->form_type == 'new-students'){
            $relation = 'newStudent';
            $this->details->newStudent['name'] = $this->details->{$relation}->first_name . ' ' . $this->details->{$relation}->father_name . ' ' . $this->details->{$relation}->grandfather_name . ' ' . $this->details->{$relation}->family_name;
        }

        if ($this->details->payment_method == 'hsbc'){
            return (new MailMessage())
                ->from('no-reply@furqanreport.com')
                ->subject('عملية اشتراك جديدة - ' . $this->details->{$relation}->name)
                ->view('emails.new-bank-subscribe', ['details' => $this->details]);
        }

        if ($this->details->payment_method == 'checkout_gateway' && is_numeric($this->details->response_code) && in_array($this->details->payment_status, ['Captured', 'Authorized']) ){
            return (new MailMessage())
                ->from('no-reply@furqanreport.com')
                ->subject('عملية اشتراك جديدة - ' . $this->details->{$relation}->name)
                ->view('emails.new-card-subscribe', ['details' => $this->details]);
        }

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
