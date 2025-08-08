<?php

namespace App\Http\Controllers;
use App\Models\articl;
use App\Models\tag;
use App\Models\Tip;
use Illuminate\Http\Request;
use App\Models\category;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\categoryRequest;

class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   $t=articl::count();
        $tip=Tip::count();
        $categories = category::paginate(8);
        return view('admin.categories.index', compact('categories','t','tip'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(categoryRequest $categoryrequest)
    {
        $category=category::create($categoryrequest->all());
        $tags = explode('#', $categoryrequest->input('tags'));

        foreach ($tags as $tagName) {
            // Vérifier si le nom du tag n'est pas vide
            if (trim($tagName) !== '') {
                $tag = tag::firstOrCreate(['nom' => trim($tagName)]);
                $tagIds[] = $tag->id;
            }
        }
        $category->tags()->sync($tagIds);
        return redirect()->route('categories.index')->with('success', 'Category added successfully'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(category $category)
    {
        $articls = Category::find($category->id)->articls;
        $category_id = 1; // Remplacez 1 par l'ID de votre catégorie
        $category->increment('count');
        return view('admin.categories.show', compact('category','articls'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(categoryRequest $categoryrequest,category $category)
    { $tags = explode(',', $categoryrequest->input('tags'));

        foreach ($tags as $tagName) {
            // Vérifier si le nom du tag n'est pas vide
            if (trim($tagName) !== '') {
                $tag = tag::firstOrCreate(['nom' => trim($tagName)]);
                $tagIds[] = $tag->id;
            }
        }
        $category->tags()->sync($tagIds);
        $category->update($categoryrequest->all());
        return redirect()->route('categories.index')->with('success1', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(category $category)
    {
        $category->DELETE();
        return back()->with('success2','the category is successfully deleted ');
    }

    public function nav()
    {   $tags=tag::all();
        $categories=Category::all();
        return view('user.template', compact('categories','tags'));
    }
    public function n()
    {
        $categories=Category::all();
        return view('user.pack',compact('categories'));
    }
    public function show_user(category $category)
    {
        $articls = $category->articls()->paginate(9);
        $categories=category::all();
        $cat=$category;
        $sim_tips = Tip::where('category_id', '!=',$category->id)->paginate(10);
        $category->increment('count');
        $tips = Tip::select('tips.id', 'tips.title', DB::raw('SUM(rates.rating) as total_rating'))
            ->leftJoin('rates', 'tips.id', '=', 'rates.tip_id')
            ->where('tips.category_id', $category->id)
            ->groupBy('tips.id', 'tips.title')
            ->orderByDesc('total_rating')
            ->take(5)
            ->get();



        return view('user.categories.show', compact('category','cat','articls','categories','tips','sim_tips'));
    }

    public function categories()
    {   $categories=category::all();
        return   $categories ; }




}
