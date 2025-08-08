<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Socialwork;

class TestController extends Controller
{

    public function addSocialNetwork(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'icon_html' => 'required|string',
                'link' => 'required|string',
            ]);

            // Create a new Socialwork instance and store it in the database
            Socialwork::create([
                'icon_html' => $validatedData['icon_html'],
                'link' => $validatedData['link'],
            ]);

            // Redirect the user back to the previous page
            return redirect()->back();
        } catch (\Exception $e) {
            // Log the error or return a response indicating the error
            return back()->withError('Error adding social network: ' . $e->getMessage());
        }
    }



    // Fonction pour mettre à jour un réseau social
    public function updateSocialNetwork(Request $request, $id)
    {
        $socialNetwork = Socialwork::findOrFail($id);

        $data = $request->validate([
            'icon_html' => 'required|string',
            'link' => 'required|url',
        ]);

        $socialNetwork->update([
            'icon_html' => $data['icon_html'],
            'link' => $data['link'],
        ]);

        return redirect()->back();
    }

    // Fonction pour obtenir tous les réseaux sociaux
    public function getSocialNetworks()
    {
        $socialNetworks = Socialwork::all();
        return view('admin.Socialwork.Socialwork', compact('socialNetworks'));
    }

    // Fonction pour supprimer un réseau social
    public function deleteSocialNetwork($id)
    {
        $socialNetwork = Socialwork::findOrFail($id);
        $socialNetwork->delete();

        return redirect()->back();
    }
}
