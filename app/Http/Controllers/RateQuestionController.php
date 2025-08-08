<?php

namespace App\Http\Controllers;
use App\Models\RateQuestion;
use App\Models\Answer;
use Illuminate\Http\Request;

class RateQuestionController extends Controller
{public function rateAnswer(Request $request)
    {
        // You can add validation and authentication checks here

        // Check if the user has already rated this answer
        $answer = Answer::find($request->input('answerId'));
        dd($answer);

        if ($answer) {
            $rate = new RateQuestion([
                'user_id' => auth()->user()->id,
                'answer_id' => $answer->id,
                'question_id' => $answer->question_id, // Access question_id through the question relationship
                'rating' => $request->input('rating'),
            ]);
            dd($rate);
            $rate->save();

            return response()->json(['message' => 'Rating added successfully']);
        }

        return response()->json(['error' => 'Answer not found'], 404);
    }

    public function getRate($questionId, $answerId)
    {
        // Retrieve the like record for the specified user and article
        $num = RateQuestion::where('user_id', auth()->user()->id)
            ->where('answer_id', $answerId)
            ->where('question_id', $questionId)
            ->first();

        return $num;
    }
}
