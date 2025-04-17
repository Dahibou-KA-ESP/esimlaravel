<?php

namespace App\Http\Controllers;

use Artisan;
use Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class SubscribtionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Setting Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the management of existing settings. Why
    | don't you explore it?
    |
    */

    /**
     * Create a new settings controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('localize_auth');
    }

    /**
     * Re-cache the app's config.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        session()->reflash();

        return view('inscription', [
            'alternate_url' => implode('/', array_slice(explode('/', $request->url()), 0, 3)),
        ]);
    }

    /**
     * Edit system settings.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $request->session()->flash('tab', 'system');
        $this->validate($request, [
            'app_locale_default'        => 'max:5|min:5|required',
            'favicon'                   => 'image',
            'insura_currency_default'   => 'in:'.collect(config('insura.currencies.list'))->map(function ($currency) {
                return $currency['code'];
            })->implode(',').'|required',
            'insura_name'               => 'max:64|min:3|required',
            'logo'                      => 'image',
            'logoentreprise'               => 'image',
            'mail_driver'               => 'in:mailgun,mandrill,sendmail,ses,smtp',
            'mail_encryption'           => 'in:none,ssl,tls',
            'mail_username'             => 'max:64|min:4|string',
            'mailgun_domain'            => 'nullable|string',
            'mailgun_secret'            => 'nullable|string',
            'mandrill_secret'           => 'nullable|string',
            'ses_key'                   => 'nullable|string',
            'ses_region'                => 'nullable|string',
            'ses_secret'                => 'nullable|string',
            'smtp_host'                 => 'max:64|min:3',
            'smtp_password'             => 'confirmed',
            'smtp_port'                 => 'integer',
        ]);
        var_dump($request);exit;
        $entreprise = Company::create([
            'address'           => $request['entreprise_address'] ?: null,
            'aft_api_key'       => $request['aft_api_key'] ?: null,
            'aft_username'      => $request['aft_username'] ?: null,
            'currency_code'     => $request['currency_code'],
            'email'             => $request['entreprise_email'] ?: null,
            'email_signature'   => $request['email_signature'] ?: null,
            'name'              => $request['entreprise_name'],
            'text_provider'     => $request['text_provider'] ?: null,
            'text_signature'    => $request['text_signature'] ?: null,
            'twilio_auth_token' => $request['twilio_auth_token'] ?: null,
            'twilio_number'     => $request['twilio_number'] ?: null,
            'twilio_sid'        => $request['twilio_sid'] ?: null,
        ]);
        $admin = $entreprise->admin()->create([
            'address'                   => $request['account_address'] ?: null,
            'birthday'                  => $request['birthday'] ?: null,
            'email'                     => $request['account_email'],
            'first_name'                => $request['first_name'],
            'last_name'                 => $request['last_name'] ?: null,
            'locale'                    => $request['locale'],
            'phone'                     => $request['phone'] ?: null,
            'profile_image_filename'    => $request['profile_image_filename'],
        ]);
        $admin->role = 'super';
        $admin->password = bcrypt($request['password']);
        $admin->save();

        
        $input = $request->only([
            'app_locale_default',
            'insura_currency_default',
            'insura_name',
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
            'smtp_port',
        ]);
  
        if ($request->hasFile('favicon') && $request->file('favicon')->isValid()) {
            $insura_favicon_filename = 'favicon.'.$request->file('favicon')->guessExtension();

            try {
                $request->file('favicon')->move(storage_path('app/public/image/favicon'), $insura_favicon_filename);
                $insura_favicon_storage_path = 'images/'.config('insura.favicon');
                if ($insura_favicon_filename !== config('insura.favicon') && Storage::has($insura_favicon_storage_path)) {
                    Storage::delete($insura_favicon_storage_path);
                }
                $input['insura_favicon'] = $insura_favicon_filename;
            } catch (FileException $e) {
                return redirect()->back()->withErrors([
                    trans('settings.message.error.file', [
                        'filename'  => $request->file('favicon')->getClientOriginalName(),
                        'type'      => trans('settings.message.error.files.favicon'),
                    ]),
                ]);
            }
        }
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $insura_logo_filename = 'logo.'.$request->file('logo')->guessExtension();
            try {
                $request->file('logo')->move(storage_path('app/public/image/logo'), $insura_logo_filename);
                $insura_logo_storage_path = 'images/'.config('insura.logo');
                if ($insura_logo_filename !== config('insura.logo') && Storage::has($insura_logo_storage_path)) {
                    Storage::delete($insura_logo_storage_path);
                }
                $input['insura_logo'] = $insura_logo_filename;
            } catch (FileException $e) {
                return redirect()->back()->withErrors([
                    trans('settings.message.error.file', [
                        'filename'  => $request->file('logo')->getClientOriginalName(),
                        'type'      => trans('settings.message.error.files.logo'),
                    ]),
                ]);
            }
        }
       
        
        
        // Artisan::call('config:clear');
        // $env = view('templates.env', ['env' => $input]);
        // Storage::disk('base')->put('.env', $env);
        
        // return redirect()->action('Auth\AuthController@getAuth')->with('success', trans('setup.message.success.setup'))->withInput([
        //     'email' => $request['account_email'],
        // ]);
    }

    /**
     * Get all settings.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Load the app with new settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function load()
    {
        return redirect()->action('SettingController@get')->with('success', trans('settings.message.success.system.edit'))->with('tab', 'system');
    }
}
