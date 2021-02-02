<?php
declare(strict_types=1);


namespace App\Repositories\Games;


use App\GameDictionaries\Search\CriteriaCollection;
use App\Models\Game;
use App\Repositories\Games\Model\GameRepositoryInterface;

class CacheGameRepositoryDecorator implements GameRepositoryInterface
{
    private GameRepositoryInterface $gameRepository;

    public function __construct(GameRepositoryInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function findGame(CriteriaCollection $criteriaCollection): ?Game
    {
        $cacheKey = sprintf('findGame-%s', $this->serializeCriteria($criteriaCollection));
        $ttl = config('game_repository.cache.ttl', 60 * 60 * 24);

        return cache()->tags(['findGame'])->remember($cacheKey, $ttl, fn() => $this->gameRepository->findGame($criteriaCollection));
    }

    protected function serializeCriteria(CriteriaCollection $criteriaCollection): string
    {
        $key = '';

        foreach ($criteriaCollection->getCriteria() as $criteria) {
            $key .= sprintf(',%s', json_encode($criteria->getSettings()));
        }
        return $key;
    }

    public function saveGame(Game $game)
    {
        $this->gameRepository->saveGame($game);
        cache()->tags(['findGame']);
    }

    public function updateOrCreateNewGame(Game $game)
    {
        $this->gameRepository->updateOrCreateNewGame($game);
        cache()->tags(['findGame']);
    }
}
