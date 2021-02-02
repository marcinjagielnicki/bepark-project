<?php
declare(strict_types=1);

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

    public $tries = 10;

    public $maxExceptions = 5;

    private Game $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function handle(SyncGameDataService $syncGameDataService)
    {
        if ($this->game->sync_status === Game::SYNC_GAME_DATA) {
            $syncGameDataService->syncGameFromExternal($this->game);
        } else {
            \Log::info('Game already in sync', $this->game->toArray());
        }
    }


    public function retryUntil()
    {
        return now()->addMinutes(15);
    }
}
