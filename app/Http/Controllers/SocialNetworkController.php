<?php

namespace App\Http\Controllers;

use App\Models\Socialwork;
use Illuminate\Http\Request;

class SocialNetworkController extends Controller
{
    public function addSocialwork(Request $request) {
        $data = $request->validate([
            'icon' => 'required',
            'link' => 'required',
        ]);

        Socialwork::create($data);

        return redirect()->back();
    }

    public function updateSocialwork(Request $request, $id) {
        $data = $request->validate([
            'icon' => 'required',
            'link' => 'required',
        ]);

        $socialwork = Socialwork::find($id);
        if ($socialwork) {
            $socialwork->update($data);
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function deleteSocialwork($id) {
        $socialwork = Socialwork::find($id);

        $socialwork->delete();

        return redirect()->back();    }

    public function getAllSocialworks() {
        $socialworks = Socialwork::all();
        return view('admin.SocialNetwork.SocialNetwork', compact('socialworks'));
    }
    public function getAllSocial() {
        $socialworks = Socialwork::all();
        return   $socialworks;
    }


}
