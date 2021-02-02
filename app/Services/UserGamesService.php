<?php
declare(strict_types=1);


namespace App\Services;


use App\GameDictionaries\Search\Criteria\GameNameFilter;
use App\GameDictionaries\Search\CriteriaCollection;
use App\Models\Game;
use App\Models\User;
use App\Repositories\Games\Model\GameRepositoryInterface;
use App\Services\Exceptions\GameAlreadyExistException;

class UserGamesService
{
    private GameRepositoryInterface $gameRepository;

    public function __construct(GameRepositoryInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function attachGameToUser(User $user, Game $userGame): Game
    {
        // Create search criteria collection.
        $criteria = new CriteriaCollection();
        $criteria->push(new GameNameFilter($userGame->name));

        $dbGame = $this->gameRepository->findGame($criteria);

        if ($dbGame) {
            // Check if user doesn't have game already attached.
            if (!$user->games()->where('game_id', $dbGame->id)->exists()) {
                $user->games()->attach($dbGame->id);
                return $dbGame;
            } else {
                throw new GameAlreadyExistException();
            }
        }
        // Game not exists, let's create one.

        // Create a stub entity and make sync of data using Job.
        $game = new Game();
        $game->name = $userGame->name;
        $game->sync_status = Game::SYNC_GAME_DATA;
        $this->gameRepository->saveGame($game);

        $user->games()->attach($game->id);

        return $game;

    }
}
