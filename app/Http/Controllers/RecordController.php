<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repostories\RecordRepository;

class RecordController extends Controller
{
    private $recordRepo;
    //constructer
    public function __construct(RecordRepository $recordRepository){
        $this->recordRepo = $recordRepository;
    }


    public function createRecord(Request $request){
        $validatedRequest = $request->validate([
            'user_id'=>'required',
            'project_id'=>'required'
        ]);
        try {
            $result = $this->recordRepo->create($validatedRequest);
            return response()->json(['result' => $result, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['result' => $e, 'success' => false]);
        }
    }
}