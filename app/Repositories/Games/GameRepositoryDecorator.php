<?php
declare(strict_types=1);


namespace App\Repositories\Games;


use App\GameDictionaries\Search\CriteriaCollection;
use App\Models\Game;
use App\Repositories\Games\Model\GameRepositoryInterface;

class GameRepositoryDecorator implements GameRepositoryInterface
{
    private GameRepositoryInterface $gameRepository;

    public function __construct(GameRepositoryInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function findGame(CriteriaCollection $criteriaCollection): ?Game
    {
        return $this->gameRepository->findGame($criteriaCollection);
    }

    public function saveGame(Game $game)
    {
        $this->gameRepository->saveGame($game);
    }

    public function updateOrCreateNewGame(Game $game)
    {
        $this->gameRepository->updateOrCreateNewGame($game);
    }
}
