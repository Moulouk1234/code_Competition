<?php

namespace App\Http\Controllers;
use ConsoleTVs\Charts\Facades\Charts;
use App\Models\Question;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\articl;
use App\Models\Like;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Category popularity statistics
        $categoryPopularity = DB::table('categories')
            ->join('questions', 'categories.id', '=', 'questions.category_id')
            ->select('categories.nom as category', DB::raw('count(questions.id) as question_count'))
            ->groupBy('categories.nom')
            ->orderByDesc('question_count')
            ->get();

        // Prepare data for the category popularity chart
        $labels = $categoryPopularity->pluck('category');
        $data = $categoryPopularity->pluck('question_count');

        // Questions per day statistics
        $questionsPerDay = DB::table('questions')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as question_count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        // Extract simple values from the date objects
        $questionLabels = $questionsPerDay->pluck('date')->map(function ($dateObject) {
            return date('Y-m-d', strtotime($dateObject));
        });

        $questionData = $questionsPerDay->pluck('question_count');

        // Article Graph
        $articles = articl::all(); // Assuming your model is named "Article"
        $likes = Like::groupBy('articl_id')->selectRaw('articl_id, count(*) as total')->pluck('total', 'articl_id')->toArray();

        // Calculate the percentage likes for each article
        $totalLikes = array_sum($likes);

        $likePercentages = [];
        foreach ($articles as $article) {
            $likeCount = $likes[$article->id] ?? 0;
            $likePercentage = $totalLikes> 0 ? ($likeCount / $totalLikes) * 100 : 0;
            $likePercentages[] = $likePercentage;
        }

        // Prepare data for the view
        $articleTitles = $articles->pluck('title')->toArray();

        ///User
        $userStats = DB::table('users')
            ->where('is_admin', 0)  // Exclude users with is_admin = 1
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as user_count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

// Extract data for the chart
        $userLabels = $userStats->pluck('date')->map(function ($date) {
            return date('Y-m-d', strtotime($date));
        });

        $userData = $userStats->pluck('user_count');



        return view('admin.dashboard', compact('labels', 'data', 'questionLabels', 'questionData','articleTitles', 'likePercentages','userLabels', 'userData'));
    }


    public function popularityCat()
    {
        $categories = Category::all();

        $chart = Charts::create('pie', 'highcharts')
            ->title('Category Popularity')
            ->labels($categories->pluck('nom'))
            ->values($categories->pluck('count'))
            ->dimensions(500, 300);
        $users = User::all();


        return view('category.popularity', compact('chart','users'));
    }



}
