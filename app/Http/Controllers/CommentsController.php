<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Comments;
use App\Models\BadWordComments;

class CommentsController extends Controller
{


    public function add_Comments(Request $request)
    {
        $data = $request->validate([
            'contenu' => 'required|string',
            'articl_id' => 'required|exists:articls,id',
        ]);
    
        $comment = Comments::create([
            'contenu' => $data['contenu'],
            'articl_id' => $data['articl_id'],
            'rate' => 0,
            'user_id' => Auth::id(),
        ]);
    
        $badWords = BadWordComments::all();
    
        foreach ($badWords as $badWord) {
            if (stripos($comment->contenu, $badWord->word) !== false) {
                DB::table('comments')->where('id', $comment->id)->delete();
                return redirect()->back()->with('errorC', 'Inappropriate content. Comment not added.');
            }
        }
    
        return redirect()->back()->with('successC', 'Comment added successfully.');
    }
    

        public function all_Comments()
        {
            // Fetch questions from the database
            $comments = Comments::all();
            $badwords = BadWordComments::all();

            // Pass the questions to the view
            return view('admin.all_Comments', ['comments' => $comments],['badwords' => $badwords]);
        }
        public function allComments()
        {
            // Fetch questions from the database
            $comments = Commments::all();

            // Pass the questions to the view
            return view('user.listComments', ['comments' => $comments]);
        }

        public function delete_Comment($id)
        {
            $comment = Comments::findOrFail($id);
            $comment->delete();

            return redirect()->back()->with('successcc', 'Comment deleted successfully.');
        }




        public function getDetails($id)
        {
            $comment = Comments::find($id);

            $comment->increment('rate');
            return view('comment-details', compact('comment'));
        }


        public function dislike($user_Id,$com_Id)
        {
                Comments::where('id', $com_Id)
                ->where('user_id', $user_Id)
                ->where('rate', '>', 0)
                ->decrement('rate');
                 return back();
        }
      function like($user_Id,$com_Id)
        {
            $comment = Comments::find($com_Id);
            $comment->increment('rate');
            return back();
        }
        public function check($userId, $comId)
        {
            // Vérifier si l'utilisateur a aimé le commentaire et si la valeur de 'rate' est égale à 1
            $liked = comments::where('user_id', $userId)
                ->where('id', $comId)
                ->where('rate', 1)
                ->exists();

            return $liked;
        }


        }



