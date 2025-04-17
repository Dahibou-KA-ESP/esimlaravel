<?php

namespace App\Jobs;

use Mail;
use App\Jobs\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Support\Facades\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
class SendWelcomeEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * The token used to log in the new user.
     *
     * @var string
     */
    protected $token;

    /**
     * The recipient of the email.
     *
     * @var App\Models\User
     */
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param  string  $token
     * @param  App\Models\User  $user
     * @return void
     */
    public function __construct($token, User $user)
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function build()
    {
        $user = $this->user;
        $data= asset('uploads/images/'. config('insura.favicon'));
        $imagePath = asset('/storage/image/logo/company/' . $user->company->logo);
        $imageData = file_get_contents($imagePath);
        $base64Image = base64_encode($imageData);
        $dataURL = 'data:image/png;base64,' . $base64Image;
        if($user->company->logo != '' && $user->company->logo != null){
            $imagePath = asset('/storage/image/logo/company/' . $user->company->logo);

            // Lire le contenu du fichier image
            $imageData = file_get_contents($imagePath);

            // Convertir le contenu en base64
            $base64Image = base64_encode($imageData);

            $dataURL = 'data:image/png;base64,' . $base64Image;
            // Afficher ou utiliser $base64Image comme bon vous semble
        }
        Mail::to($user->email)->send(emails.welcome);

        /*Mail::send(
            'emails.welcome',
            [
                'token'     => $this->token,
                'recipient' => $this->user,
            ],
            function ($m) use ($user) {
                $m->subject('Your New Account!');
                $m->to($user->email, $user->first_name.' '.$user->last_name);
            }
        );*/

        echo  $this->token;
    } 

    public function handle()
    {
        $user = $this->user;
        Mail::send(
            'emails.welcome',
            [
                'token'     => $this->token,
                'recipient' => $this->user,
            ],
            function ($m) use ($user) {
                $m->subject('Création de compte ASSURPRO');
                $m->to($user->email, $user->first_name.' '.$user->last_name);
            }
        );
    }
}
