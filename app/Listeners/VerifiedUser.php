<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class VerifiedUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Verified  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        $user=$event->user;
        $role = config('roles.models.role')::where('name', '=', 'Utilisateur')->first();
        $oldRole = config('roles.models.role')::where('name', '=', 'Inscrit')->first();
        $user->detachRole($oldRole);
        $user->attachRole($role);
    }
}
