<?php

namespace App\Http\Controllers;
use App\Http\Requests\LikeRequest;
use App\Models\Like;
use App\Models\Rate;
use Illuminate\Http\Request;
use App\Http\Requests\rateRequest;
use Illuminate\Support\Facades\Auth;

class rateController extends Controller
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
    function store(rateRequest $raterequest)
    {

        $userId = $raterequest->input('userId');
        $tipId = $raterequest->input('tip_Id');

        Rate::create([
            'user_id' => $userId,
            'tip_id' => $tipId,
            'rating'=>1
        ]);

        return back();
    }
    public function check($userId, $tipId)
    {
        // Vérifier si il a fait j'aime
        $rate_yes = Rate::where('user_id', $userId)
            ->where('tip_id', $tipId)
            ->exists();
        return $rate_yes ;

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy($tip_id)
    { $user_id=Auth::id();
        // Retrieve the like record based on the article ID and user ID
        $rate = Rate::where('tip_id', $tip_id)
            ->where('user_id', $user_id)
            ->first();

        // Check if the like record exists

        // Delete the like record
        $rate->delete();
        return back();
    }

    function addRateToTip($userId,$tipId)
   {
       // Check if the user has already liked the article
       $existingrate =Rate::where('user_id', $userId)
                           ->where('tip_id', $tipId)
                           ->first();

       if ($existingrate) {
           // User has already liked the tip
           return false;
       }

       else{// Create a new rate record
       Rate::create([
           'user_id' => $userId,
           'tip_id' => $tipId,
           'rating' => true,
       ]);

       return true;}
   }
   function addRate($userId, $tipId)
{
    // Retrieve the like record for the specified user and tip
    $rate= Rate::where('user_id', $userId)
                ->where('tip_id', $tipId)
                ->where('rating', 1)
                ->first();

    // Check if a rate record was found
    if ($rate) {
        return true; // User has liked the article
    } else {
        return false; // User has not liked the article
    }
}


function getRate($userId, $tipId)
{
    // Retrieve the like record for the specified user and article
    $rate =Rate::where('user_id', $userId)
                ->where('tip_id', $tipId)
                ->where('rating', 1)
                ->first();

    // Check if a like record was found
    if ($rate) {
        return true; // User has liked the article
    } else {
        return false; // User has not liked the article
    }}


}
