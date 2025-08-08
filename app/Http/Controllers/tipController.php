<?php

namespace App\Http\Controllers;
use App\Models\Tip;
use Illuminate\Http\Request;
use App\Models\category;

use App\Http\Requests\tipRequest;

class tipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories=Category::all();
        $tips=Tip::all();
        return view('admin.tips.index', compact('categories','tips'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {



    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(tipRequest $tiprequest)
    {
        $tip=Tip::create($tiprequest->all());
        return redirect()->route('tips.index');
    }

    /**
     * Display the specified resource.
     */

    public function show(Tip $tip)
    {

        $categories=Category::all();
        return view('user.tips.tip', compact('tip','categories'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tip $tip)
    {
        $tips=Tip::all();
        $categories=Category::all();
        return view('admin.tips.edit', compact('tip','categories','tips'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(tipRequest $tiprequest,tip $tip)
    {
        $data = $tiprequest->validate([
            'title' => 'required|string',
            'description' => 'required|string',

            'contenu' => 'required|string',


            'category_id' => 'required|exists:categories,id',
        ]);

        $tipp = Tip::find($tip->id);
        $tipp->update($data);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tip $tip)
    {
        $tip->DELETE();
        return back();
    }
    public function show_user($category_id = null)
    {
        $selectedCategoryId = $category_id;
        $categories = Category::all();
        $tips = $category_id ? Category::findOrFail($category_id)->tips : Tip::all();

        return view('user.tips.show', compact('tips', 'categories', 'selectedCategoryId'));
    }


}
