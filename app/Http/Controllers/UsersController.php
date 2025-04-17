<?php

namespace App\Http\Controllers;
use Storage;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendWelcomeEmail;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UsersController extends Controller
{   

    public function index(){
        $users= User::all()->where('deleted_at',NULL)->where('role','!=','partenaire');
        $user = auth()->user();
        return view('users.index',compact('users','user'));
    }

    public function userDelete(){
        $users = User::whereNotNull('deleted_at')->get();
        $user = auth()->user();
        return view('users.ex_user',compact('users','user'));
    }


    public function createUser(Request $request) {
        try {           

            // Valider les données du formulaire, y compris la correspondance des mots de passe
            
            $request->validate([
                'civilite' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required',
                'address' => 'required',
                'birthday' => 'required',
                'role' => 'required',
            ]);
            

            $profil_filename = 'default-profile.jpg';   
            if ($request->hasFile('profile_image_filename')) {
                $logo = $request->file('profile_image_filename');
                $logo_extension = $logo->getClientOriginalExtension(); // Obtenir l'extension du fichier
            
                // Générer un nom de fichier unique
                $profil_filename = Str::random(7) . '-profile_image.' . $logo_extension;
                
                try {
                    // Déplacer le fichier vers le dossier de stockage approprié
                    $logo->move(storage_path('app/public/image/profile_image/'), $profil_filename);
                } catch (FileException $e) {
                    // Gérer les erreurs de déplacement de fichier
                    return redirect()->back()->with('error','photo non telecharger');
                }
                
            }
        
            $id_entreprise = auth()->user()->entreprise_id;
            $user = new User();
            $user->civilite = $request->civilite;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->birthday = $request->birthday;
            $user->commission_rate = $request->commission_rate;
            $user->user_actif = 1;
            $user->profile_image_filename = $profil_filename;
            $user->entreprise_id = $id_entreprise ;

            $user->role = $request->role;
            $user->save();

        //     Mail::send('email.new_user', [
        //         'email' => $request->email,
        //         'nom' => $request->first_name,
        //         'role' => $request->role,
                
        //         ], function($message) use ($user) {
        //             $message->from('noreply@platineassurances.sn');
        //             $message->subject(' Création de compte COMPARATEUR');
        //             $message->to($user->email);
        // });


            return redirect()->back()->with('success', 'Utilisateur crée avec succès !');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }
        

    }

    public function updateUser(Request $request ,$id) {
        $users= DB::table('users')->where('id','=',$id)->get();
        return view('users.edit_user',compact('users'));
       
    }

    public function updateUserSaving(Request $request ,$id) {
        try {
            $users=User::find($id);
        if($request->update_password !== NULL && $request->update_password !==  ""){
            if(bcrypt($request->update_password) !== $users->password ){
                $request->validate([
                    'update_password' => [
                        'required',
                        'string',
                        'min:8',
                        'regex:/[A-Z]/',       // Au moins une majuscule
                        'regex:/[a-z]/',       // Au moins une minuscule (optionnel si vous voulez juste majuscule et caractères spéciaux)
                        'regex:/[0-9]/',       // Au moins un chiffre
                        'regex:/[@$!%*?&]/',   // Au moins un caractère spécial,               
                         ],
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email',
                ]);
                
    
                $users->first_name=$request->input('first_name');
                $users->last_name=$request->input('last_name');
                $users->email=$request->input('email');
                $users->phone=$request->input('phone');
                $users->address=$request->input('address');
                $users->commission_rate=$request->input('commission_rate');
                $users->password = bcrypt($request->update_password);
                $users->update();
                return redirect()->back()->with('success', 'utilisateur modifié avec succès !'); 
            }
            else{
                return redirect()->back()->with('error', 'Un probleme avec le changement de mot de passe !'); 
            }
            
    
        
        }else{
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
            ]);
            

            $users->first_name=$request->input('first_name');
            $users->last_name=$request->input('last_name');
            $users->email=$request->input('email');
            $users->phone=$request->input('phone');
            $users->address=$request->input('address');
            $users->birthday=$request->input('birthday');
            $users->commission_rate=$request->input('commission_rate');

            if(Auth::check() &&  Auth::user()->role === 'super'){
                $users->role = $request->input('role');
            }
            $users->update();
            return redirect()->back()->with('success', 'utilisateur modifié avec succès !'); 
        }
           
        } catch (Exception $e) {

            return redirect()->back()->with('error', "Votre utilisateur n'a pas été modifié ! ");
        }
    }

    public function updateStatus(Request $request, $id) {
        try {
        $user = User::findOrFail($id);
        $user->user_actif = $request->user_actif;
        $user->save();
            return redirect()->back()->with('success', 'Utilisateur desactivé avec succès');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la desactivation .');
        }
    }

    public function updateUserProfileSaving(Request $request ,$id) {
        
            $users=User::find($id);    
            $profil_filename = 'default-profile.jpg';   
            if ($request->file('profile_image_filename') != null) {
                $logo = $request->file('profile_image_filename');             
                $logo_extension = $logo->getClientOriginalExtension(); // Obtenir l'extension du fichier            
               // Générer un nom de fichier unique
                $profil_filename = Str::random(7) . '-profile_image.' . $logo_extension;
                try {
                    // Déplacer le fichier vers le dossier de stockage approprié
                    // $logo->move(storage_path('app/public/image/profile_image/'), $profil_filename);
                    $logo->storeAs('public/image/profile_image', $profil_filename);
                    // var_dump($profil_filename);exit;

                } catch (FileException $e) {
                    // Gérer les erreurs de déplacement de fichier
                    return redirect()->back()->with('error','photo non telecharger');
                }
                $users->profile_image_filename = $profil_filename;
                // var_dump($profil_filename);exit;

                $users->save();
                return redirect()->back()->with('success', 'Photo modifié avec succès !'); 
            }
            else{
                return redirect()->back()->with('error', 'Photo non modifié !'); 
            }
            
       
    }

    public function resetPassword(Request $request ,$id) {
        try {          
            $users=User::find($id);
            $request->validate([
                'update_password' => [
                    'required',
                    'string',
                    'min:8',
                ]
            ]);
            $newPassword = $request->update_password;    
            $users->password = bcrypt($newPassword);
        
            $users->update();
            // Dispatch job to send welcome email
            $token = hash_hmac('sha256', str_random(40), config('app.key'));
            DB::table('password_resets')->insert(['email' => $users->email, 'token' => $token, 'created_at' => new Carbon]);
            $job = new SendWelcomeEmail($token, $staff);
            $this->dispatch($job->onQueue('emails')->delay(10));
            // Mail::send('email.reset_pass_user', [
            //     'email' => $users->email,
            //     'nom' => $users->first_name, 
            //     'password'=> $newPassword,           
            //     ], function($message) use ($users) {
            //         $message->from('noreply@platineassurances.sn');
            //         $message->subject('Reset Mot de Passe');
            //         $message->to($users->email);
            // });
            
            return redirect()->back()->with('success', 'Mot de passe réinitialiser avec succès !'); 
        } catch (Exception $e) {
            return redirect()->back()->with('error', "Reset mot de passe n'a pas ete modifié ! ");
        }
    }

    public function AcitvateUser(Request $request ,$id) {
        
        try {
            
            $users=User::find($id);
            $request->validate([
                'password' => [
                    'required',
                    'string',
                    'min:8',
                ],
                'update_password' => [
                    'required',
                    'string',
                    'min:8',
                    'same:password',
                ],
            ]);
            $users->actif_user = 1;
            $users->password = bcrypt($request->password);
        
            $users->update();
            // Mail::send('email.reset_pass_user', [
            //     'email' => $users->email,
            //     'nom' => $users->first_name, 
            //     'password'=> $newPassword,           
            //     ], function($message) use ($users) {
            //         $message->from('noreply@platineassurances.sn');
            //         $message->subject('Reset Mot de Passe');
            //         $message->to($users->email);
            // });
            
            return redirect()->route('login')->with('success', 'Bienvenue votre compte est actif connectez-vous !'); 
        } catch (Exception $e) {
            return redirect()->back()->with('error', "Verifier votre mot de passe ! ");
        }
    }

    public function indexActiveUser($id){
        $user = User::find($id);

        // Si aucun utilisateur n'est trouvé
        if (!$user) {
            // Créer un objet utilisateur fictif avec juste l'ID
            $user = (object) ['id' => $id];
        }
        return view('users.activate',compact('user'));
    }

    public function passiveDelete($id){
        $users=User::find($id);
        $users->deleted_at = Carbon::now();
        $users->update();

        return redirect()->back()->with('success', 'utilisateur supprimé avec succès !'); 

    }

    public function restaurer($id){
        $users=User::find($id);
        $users->deleted_at = NULL;
        $users->update();

        return redirect()->back()->with('success', 'utilisateur restauré avec succès !'); 

    }
    
}
