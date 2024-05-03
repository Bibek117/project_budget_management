<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repostories\BudgetRepository;
use App\Models\Budget;
use App\Models\Project;
use App\Http\Requests\StoreBudgetRequest;

class BudgetController extends Controller
{
    private $budgetRepo;
    public function __construct(BudgetRepository $budgetRepo)
    {
        $this->budgetRepo = $budgetRepo;
        $this->middleware('permission:create-budget|edit-budget|delete_budget|view-budget', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-budget', ['only' => ['store', 'createBudget']]);
        $this->middleware('permission:edit-budget', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-budget', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $result = $this->budgetRepo->getAll();
            return response()->json(['result' => $result, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['result' => $e->getMessage(), 'success' => false]);
        }
    }

    //budget create form
    public function create()
    {

        return view('projects.timelines.budgets.create', ['projects' => Project::all()]);
    }

    //budget edit form
    public function edit(String $id)
    {
        $budget = Budget::find($id);
        return view('projects.timelines.budgets.update', ['budget' => $budget]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBudgetRequest $request)
    {

        $this->budgetRepo->create($request->toArray());
        if($request->ajax()){
            return response()->json(['message'=>'Budgtes created successfully','success'=>true]);
        }

        return redirect()->route('project.index')->with('success', 'Budgets created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $result = $this->budgetRepo->getById($id);
            return response()->json(['result' => $result, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['result' => $e->getMessage(), 'success' => false]);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedInput = $request->validate([
            'name'=>'required|string',
            'amount'=>'required'
        ]);
        $project = Budget::find($id)->timeline->project;
        $result = $this->budgetRepo->updateById($id, $validatedInput);

        if($request->ajax()){
            return response()->json(['message'=>'Budget updated','success'=>true]);     
         }
        return redirect()->route('project.show',$project->id)->withSuccess('Budget updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $req,string $id)
    {
          
            $result = $this->budgetRepo->deleteById($id);
            if($req->ajax()){
                return response()->json(['message'=>'deleted','success'=>true]);
            }
            return redirect()->route('project.show',$req['project_id'])->withSuccess("Budget deleted successfully");
    }
}
