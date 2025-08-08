<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index()
    {
        return view('search.index');
    }

    public function search(Request $request)
    {
        $input = $request->input('search');
        $tables = ['answers','privacy_policies', 'articls','missions', 'bad_words', 'bad_word_comments', 'categories', 'comments', 'contacts', 'faqs', 'policiy_categories', 'questions', 'setups', 'tags', 'tips', 'users','values'];
        $columns = [
            'answers' => ['content'],
            'missions' => ['mission'],
            'articls' => ['title', 'description', 'contenu'],
            'bad_words' => ['word'], // Add more columns for other tables as needed
            'bad_word_comments' => ['word'],
            'categories' => ['nom', 'description'],
            'comments' => ['contenu'],
            'contacts' => ['firstname', 'lastname', 'email', 'phone', 'message'],
            'faqs' => ['question', 'details'],
            'policiy_categories' => ['name'],
            'questions' => ['title', 'details', 'tried', 'expected', 'tags'],
            'setups' => ['title', 'description'],
            'tags' => ['nom'],
            'tips' => ['title', 'description', 'contenu'],
            'users' => ['name', 'email', 'job', 'ecole', 'activity'],
            'values' => ['title', 'description'],
            'privacy_policies' => ['content'],
        ];

        $searchResults = $this->characterByCharacterSearch($input, $tables, $columns);

        return view('search.results', compact('searchResults', 'input'));
    }

    private function characterByCharacterSearch($input, $tables, $columns)
    {
        $results = [];

        foreach ($tables as $table) {
            foreach ($columns[$table] as $column) {
                $query = DB::table($table)
                    ->where(DB::raw("CONVERT($column USING utf8)"), 'LIKE', '%' . $input . '%')
                    ->get();

                foreach ($query as $result) {
                    $results[$table][] = $result;
                }
            }
        }

        return $results;
    }
}
