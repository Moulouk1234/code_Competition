<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Rules\UniqueCategoryName;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\PoliciyCategory;


class CategoryPoliciyController extends Controller
{
    public function showAll()
    {
        $categories = PoliciyCategory::all();

        return view('admin.categories', compact('categories'));
    }

    public function create()
    {
        return view('admin.categorie-create');
    }




        public function save(Request $request)
        {
            $data = $request->validate([
                'name' => ['required', 'string' ],
            ]);


                PoliciyCategory::create($data);


            return redirect()->back();
        }


    public function details($id)
    {
        $category = PoliciyCategory::find($id);

        return view('admin.category-details', compact('category'));
    }



    public function edit($id)
    {
        $category = PoliciyCategory::findOrFail($id);

        return view('admin.category-edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
        ]);
    
        // Find the PoliciyCategory by ID
        $category = PoliciyCategory::findOrFail($id);
    
        // Update the attributes
        $category->update($data);
    
        return redirect('/categoriesPolicy')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $category = PoliciyCategory::findOrFail($id);
    
            // Delete the record from the database
            DB::table('policiy_categories')->where('id', $id)->delete();
    
            // Delete the model instance
            $category->delete();
    
            return redirect('/categoriesPolicy')->with('success', 'Category deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Category not found.');
        }
    }
    
}

