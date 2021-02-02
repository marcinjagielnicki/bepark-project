<?php
declare(strict_types=1);


namespace App\GameDictionaries\Services;


use App\GameDictionaries\DTO\GameDTO;
use App\GameDictionaries\Search\Criteria\GameNameFilter;
use App\GameDictionaries\Search\CriteriaCollection;
use App\Models\Game;
use App\Repositories\Games\DatabaseGameRepository;
use App\Repositories\Games\Model\ExternalGameRepositoryInterface;
use App\Repositories\Games\Model\GameRepositoryInterface;
use Illuminate\Support\Collection;

class SyncGameDataService
{

    private GameRepositoryInterface $gameRepository;

    private ExternalGameRepositoryInterface $externalGameRepository;

    private DatabaseGameRepository $databaseGameRepository;


    public function __construct(GameRepositoryInterface $gameRepository, DatabaseGameRepository $databaseGameRepository, ExternalGameRepositoryInterface $externalGameRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->externalGameRepository = $externalGameRepository;
        $this->databaseGameRepository = $databaseGameRepository;
    }

    public function syncGameFromExternal(Game $game)
    {
        $game->update(['sync_status' => Game::SYNC_GAME_DATA_IN_PROGRESS]);

        $criteria = new CriteriaCollection();
        $criteria->push(new GameNameFilter($game->name));

        // Run external game repository. We are trying to fetch as many games as possible on single run.
        $games = $this->externalGameRepository->findGames($criteria);

        $this->mergeGameData($games);

        if (!$this->checkGameFound($games, $game)) {
            $game->sync_status = Game::SYNC_GAME_DATA_NOT_FOUND;
            $this->gameRepository->saveGame($game);
        }
    }

    protected function mergeGameData(Collection $games)
    {
        /** @var GameDTO $game */
        foreach ($games as $game) {
            $model = $game->toModel();
            $model->sync_status = Game::SYNC_GAME_DATA_COMPLETED;

            $criteria = new CriteriaCollection();
            $criteria->push(new GameNameFilter($model->name, true));

            $dbGame = $this->databaseGameRepository->findGame($criteria);
            if ($dbGame) {
                $dbGame->fill($this->rejectEmptyProperties($model->toArray()));
                $this->gameRepository->saveGame($dbGame);
            } else {
                $this->gameRepository->saveGame($model);
            }
        }
    }

    protected function rejectEmptyProperties(array $modelData): array
    {
        return collect($modelData)->reject(function ($value) {
            if (is_array($value) && empty($value['platforms'] ?? [])) {
                return true;
            }
            return empty($value);
        })->toArray();
    }

    protected function checkGameFound(Collection $collection, Game $game): bool
    {
        /** @var GameDTO $gameDTO */
        foreach ($collection as $gameDTO) {
            if ($gameDTO->getName() === $game->name) {
                return true;
            }
        }

        return false;
    }
}
