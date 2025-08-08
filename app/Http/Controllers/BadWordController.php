<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BadWord;
use Illuminate\Support\Facades\DB;

class BadWordController extends Controller
{
    public function showAddForm()
    {
        $badWords = BadWord::all();

        return view('add-bad-word', compact('badWords'));
    }

    public function addBadWord(Request $request)
    {
        $data = $request->validate([
            'word' => 'required|string|unique:bad_words',
        ]);

        BadWord::create($data);


        return redirect()->back()->with('success', 'Bad Word added');    }
    public function deleteBadWord($id)
    {
        $badWord = BadWord::find($id);

       
            DB::table('bad_words')->where('id', $id)->delete();
            $badWord->delete();

            return redirect()->back()->with('success2', 'Bad Word deleted.');     

    }
    
}
