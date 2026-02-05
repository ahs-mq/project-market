<?php

namespace App\Events;

use App\Models\project;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project; // Just pass the whole model object

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function broadcastOn(): array
    {
        return [new Channel('projects')];
    }

    // Add this method to define what the React side sees
    public function broadcastWith(): array
    {
        return [
            'id' => $this->project->id,
            'status' => $this->project->status,
        ];
    }
}
