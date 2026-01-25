<?php

namespace App\Policies;

use App\Models\User;
use App\Models\project;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{

    public function modify(User $user, project $project): Response
    {
        return $user->id === $project->user_id
            ? Response::allow()
            : Response::deny('Unauthorized');
    }

    public function notSameUser(User $user, Project $project): Response
    {
        return $user->id !== $project->user_id
            ? Response::allow()
            : Response::deny('Unauthorized');
    }
}
