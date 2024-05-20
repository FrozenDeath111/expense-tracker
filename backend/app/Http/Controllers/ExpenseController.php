<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\History;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function getExpenseData()
    {
        $expenses = Expense::all();
        $budgets = Budget::all();
        $histories = History::all();

        return response()->json([
            "expenses" => $expenses,
            "budgets" => $budgets,
            "histories" => $histories,
        ]);
    }
    public function getCategory($month, $year)
    {
        try {
            $budget = Budget::where("month", $month)->where("year", $year)->first();
            return response()->json([
                "status" => "success",
                "message" => "Data fetch Successful",
                "code" => 200,
                "budget" => $budget,
                "expenses" => $budget->expenses,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                "status" => "error",
                "message" => $th->getMessage(),
                "code" => 400,
            ]);
        }
    }
    public function setupBudget(Request $request)
    {
        $budget = new Budget();

        $budget->month = $request->month;
        $budget->year = $request->year;
        $budget->amount = $request->amount;

        try {
            $budget->save();
            foreach ($request->categories as $category) {
                $expenses = new Expense();
                $expenses->category = $category['category'];
                $expenses->amount = $category['budget'];
                $expenses->budget_id = $budget->id;
                $expenses->save();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Data Insert Successful',
                'code' => 200,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'code' => 500,
            ]);
        }
    }
    public function updateBudget(Request $request, $month, $year)
    {
        $budget = Budget::where('month', $month)->where('year', $year)->first();

        $budget->amount = $request->updatedAmount;

        try {
            $budget->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Budget Updated Successful',
                'code' => 200,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'code' => 500,
            ]);
        }
    }
    public function updateExpense(Request $request)
    {
        $expense = Expense::find($request->id);
        $expense->amount = $request->updatedAmount;

        try {
            $expense->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Expense Updated Successful',
                'code' => 200,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'code' => 500,
            ]);
        }
    }
    public function addCategory(Request $request)
    {
        $expense = new Expense();

        $expense->category = $request->category;
        $expense->amount = $request->amount;
        $expense->budget_id = $request->budget_id;

        try {
            $expense->save();
            return response()->json([
                'status' => 'success',
                'message' => 'New Expense Added Successful',
                'code' => 200,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'code' => 500,
            ]);
        }
    }
    public function addExpenseData(Request $request)
    {
        $history = new History();

        $history->amount = $request->amount;
        $history->category = $request->category;
        $history->description = $request->description;
        $history->date = $request->date;
        $history->method = $request->method;
        $history->location = $request->location;

        try {
            $history->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Expense Data Added Successful',
                'code' => 200,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'code' => 500,
            ]);
        }
    }
}
