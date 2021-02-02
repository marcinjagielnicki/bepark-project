<?php
declare(strict_types=1);


namespace App\Repositories\Games;


use App\GameDictionaries\Search\CriteriaCollection;
use App\Jobs\FetchGameData;
use App\Models\Game;
use App\Repositories\Games\Model\GameRepositoryInterface;

class GameRepository implements GameRepositoryInterface
{
    public function findGame(CriteriaCollection $criteriaCollection): ?Game
    {
        return null;
    }

    public function saveGame(Game $game)
    {
        if ($game->sync_status === Game::SYNC_GAME_DATA) {
            // Dispatch sync/async job for sync games data
            FetchGameData::dispatch($game);
        }
    }

    public function updateOrCreateNewGame(Game $game)
    {
        if ($game->sync_status === Game::SYNC_GAME_DATA) {
            FetchGameData::dispatch($game);
        }
    }
}
