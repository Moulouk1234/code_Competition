<?php

namespace App\Http\Controllers;
use App\Models\like;
use Illuminate\Http\Request;
use App\Http\Requests\likeRequest;
use Illuminate\Support\Facades\Auth;


class LikeController extends Controller
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
    public function show( like $like)
    {

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


    public function destroy($articl_id)
    { $user_id=Auth::id();
        // Retrieve the like record based on the article ID and user ID
        $like = like::where('articl_id', $articl_id)
            ->where('user_id', $user_id)
            ->first();

        // Check if the like record exists

            // Delete the like record
            $like->delete();
        return back();
    }

    function addLikeToArticle($userId, $articlId)
   {
       // Check if the user has already liked the article
       $existingLike = Like::where('user_id', $userId)
                           ->where('articl_id', $articlId)
                           ->first();

       if ($existingLike) {
           // User has already liked the article
           return false;
       }

       // Create a new like record
       Like::create([
           'user_id' => $userId,
           'articl_id' => $articlId,
           'liked' => true,
       ]);

       return true;
   }

   function addLike( $articlId)
{
    // Retrieve the like record for the specified user and article
    $like = Like::where('user_id', $userId)
                ->where('articl_id', $articlId)
                ->where('liked', 1)
                ->first();

    // Check if a like record was found
    if ($like) {
        return true; // User has liked the article
    } else {
        return false; // User has not liked the article
    }
}
function getLike($userId, $articlId)
{
    // Retrieve the like record for the specified user and article
    $like = Like::where('user_id', $userId)
                ->where('articl_id', $articlId)
                ->where('liked', 1)
                ->first();

    // Check if a like record was found
    if ($like) {
        return true; // User has liked the article
    } else {
        return false; // User has not liked the article
    }}
    //***************************************************************************************************** */
  function store(likeRequest $likerequest)
    {

        $userId = $likerequest->input('userId');
        $articlId = $likerequest->input('articlId');

           Like::create([
                'user_id' => $userId,
                'articl_id' => $articlId,
                'liked'=>1
            ]);

        return back();
    }
    public function check($userId, $articlId)
    {
        // Vérifier si il a fait j'aime
        $like_yes = Like::where('user_id', $userId)
            ->where('articl_id', $articlId)
            ->exists();
        return $like_yes ;

    }


    //***************************************************************************************************** */
}
