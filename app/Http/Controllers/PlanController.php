<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanModules;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $allPlans = Plan::all();
        return view('admin.plans.index', ['allPlans' => $allPlans]);
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['price'] = str_replace(',', '.', $data['price']);
        $data['duration'] = 30;
        $data['type'] = 'monthly';
        $data['slug'] = $data['name'];
        $planCreated = Plan::create($data);

        $modules = explode(',', $data['modules']);
        foreach($modules as $module) {
            PlanModules::create([
                'plan_id' => $planCreated->id,
                'module' => $module
            ]);
        }

        return redirect()->route('plans.index')->with('success', 'Plan created successfully.');
    }
}
