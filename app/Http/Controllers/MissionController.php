<?php
namespace App\Http\Controllers;

use App\Models\SetUp;
use App\Models\Value;
use Illuminate\Http\Request;
use App\Models\Mission;
use Illuminate\Support\Facades\DB;

class MissionController extends Controller
{
    public function showAddForm()
    {
        $missions = Mission::all();

        return view('admin.ALLMission', compact('missions'));
    }

    public function addMission(Request $request)
    {
        $data = $request->validate([
            'mission' => 'required|string|unique:missions',
        ]);

        Mission::create($data);


        return redirect()->back()->with('success', 'Mission added.');    }
    public function deleteMission($id)
    {
        $mission = Mission::find($id);


            DB::table('missions')->where('id', $id)->delete();
            $mission->delete();

            return redirect()->back()->with('success3', 'Mission deleted.'); 

    }

public function updateMission(Request $request, $id)
{
    // Validate the request data
    $request->validate([
        'mission' => 'required|string|max:255',
    ]);

    // Find the mission by ID
    $mission = Mission::find($id);


    // Update the mission with the new data
    $mission->update([
        'mission' => $request->input('mission'),
    ]);

    // Redirect back with success message
    return redirect()->back()->with('success2', 'Mission updated.'); 
}
public function showAbout(){
    $missions = Mission::all();
    $values = Value::all();
    $setups = SetUp::all();
    return view('about', compact('missions','values','setups'));
}

}
