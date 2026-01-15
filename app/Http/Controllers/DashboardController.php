<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $yourProjects = Auth::user()->projects;
        return response()->json(['projects' => $yourProjects], 200);
    }

    public function pending()
    {
        $pendingProjects = Auth::user()->projects()->where('status', 'pending')->get();
        return response()->json(['projects' => $pendingProjects], 200);
    }

    public function complete()
    {
        $completeProjects = Auth::user()->projects()->where('status', 'complete')->get();
        return response()->json(['projects' => $completeProjects], 200);
    }

    public function canceled()
    {
        $canceledProjects = Auth::user()->projects()->where('status', 'canceled')->get();
        return response()->json(['projects' => $canceledProjects], 200);
    }
}
