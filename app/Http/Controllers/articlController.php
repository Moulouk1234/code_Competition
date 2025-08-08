<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;

use App\Http\Requests\articlRequest;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use App\Models\articl;
use App\Models\tag;
use App\Models\Category;
use App\Models\Comments;

use Illuminate\Support\Facades\Auth;
use App\Models\User;


class articlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($category_id = null)
    {
        $categories = Category::all();
        $selectedCategoryId = $category_id;
        $articls = $category_id ? Category::findOrFail($category_id)->articls()->paginate(8) : articl::paginate(8);

        return view('admin.articls.index', compact('articls', 'categories', 'selectedCategoryId'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        //$tags=tag::all();
        return view('admin.articls.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(articlRequest $articlRequest)
    {
        $article = articl::create($articlRequest->all());

        // Séparation des tags saisis dans le formulaire
        $tags = explode('#', $articlRequest->input('tags'));
        $tagIds = [];

        // Parcourir tous les tags mentionnés
        foreach ($tags as $tagName) {
            // Vérifier si le nom du tag n'est pas vide
            if (trim($tagName) !== '') {
                // Vérifier si le tag existe déjà dans la base de données
                $tag = Tag::firstOrCreate(['nom' => trim($tagName)]);

                // Incrémenter la colonne 'occ' si le tag existe déjà
                if ($tag->wasRecentlyCreated) {
                    $tag->increment('occ');;
                } else {
                    $tag->increment('occ');
                }

                // Ajouter l'ID du tag à la liste des IDs des tags associés à l'article
                $tagIds[] = $tag->id;
            }
        }

        // Synchroniser la relation entre l'article et les tags
        $article->tags()->sync($tagIds);
    

return redirect()->route('articls.index')->with('success', 'Article added succefully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(articl $articl)
    {
        $categories = Category::all();
        return view('admin.articls.show', compact('articl', 'categories'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(articl $articl)
    {
        $categories = Category::all();
        //$tags=tag::all();

        return view('admin.articls.edit', compact('articl', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(articlRequest $articlrequest, articl $articl)
    {
        $tags = explode(',', $articlrequest->input('tags'));
    
        foreach ($tags as $tagName) {
            // Vérifier si le nom du tag n'est pas vide
            if (trim($tagName) !== '') {
                $tag = tag::firstOrCreate(['nom' => trim($tagName)]);
                $tagIds[] = $tag->id;
            }
        }
        $articl->tags()->sync($tagIds);
        $articl->update($articlrequest->all());
    
        // Ajouter un message flash pour indiquer la modification réussie
    
        return redirect()->route('articls.index')->with('success2', 'Article updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(articl $articl)
    {
        $articl->DELETE();
        return back()->with('info', 'the article is successfully deleted ');
    }

    public function articles($category_id = null)
    {
        // Si un tag est spécifié, filtrer par tag

        // Sinon, si une catégorie est spécifiée, filtrer par catégorie
        if ($category_id) {
            $articls = Category::findOrFail($category_id)->articls()->paginate(9);
        } // Sinon, récupérer tous les articles
        else {
            $articls = articl::paginate(9);
            $selectedCategoryId = null;
            $selectedTagId = null;
        }
        $recommendedTags = Tag::orderBy('occ', 'desc')->take(10)->get();
        $categories = Category::all();
        $max = Category::orderByDesc('count')->limit(5)->get();

        return view('user.articls.template', compact('articls', 'categories', 'max', 'recommendedTags'));

    }

    public function tags($tag_id = null)
    {
        if ($tag_id) {
            $tag = Tag::findOrFail($tag_id);
            $tag->increment('occ');
            $articls = $tag->articls()->paginate(9);

        } else {
            $articls = articl::paginate(9);
            $selectedCategoryId = null;
            $selectedTagId = null;
        }
        $recommendedTags = Tag::orderBy('occ', 'desc')->take(10)->get();
        $categories = Category::all();
        $max = Category::orderByDesc('count')->limit(5)->get();
        return view('user.articls.template', compact('articls', 'categories', 'max', 'recommendedTags'));

    }


    public function show_user(articl $articl)
    {
        $categories = Category::all();
        $art = $articl;
        $tags = $articl->tags()->get();
        foreach ($tags as $tag) {
            $tag->increment('occ');
        }
       
        $comments = $articl->comments()->get();

         
        return view('user.articls.show', compact('articl', 'categories', 'art','comments'));
    }

    public function like(articl $articl)
    {
        $articl->increment('like');
    }

    public function Dilike(articl $articl)
    {
        $articl->decrement('like');
    }

    public function artcat(articl $articl)
    {

        // Récupérer l'ID de la catégorie de l'article donné
        $categorieId = $articl->category_id;

        // Sélectionner les autres articles ayant la même catégorie
        $artcat = articl::where('category_id', $categorieId)
            ->where('id', '!=', $articl->id)
            ->take(3)
            ->get();
        return $artcat;

    }


    public function pdfarticle($id)
    {

        $article = Articl::findOrFail($id);

        $html = view('user.articls.printarticl', compact('article'))->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Article.pdf');
    }

    public function artprof(articl $articl)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        // Vérifier si l'article est déjà associé à l'utilisateur
        if ($user->saves()->where('articl_id', $articl->id)->exists()) {
            // Si l'article est déjà associé à l'utilisateur, le détacher
            $user->saves()->detach($articl->id);
            Session::flash('success', 'Article unsaved successfully!');
        } else {
            // Sinon, sauvegarder l'article
            $user->saves()->attach($articl->id);
            Session::flash('success', 'Article saved successfully!');
        }

        // Reste de votre code pour récupérer les informations du profil de l'utilisateur
        $likedArticleIds = $user->likes()->pluck('articl_id');
        $interestedCategories = Category::whereIn('id', function ($query) use ($likedArticleIds) {
            $query->select('category_id')
                ->from('articls')
                ->whereIn('id', $likedArticleIds);
        })->get();
        $questionHistory = $user->questions()->latest()->take(10)->get();

        return view('user.profile.show', compact('user', 'interestedCategories', 'questionHistory', 'articl'));
    }
}
