<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Question;
use App\Models\RateQuestion;
class AnswerController extends Controller
{

public function deleteAnswer($id)
{
    $answer = Answer::find($id);

    if ($answer) {
        $answer->delete();
        return redirect()->back()->with('success', 'Answer Deleted.');
    } else {
        return redirect()->back()->with('error', 'Erreur .');
    }
}
public function downloadFile($id)
    {
        $answer = Answer::find($id);

        if ($answer && Storage::exists($answer->file_path)) {
            return response()->download(storage_path('app/' . $answer->file_path));
        } else {
            abort(404);
        }

    }


    public function filterByCreatedAt($id)
{
    $question = Question::findOrFail($id);
    $answers = $question->answers()->orderBy('created_at', 'desc')->get();
    $numberOfAnswers = $answers->count();
        $question->increment('count');
    return view('user.listAnswerByQ', [
        'question' => $question,
        'numberOfAnswers' => $numberOfAnswers,
        'answers' => $answers,
    ]);
}


}
