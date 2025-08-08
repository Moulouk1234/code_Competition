<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::all();
        return view('admin.faqs', compact('faqs'));
    }

    public function edit($id)
    {
        $faq = Faq::find($id);
    
        return view('admin.edit-faq', compact('faq'));
    }
    public function listfeq()
    {        $faqs = Faq::all();

      
        return view('user.faqs', compact('faqs'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required',
            'details' => 'required',
        ]);

        $faq = Faq::find($id);
        $faq->update([
            'question' => $request->input('question'),
            'details' => $request->input('details'),
        ]);

        return redirect('/faqs')->with('success', 'FAQ updated successfully!');
    }

    public function add()
    {
        return view('admin.faqs');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required',
            'details' => 'required',
        ]);

     
        Faq::create($data);


        return redirect('/faqs')->with('success2', 'FAQ added successfully!');
    }

    public function delete($id)
    {
        $faq = Faq::find($id);
        $faq->delete();

        return redirect('/faqs')->with('success3', 'FAQ deleted successfully!');
    }
}
