<?php
declare(strict_types=1);


namespace App\Repositories\Games;


use App\GameDictionaries\Search\Criteria\GameNameFilter;
use App\GameDictionaries\Search\CriteriaCollection;
use App\Models\Game;
use App\Repositories\Games\Model\GameRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class DatabaseGameRepository implements GameRepositoryInterface
{

    private Game $model;

    public function __construct(Game $model)
    {
        $this->model = $model;
    }

    public function findGame(CriteriaCollection $criteriaCollection): ?Game
    {
        $query = $this->model->newModelQuery();

        $query->select();
        $query = $this->applyCriteria($criteriaCollection, $query);

        $query->limit(1);

        return $query->get()->first();
    }

    protected function applyCriteria(CriteriaCollection $criteriaCollection, Builder $builder): Builder
    {
        // Transform criteria to eloquent way. Can be moved to separated services when added new filters.
        foreach ($criteriaCollection->getCriteria() as $criteria) {
            if ($criteria instanceof GameNameFilter) {
                $settings = $criteria->getSettings();
                if ($settings[GameNameFilter::GAME_NAME_PRECISE_SEARCH_SETTING]) {
                    $builder->where('name', $settings[GameNameFilter::GAME_NAME_SETTING]);
                } else {
                    $builder->where('name', 'LIKE', $settings[GameNameFilter::GAME_NAME_SETTING] . '%');
                }
            }
        }

        return $builder;
    }

    public function saveGame(Game $game)
    {
        $game->save();
    }

    public function updateOrCreateNewGame(Game $game)
    {
        Game::updateOrCreate(['name' => $game->name], $game->toArray());
    }
}
