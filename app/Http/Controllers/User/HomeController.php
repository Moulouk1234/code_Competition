<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomEmail;


class HomeController extends Controller
{
    public function index(){
        return view('user.homeuser');
    }

    public function goregister()
    {
        return view('admin.liste_users.registerAdmin');

    }
    public function show()
    {

        $users = User::where('is_admin', 0)->take(4)->get();
        $users_admin = User::where('is_admin', 1)->take(4)->get();

        foreach ($users as $user) {
            $createdAt = $user->created_at;
            $user->member_since = Carbon::parse($createdAt)->diffForHumans();
            $user->numberOfLikes = $user->likes()->count();
            $user->numberOfFriends = $user->friends()->count();

            $userFriends = Friend::where('user_id', $user->id)->get();
            $friendFriends = Friend::where('friend_id', $user->id)->get();

            $user->friends = $userFriends->merge($friendFriends);
        }

        foreach ($users_admin as $user) {
            $createdAt = $user->created_at;
            $user->member_since = Carbon::parse($createdAt)->diffForHumans();
        }

        return view('admin.liste_users.index', compact('users', 'users_admin'));
    }

    public function show2()
    {

        $users = User::all();

        foreach ($users as $user) {
            $createdAt = $user->created_at;
            $user->member_since = Carbon::parse($createdAt)->diffForHumans();
            $userId = $user->id;
            $user->friends = Friend::where('user_id', $userId)->get();
        }


        return view('admin.liste_users.account_users', compact('users'));
    }
    public function show3()
    {

        $users = User::all();

        foreach ($users as $user) {
            $createdAt = $user->created_at;
            $user->member_since = Carbon::parse($createdAt)->diffForHumans();
        }

        return view('admin.liste_users.accounts_admins', compact('users'));
    }


    public function saveAdmin(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'photo' => ['nullable', 'image', 'max:2048'], // Facultatif : valider l'image si vous avez un champ photo
        ]);

        // Traitement de la photo si elle est présente
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
        }

        // Création de l'utilisateur avec le rôle d'administrateur
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->photo = $photoPath;
        $user->is_admin = 1;
        $user->save();

        // Redirection vers une autre page après l'enregistrement
        $users = User::all();
        return redirect()->route('admin_accounts');
    }
    public function editUser() {
        return view('user.editProfile');
    }
    public function update(Request $request)
    {        $avatarPath = 'avatar.png'; // Set a default avatar path

        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'password' => 'nullable|string|min:8',
            'photo' => 'nullable|image|max:2048', // max:2048 signifie que la taille maximale de la photo est de 2 Mo (2048 Ko)
        ]);

        // Mise à jour des informations de l'utilisateur
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->job = $request->job;
        $user->ecole = $request->ecole;
        $user->activity = $request->activity;
        $user->adresse = $request->adresse;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $user->photo = $photoPath;
        }
      

        $user->save();

        return view('user.homeuser');
    }

        public function sendEmail(Request $request)
    {
        $users = User::all();
        $message = $request->input('message');

        foreach ($users as $user) {
            Mail::to($user->email)->send(new CustomEmail($message));
        }

        return view('admin.newsletters.newsletter', compact('users'));
    }



    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->delete()){
            return redirect()->route('user.show', ['user' => $id]);
        }

    }




}
