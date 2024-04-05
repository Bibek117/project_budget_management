<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repostories\ContactRepository;
use App\Models\User;
use App\Models\Contacttype;
use App\Models\Contact;

class ContactController extends Controller
{
    private $contactRepo;
    public function __construct(ContactRepository $contactRepo)
    {
        $this->contactRepo = $contactRepo;
    }


    //get timeline for ajax
    public function getSingleAjax(string $id)
    {
        $result = DB::select('select users.id,users.username from users inner join contacts on users.id = contacts.user_id where contacts.contacttype_id = ?', [$id]);
        return response()->json(['users' => $result]);
    }

    //assign contact form
    public function create(String $id)
    {
        $assignedContatcs = DB::table('contacts')->where('user_id', $id)->pluck('contacttype_id')->toArray();
        $user = User::find($id);
        return view('users.assignContacttype', ['user' => $user, 'assignedContacts' => $assignedContatcs, 'contacttypes' => Contacttype::get()]);
    }
    public function createContact(Request $request)
    {
        $validatedRequest = $request->validate([
            'user_id' => 'required',
            'contacttype_id' => 'required|array'
        ]);
        $data = [];
        foreach ($validatedRequest['contacttype_id'] as $contacttypeId) {
            $data[] = [
                'user_id' => $validatedRequest['user_id'],
                'contacttype_id' => $contacttypeId,
            ];
        }
        if (count($data) == 1) {
            Contact::create($data[0]);
        } else {
            Contact::insert($data);
        }
        return redirect()->route('user.index')->withSuccess("Contact Types assigned successfully");
    }
}
