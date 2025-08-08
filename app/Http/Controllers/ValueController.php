<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Value;
use Illuminate\Support\Facades\DB;

class ValueController extends Controller
{
    public function showAddForm()
    {
        $values = Value::all();

        return view('admin.ALLValues', compact('values'));
    }

    public function addValues(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',

        ]);

        Value::create($data);


        return redirect()->back();    }
    public function deleteValue($id)
    {
        $value = Value::find($id);

       
            DB::table('values')->where('id', $id)->delete();
            $value->delete();

            return redirect()->back();        

    }

public function updateValue(Request $request, $id)
{
    // Validate the request data
    $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
]);

    // Find the mission by ID
    $value = Value::find($id);

   
    // Update the mission with the new data
    $value->update([
        'title' => $request->input('title'),
        'description' => $request->input('description'),

    ]);

    // Redirect back with success message
    return redirect()->back();
}

}
