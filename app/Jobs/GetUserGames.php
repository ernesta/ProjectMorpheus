<?php

namespace Morpheus\Jobs;

use Morpheus\User;
use Morpheus\APIs\SteamGames;
use Morpheus\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class GetUserGames extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
	
	protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        iterator_to_array((new SteamGames())->getGames($this->user));
    }
}
