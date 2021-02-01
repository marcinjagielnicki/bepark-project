<?php

namespace App\Jobs;

use App\GameDictionaries\Services\SyncGameDataService;
use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchGameData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Game
     */
    private Game $game;

    /**
     * Create a new job instance.
     *
     * @param Game $game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function handle(SyncGameDataService $syncGameDataService)
    {
        $syncGameDataService->syncGameFromExternal($this->game);
    }


}
