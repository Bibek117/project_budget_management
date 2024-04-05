<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Models\Contacttype;
use App\Repostories\ContacttypeRepository;

class ContacttypeController extends Controller
{
    private $contacttypeRepo;
    public function __construct(ContacttypeRepository $contacttypeRepo){
        $this->contacttypeRepo = $contacttypeRepo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $result = $this->contacttypeRepo->getAll();
            return view('contacts.index',['contacttypes'=>$result]);
        } catch (\Exception $e) {
            return response()->json(['result' => $e, 'success' => false]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedReq = $request->validate([
            'contacttypes'=>'required|array',
            'contacttypes.*.name'=>'required|string'
        ]);
       $contactData = [];
       foreach($validatedReq['contacttypes'] as $contacttype){
         $contactData[] = ['name'=>$contacttype['name']];
       }
       if(count($contactData) == 1){
        Contacttype::create($contactData[0]);
       }else{
        Contacttype::insert($contactData);
       }
       return redirect()->route('contacttype.index')->withSuccess("Contact Type created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $result = $this->contacttypeRepo->getById($id);
            return response()->json(['result' => $result, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['result' => $e->getMessage(), 'success' => false]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $contacttype = Contacttype::find($id);
        return view('contacts.update',['contacttype'=>$contacttype]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedReq = $request->validate([
            'name' => 'required'
        ]);
        try {
            $result = $this->contacttypeRepo->updateById($id, $validatedReq);
            return redirect()->route('contacttype.index')->withSuccess('Contact type updated successfully');
        } catch (\Exception $e) {
            return response()->json(['result' => $e, 'success' => false]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = $this->contacttypeRepo->deleteById($id);
            return redirect()->route('contacttype.index')->withSuccess('Contact Type deleted successfully');
        } catch (\Exception $e) {
            return response()->json(['result' => $e, 'success' => false]);
        }
    }
}
