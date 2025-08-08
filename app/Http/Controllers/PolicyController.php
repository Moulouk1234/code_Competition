<?php

namespace App\Http\Controllers;

use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;
use App\Models\PoliciyCategory;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = PrivacyPolicy::all();
        return view('admin.privacy-policy', compact('policies'));
    }


    public function showCategoryWithPolicies()
    {
        $categories = PoliciyCategory::all();

        return view('user.showPolicy', compact('categories'));
    }

public function addByCat(Request $request,$id)
{
    $data = $request->validate([
        'content' => 'required|string',
    ]);

    PrivacyPolicy::create([
        'category_id' => $id,
        'content' => $data['content'],
    ]);

    return redirect()->back();
}



    public function edit($id)
    {
        $policy = PrivacyPolicy::find($id);
        $categories = PoliciyCategory::all();
        return view('admin.policy-update', compact('policy', 'categories'));
    }
    public function update(Request $request, $id)
{
    $data = $request->validate([
        'content' => 'required|string',
        'category_id' => 'required|exists:categories,id',
    ]);

    $policy = PrivacyPolicy::find($id);
    $policy->update($data);

    return redirect()->back();
}

    public function destroy($id)
    {
        $policy = PrivacyPolicy::find($id);
        $policy->delete();

        return redirect()->back();
    }

public function showByCategory($category)
{
    $policies = PrivacyPolicy::where('category_id', $category)->get();
    return view('admin.policiesshowByCategory', compact('category', 'policies'));
}

}

