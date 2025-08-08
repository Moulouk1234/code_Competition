<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   /** public function __construct()
    {
        $this->middleware('auth');
    }**/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.homeadmin');
    }
    public function editAdmin($id)
    {
        $user = User::findOrFail($id);
        return view('admin.liste_users.editProfileAdmin')->with('user',$user);
    }
    public function updateProfileAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'photo' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $user->photo = $photoPath;
        }
        $user->save();

        return redirect()->route('liste_user');
    }
    public function CreateNewsletter()
    {
        $users = User::all();

        foreach ($users as $user) {
            $createdAt = $user->created_at;
            $user->member_since = Carbon::parse($createdAt)->diffForHumans();
        }
        return view('admin.newsletters.newsletter')->with('users',$users);
    }
}
