<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        return view('partner.budget.index');
    }


    public function create()
    {
        return view('partner.budget.create');
    }
}
