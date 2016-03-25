<?php

namespace Morpheus\Jobs;

use Morpheus\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class GameUpdateMetacritic extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
	
	protected $game;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\Morpheus\SteamGame $game) 
    {
        $this->game = $game;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new \Morpheus\APIs\MetacriticGames())->update($this->game);
    }
}
