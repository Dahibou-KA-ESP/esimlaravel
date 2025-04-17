<?php

namespace App\Models;

use Storage;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'entreprise';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'address',
    'aft_api_key', 
    'aft_username',
    'currency_code', 
    'email', 
    'email_signature', 
    'name', 
    'text_provider', 
    'text_signature', 
    'twilio_auth_token', 
    'twilio_number', 
    'twilio_sid',
    'insura_name',
    'logo',
    'logoentreprise',
    'mail_driver',
    'mail_encryption',
    'mail_username',
    'mailgun_domain',
    'mailgun_secret',
    'mandrill_secret',
    'ses_key',
    'ses_region',
    'ses_secret',
    'smtp_host',
    'smtp_password',
    'smtp_port' ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Register Event Listeners
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($entreprise) {
            $entreprise->policies->each(function ($policy) {
                $policy->attachments()->delete();
            });
            $entreprise->users->each(function ($user) {
                if ($user->profile_image_filename !== 'default-profile.jpg') {
                    $file_storage_path = 'images/users/'.$user->profile_image_filename;
                    if (Storage::has($file_storage_path)) {
                        Storage::delete($file_storage_path);
                    }
                }
                $user->uploads->each(function ($upload) {
                    $file_storage_path = 'attachments/'.$upload->filename;
                    if (Storage::has($file_storage_path)) {
                        Storage::delete($file_storage_path);
                    }
                });
                $user->attachments()->delete();
                $user->incomingEmails->merge($user->outgoingEmails)->each(function ($email) {
                    $email->attachments()->delete();
                });
            });
        });
    }

    // Relationships
    public function admin() {
        return $this->hasOne(User::class)->admin();
    }

    public function brokers() {
        return $this->hasMany(User::class)->broker();
    }
	
	public function clients() {
        return $this->hasMany(Client::class);
    }
	
    public function payments() {
        return $this->hasManyThrough(Payment::class, Client::class, 'id', 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function historique() {
        return $this->hasMany(Historiques::class);
    }
	public function policies() {
        return $this->hasManyThrough(Policy::class, Product::class);
    }
	
    public function reminders() {
        return $this->hasMany(Reminder::class);
    }

    public function staff() {
        return $this->hasMany(User::class)->staff();
    }

    public function users() {
        return $this->hasMany(User::class);
    }
}
