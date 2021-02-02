<?php
declare(strict_types=1);


namespace App\Repositories\Games;


use App\GameDictionaries\Search\CriteriaCollection;
use App\Models\Game;
use App\Repositories\Games\Model\GameRepositoryInterface;

class DatabaseGameRepositoryDecorator extends DatabaseGameRepository
{
    private GameRepositoryInterface $gameRepository;

    public function __construct(Game $model, GameRepositoryInterface $gameRepository)
    {
        parent::__construct($model);
        $this->gameRepository = $gameRepository;
    }

    public function findGame(CriteriaCollection $criteriaCollection): ?Game
    {
        $game = parent::findGame($criteriaCollection);

        if ($game) {
            return $game;
        }

        // Run underlying services (e.g. autocomplete from 3rd-parties).
        $decoratedGame = $this->gameRepository->findGame($criteriaCollection);

        if ($decoratedGame) {
            if (!$decoratedGame->exists) {
                $decoratedGame->save();
            }

            return $decoratedGame;
        }

        return null;
    }

    public function saveGame(Game $game)
    {
        parent::saveGame($game);

        // Run save on underlying services (like ElasticSearch).
        $this->gameRepository->saveGame($game);
    }

    public function updateOrCreateNewGame(Game $game)
    {
        parent::updateOrCreateNewGame($game);

        // Run save on underlying services (like ElasticSearch).
        $this->gameRepository->updateOrCreateNewGame($game);
    }
}
