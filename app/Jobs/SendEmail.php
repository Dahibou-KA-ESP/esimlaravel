<?php

namespace App\Jobs;

use Mail;
use App\Models\User;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Job implements ShouldQueue
{
    use Queueable, SerializesModels, InteractsWithQueue;

    /**
     * An email object being sent.
     *
     * @var \App\Models\Email
     */
    protected $email;

    /**
     * The blade email template used by the email.
     *
     * @var string
     */
    protected $view;

    /**
     * Create a new job instance.
     *
     * @param  App\Models\Email  $email
     * @param  string  $view
     * @return void
     */
    public function __construct(Email $email, $view)
    {
        // echo "daf ka";exit
        $this->email = $email;
        $this->view = $view;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = $this->email;

        Mail::send(
            $this->view,
            [
                'email' => $email,
            ],
            function ($m) use ($email) {
                $m->from($email->sender->email, $email->sender->first_name.' '.$email->sender->last_name);
                $m->subject($email->subject);
                $m->to($email->recipient->email, $email->recipient->first_name.' '.$email->recipient->last_name);
            }
        );

        $email->status = 1;
        $email->save();
    }
}
