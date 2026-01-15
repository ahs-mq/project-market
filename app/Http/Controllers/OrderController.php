<?php

namespace App\Http\Controllers;

use App\Models\project as Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function send_offer(User $user, Project $project)
    {
        // Authorization check
        Auth::authorize('isSameUser', $project);
        // Add logic here to handle the acceptance of the order
        $project->update(['status' => 'offer_received']);
        return response()->json(['message' => 'Offer sent successfully'], 200);
    }
    public function accept(User $user, Project $project)
    {
        // Authorization check
        Auth::authorize('notSameUser', $project);
        // Add logic here to handle the acceptance of the order
        $project->update(['status' => 'accepted']);
        return response()->json(['message' => 'Order accepted successfully'], 200);
    }

    public function reject(User $user, Project $project)
    {
        // Authorization check
        Auth::authorize('notSameUser', $project);
        // Add logic here to handle the rejection of the order
        $project->update(['status' => 'rejected']);
        return response()->json(['message' => 'Order rejected successfully'], 200);
    }

    public function complete(User $user, Project $project)
    {
        // Authorization check
        Auth::authorize('notSameUser', $project);
        // Add logic here to handle the completion of the order
        $project->update(['status' => 'complete']);
        return response()->json(['message' => 'Order completed successfully'], 200);
    }
}
