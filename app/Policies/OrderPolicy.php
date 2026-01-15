<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function isSameUser(User $user, Project $project): Response
    {
        return $user->id === $project->user_id
            ? Response::allow()
            : Response::deny('Unauthorized - You cannot send an offer to your own project.');
    }

    public function notSameUser(User $user, Project $project): Response
    {
        return $user->id !== $project->user_id
            ? Response::allow()
            : Response::deny('Unauthorized');
    }
}
