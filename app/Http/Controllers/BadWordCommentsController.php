<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BadWordComments;
use Illuminate\Support\Facades\DB;

class BadWordCommentsController extends Controller
{
    public function showAddFormComment()
    {
        $badWords = BadWordComments::all();

        return view('add-bad-wordComment', compact('badWords'));
    }

    public function addBadWordComment(Request $request)
    {
        $data = $request->validate([
            'word' => 'required|string|unique:bad_words',
        ]);

        BadWordComments::create($data);


        return redirect()->back()->with('success', 'Bad Word added');    }
    public function deleteBadWordComment($id)
    {
        $badWord = BadWordComments::find($id);

       
            DB::table('bad_word_comments')->where('id', $id)->delete();
            $badWord->delete();

            return redirect()->back()->with('success2', 'Bad Word deleted.');       

    }
    
}
