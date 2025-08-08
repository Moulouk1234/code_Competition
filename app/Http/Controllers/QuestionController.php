<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Database\Eloquent\Model;
use App\Models\BadWord;
use App\Models\Category;

use App\Models\RateQuestion;

class QuestionController extends Controller
{
    public function create()
    {
        return view('question');
    }
    public function gotoaddQuestion($id)
    {
        $categories = Category::all();
        return view('user.addQuestion', ['id' => $id, 'categories' => $categories]);
    }

    public function add_Question(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'tried' => 'nullable|string',
            'expected' => 'nullable|string',
            'tags' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx', // Adjust file types as needed
        ]);
    
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $path = $file->store('files');
        } else {
            $path = null;
        }
    
        $question = Question::create([
            'title' => $request->input('title'),
            'details' => $request->input('details'),
            'tried' => $request->input('tried'),
            'expected' => $request->input('expected'),
            'tags' => $request->input('tags'),
            'file_path' => $path,
            'user_id' => $id,
            'category_id' => $request->input('selectedCategory'), // Use 'selectedCategory' here
        ]);
    
        $tags = explode('#', $request->input('tags'));
        $tagIds = [];
    
        foreach ($tags as $tagName) {
            // Vérifier si le nom du tag n'est pas vide
            if (trim($tagName) !== '') {
                $tag = tag::firstOrCreate(['nom' => trim($tagName)]);
                $tagIds[] = $tag->id;
            }
        }
    
        $question->tags()->sync($tagIds);
        $badWords = BadWord::all();
    
        foreach ($badWords as $badWord) {
            if (stripos($question->details, $badWord->word) !== false) {
                // Display danger alert for inappropriate content
                return redirect()->route('FilterMyQuestions', ['id' => $id])->with('errorC', 'Inappropriate content detected.');
            }
        }
    
        // If no inappropriate content is detected, display success alert
        return redirect()->route('FilterMyQuestions', ['id' => $id])->with('success', 'Question added successfully.');
    }
    
    public function gotoaddQuestionCat($id)
    {        $category = Category::findOrFail($id);


        return view('user.addQuestionByCategory', ['id' => $id,'category'=>$category]);
    }
    public function add_QuestionCat(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'tried' => 'nullable|string',
            'expected' => 'nullable|string',
            'tags' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx', // Adjust file types as needed
        ]);

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $path = $file->store('files');
        } else {
            $path = null;
        }

        $question = Question::create([
            'title' => $request->input('title'),
            'details' => $request->input('details'),
            'tried' => $request->input('tried'),
            'expected' => $request->input('expected'),
            'tags' => $request->input('tags'),
            'file_path' => $path,
            'user_id' => Auth::id(),
            'category_id' => $id, // Use the provided $category_id
        ]);

        $tags = explode('#', $request->input('tags'));
        $tagIds = [];
        foreach ($tags as $tagName) {
            // Check if the tag name is not empty
            if (trim($tagName) !== '') {
                $tag = Tag::firstOrCreate(['nom' => trim($tagName)]); // Correct model name to 'Tag'
                $tagIds[] = $tag->id;
            }
        }
        $question->tags()->sync($tagIds);

        $badWords = BadWord::all();

        foreach ($badWords as $badWord) {
            if (stripos($question->details, $badWord->word) !== false) {
                DB::table('questions')->where('id', $question->id)->delete();
                break;
            }
        }


        return redirect()->route('FilterMyQuestionscat', ['id' => $id])->with('success2', 'Question added successfully.');
    }

    public function all_QuestionsA()
    {
        // Fetch questions from the database
        $badwords = BadWord::all();

        // Paginate the questions
        $questions = Question::paginate(9);

        // Pass the questions and other data to the view
        return view('admin.all_Questions', compact('questions', 'badwords'));
    }

    public function allQuestions()
    {
        // Fetch questions from the database
        $questions = Question::paginate(9);

        // Pass the questions to the view
        return view('user.listQuestions', ['questions' => $questions]);
    }
    public function allQuestionsCat($id)
    {
        $category = Category::find($id);

        $questions = $category->questions()->paginate(9);

        return view('user.listQuestions', ['questions' => $questions, 'category' => $category]);
    }
    public function allQuestionsCatuser($id)
    {

        $category = Category::find($id);


        $questions = $category->questions;


        return $questions ;
    }
    public function delete_Question($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->back()->with('success3', 'Question deleted successfully.');
    }

    public function showAnswersByQuestion($id)
    {
        $question = Question::findOrFail($id);
        $answers = $question->answers()->with('user', 'rate')->get();
        $numberOfAnswers = $answers->count();
        $question->increment('count');


        return view('user.listAnswerByQ', [
            'question' => $question,
            'numberOfAnswers' => $numberOfAnswers,
            'answers' => $answers,
        ]);
    }
    public function AnswerQuestion($id)
    {
        $question = Question::findOrFail($id);
        $answers = $question->answers()->with('user', 'rate')->get();
        $numberOfAnswers = $answers->count();
        $question->increment('count');

        return view('user.answerPage', [
            'question' => $question,
            'numberOfAnswers' => $numberOfAnswers,
        ]);

    }

    public function Show(Question $question)
    {
        return view('answer', compact('question'));
    }

    public function SaveAnswer(Request $request, $questionId, $userId)
    {
        $request->validate([
            'content' => 'required|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx', // Adjust file types as needed
        ]);

        // Retrieve the question from the database
        $question = Question::findOrFail($questionId);

        $data = [
            'content' => $request->input('content'),
            'user_id' => $userId,
        ];

        // Handle file upload
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $path = $file->store('answer_files'); // Adjust the storage path as needed
            $data['file_path'] = $path;
        }

        // Create the answer associated with the question
        $question->answers()->create($data);

        return redirect('/allQuestions')->with('success4', 'Answer added successfully.');
    }

    public function getDetails($id)
    {
        $question = Question::find($id);

        $question->increment('count');
        return view('question-details', compact('question'));
    }
    public function editQuestion($id)
    {
        $question = Question::find($id);
        $categories=Category::all();
        return view('user.editQuestion', compact('question','categories'));
    }
    public function updateQuestion(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'tried' => 'nullable|string',
            'expected' => 'nullable|string',
            'tags' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx',
        ]);

        $question = Question::find($id);

        $tags = explode(',', $request->input('tags'));

        foreach ($tags as $tagName) {
            // Vérifier si le nom du tag n'est pas vide
            if (trim($tagName) !== '') {
                $tag = tag::firstOrCreate(['nom' => trim($tagName)]);
                $tagIds[] = $tag->id;
            }
        }
        $question->tags()->sync($tagIds);
        $question->update($request->all());
        // No bad words found, proceed with the update
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $path = $file->store('files');
        } else {
            $path = null;
        }

        $question->update([
            'title' => $data['title'],
            'details' => $data['details'],
            'tried' => $data['tried'],
            'expected' => $data['expected'],
            'tags' => $data['tags'],
            'file_path' => $path,
        ]);

        $badWords = BadWord::all();
        foreach ($badWords as $badWord) {
            if (stripos($question->details, $badWord->word) !== false) {
                DB::table('questions')->where('id', $id)->delete();
                break;
            }
        }
        Session::flash('success5', 'Question updated successfully.');

        return redirect('allQuestions');
    }

    public function downloadFile($id)
    {
        $question = Question::find($id);

        if ($question && Storage::exists($question->file_path)) {
            return response()->download(storage_path('app/' . $question->file_path));
        } else {
            abort(404);
        }}
    public function filterByCreatedAt(Request $request)
    {
        $questions = Question::orderBy('created_at', 'desc')->paginate(9);


        // Pass the filtered questions to the view
        return view('user.listQuestions', compact('questions'));
    }
    public function filterByCount(Request $request)
    {
        $questions = Question::orderBy('count', 'desc')->paginate(9);

        return view('user.listQuestions', compact('questions'));
    }

    public function FilterQunanswered(Request $request)
    {

        $questions = Question::whereDoesntHave('answers')->paginate(9);

        return view('user.listQuestions', compact('questions'));
    }
    public function FilterMyQuestions($id)
    {
        $questions = Question::where('user_id', $id)->paginate(9);

        return view('user.listQuestions', compact('questions'));
    }
    public function calculateInterestRate($questionId)
    {
        $question = Question::findOrFail($questionId);

        $totalViews = Question::sum('count');

        if ($totalViews > 0) {
            $interestRate = ($question->count / $totalViews) * 100;
        } else {
            $interestRate = 0;
        }
        $interestRate = number_format($interestRate, 2);

        return $interestRate;
    }

    public function filterByCreatedAtA(Request $request)
    {
       // $questions = Question::orderBy('created_at', 'desc')->get();
        $questions = Question::orderBy('created_at', 'desc')->paginate(9);
        $badwords = BadWord::all();


        return view('admin.all_Questions', compact('questions','badwords'));
    }
    public function filterByCountA(Request $request)
    {

        $questions = Question::orderBy('count', 'desc')->paginate(9);

        $badwords = BadWord::all();


        return view('admin.all_Questions', compact('questions','badwords'));
    }

    public function filterQunansweredA(Request $request)
    {
        //$questionsbase = Question::all();
        $questions = Question::whereDoesntHave('answers')->paginate(9);
        $badwords = BadWord::all();

        return view('admin.all_Questions', compact('questions','badwords'));
    }
    public function all()
    {
        $questionCount = Question::all()->count();
        return $questionCount ;}
}
