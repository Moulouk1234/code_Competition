<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'email' => 'required',
            'message' => 'required',
        ]);

        $msg= new Contact();
        $msg->firstname=$request->input('firstname');
        $msg->lastname=  $request->input('lastname');
        $msg->email=  $request->input('email');
        $msg->phone=  $request->input('phone');
        $msg->message=$request->input('message');
        $msg->user_id = Auth::id();
        $msg->save();


        return redirect()->route('user.homeuser')->with('success', 'Contact added successfully.'); 
    }
    public function index()
    {
        $messages = Contact::all();
        return view('admin.messages.index', compact('messages'));
    }
    public function show($id)
    {

        $msg = Contact::findOrFail($id);
        if ($msg->is_read!=1)
        { $msg->is_read=1;
            $msg->save(); }

        return view('admin.messages.show')->with('msg', $msg);
    }
    public function destroy($id)
    {

        $msg = Contact::find($id);
        if ($msg->delete()){
            return redirect()->route('message.index')->with('success2', 'Contact deleted successfully.');
        }

    }





}
