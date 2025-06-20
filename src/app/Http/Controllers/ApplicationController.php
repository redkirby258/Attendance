<?php

namespace App\Http\Controllers;

use App\Models\AttendanceCorrectionRequest;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
   // ApplicationController.php
public function index()
{
    $user = Auth::user();

    $pendingApplications = AttendanceCorrectionRequest::where('user_id', $user->id)
        ->where('status', 'pending')
        ->with('user')
        ->get();

    $approvedApplications = AttendanceCorrectionRequest::where('user_id', $user->id)
        ->where('status', 'approved')
        ->with('user')
        ->get();

    return view('application.index', compact('pendingApplications', 'approvedApplications'));
}


}