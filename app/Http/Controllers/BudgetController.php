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
    public function createBudget()
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
        $timeline_id = $request->timeline_id;
        $budgetsData = [];

        foreach ($request->budgets as $budget) {
            $budgetsData[] = [
                'timeline_id' => $timeline_id,
                'name' => $budget['name'],
                'amount' => $budget['amount'],
            ];
        }

        if (count($budgetsData) == 1) {
            Budget::create($budgetsData[0]);
        } else {
            Budget::insert($budgetsData);
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
        return redirect()->route('project.show',$project->id)->withSuccess('Budget updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $req,string $id)
    {
          
            $result = $this->budgetRepo->deleteById($id);
            return redirect()->route('project.show',$req['project_id'])->withSuccess("Budget deleted successfully");
    }
}