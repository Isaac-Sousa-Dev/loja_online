<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgentAIController extends Controller
{
    public function index()
    {
        return view('partner.agentai.index');
    }
}
