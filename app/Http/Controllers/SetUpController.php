<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SetUp;
use Illuminate\Support\Facades\DB;

class SetUpController extends Controller
{
    public function showAddForm()
    {
        $values = SetUp::all();

        return view('admin.SetUps', compact('values'));
    }

    public function addSetUp(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',

        ]);

        SetUp::create($data);


        return redirect()->back()->with('success', 'Set Up added.');   }
    public function deleteSetUp($id)
    {
        $value = SetUp::find($id);

       
            DB::table('setups')->where('id', $id)->delete();
            $value->delete();

            return redirect()->back()->with('success2', 'Set Up deleted.');        

    }

public function updateSetUp(Request $request, $id)
{
    // Validate the request data
    $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
]);

    // Find the mission by ID
    $value = SetUp::find($id);

   
    // Update the mission with the new data
    $value->update([
        'title' => $request->input('title'),
        'description' => $request->input('description'),

    ]);

    // Redirect back with success message
    return redirect()->back()->with('success3', 'Set Up updated.');;
}

}
