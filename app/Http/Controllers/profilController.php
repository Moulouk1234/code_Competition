<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Friend;
use App\Models\Like;
use App\Models\Category;
use App\Http\Requests\friendRequest;
use Illuminate\Http\Request;

class profilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show($userId)
    {
        $user = User::find($userId);
        $likedArticleIds = $user->likes()->pluck('articl_id');

        // Récupérer les catégories des articles aimés
        $interestedCategories = Category::whereIn('id', function ($query) use ($likedArticleIds) {
            $query->select('category_id')
                ->from('articls')
                ->whereIn('id', $likedArticleIds);
        })->get();
        $user = User::find($userId);
        $questionHistory = $user->questions()->latest()->take(10)->get();

        return view('user.profile.show', compact('user','interestedCategories','questionHistory'));


    }

    public function store(friendRequest $friendrequest)
{
    // Récupérer les données du formulaire
    $userId = $friendrequest->input('userId');
    $friendId = $friendrequest->input('friendId');

    // Vérifier si les utilisateurs sont déjà amis
    $existingFriendship = Friend::where('user_id', $userId)
                                 ->where('friend_id', $friendId)
                                 ->exists();

    // Si les utilisateurs ne sont pas déjà amis, créer une nouvelle relation
    if (!$existingFriendship) {
        // Créer une relation d'ami dans la table "friends"
        $friendship = Friend::create([
            'user_id' => $userId,
            'friend_id' => $friendId
        ]);
    }
    // Rediriger ou retourner une réponse
    return back()->with('friendAdded', !$existingFriendship);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function check($userId, $friendId)
{
    // Vérifier si les utilisateurs sont déjà amis
    $areFriends = Friend::where('user_id', $userId)
                       ->where('friend_id', $friendId)
                       ->exists();
    return $areFriends ;

}



public function removeFriend(Request $request, $userId, $friendId)
{
    $friendship = Friend::where('user_id', $userId)
                        ->where('friend_id', $friendId)
                        ->first();

    if ($friendship) {
        $friendship->delete();
        return redirect()->back()->with('success', 'Ami retiré avec succès.');
    } else {
        return redirect()->back()->with('error', 'La relation d\'ami n\'existe pas.');
    }
}

}
