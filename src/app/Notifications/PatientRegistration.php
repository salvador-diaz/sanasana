<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class PatientRegistration extends Notification implements ShouldQueue
{
    use Queueable;

    public $patient;

    /**
     * Create a new notification instance.
     */
    public function __construct($patient)
    {
       $this->patient = $patient; 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        //TODO: add SMS notifications
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail =  (new MailMessage)
                    ->subject('Your information was registered successfully')
                    ->greeting("Hello {$this->patient->name}!")
                    ->line('Your information was registered successfully:')
                    ->line('')
                    ->line("- Name: {$this->patient->name}")
                    ->line("- Email: {$this->patient->email}")
                    ->line("- Address: {$this->patient->address}")
                    ->line("- Phone: {$this->patient->phone}");

        $mime = Storage::mimeType($this->patient->document_photo_url);
        if ($mime)
            $mail->attach(Storage::path($this->patient->document_photo_url), [
                'as' => "{$this->patient->name}.".explode("/", $mime)[1],
                'mime' => "image/{$mime}",
            ]);
            
        return $mail;
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

    // TODO: add SMS notifications
    // public function toSms(object $notifiable) {}

    private function patientDataPlainList() {
        return <<<TXT
            - Name: {$this->patient->name}
            - Email: {$this->patient->email}
            - Address: {$this->patient->address}
            - Phone: {$this->patient->phone}
        TXT;
    }
}
