<?php
namespace App\Http\Controllers;
use App\Notifications\NewsletterNotification;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewsletterController extends Controller
{
    public function CreateNewsletter()
    {
        $users = User::where('is_admin',0)->get();

        foreach ($users as $user) {
            $createdAt = $user->created_at;
            $user->member_since = Carbon::parse($createdAt)->diffForHumans();
        }
        return view('admin.newsletters.newsletter')->with('users', $users);
    }

    public function sendNotification2(Request $request)
    {
        $users = User::all();

        // Customize the content for the notification
        $notificationContent = $request->input('message', 'Default notification content');

        foreach ($users as $user) {

            $user->notify(new NewsletterNotification($notificationContent));
        }

        return redirect()->back();
    }

    public function sendNotification(Request $request)
    {
        $selectedUserIds = explode(',', $request->input('selectedUsers', []));


        $users = User::whereIn('id', $selectedUserIds)->where('is_admin',0)->get();


        $notificationContent = $request->input('message', 'Default notification content');

        foreach ($users as $user) {
            $user->notify(new NewsletterNotification($notificationContent));
        }

        return redirect()->back();
    }
}
