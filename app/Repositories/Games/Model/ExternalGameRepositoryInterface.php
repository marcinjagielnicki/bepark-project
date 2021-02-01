<?php


namespace App\Repositories\Games\Model;


use App\GameDictionaries\Search\CriteriaCollection;
use App\Models\Game;
use Illuminate\Support\Collection;

interface ExternalGameRepositoryInterface
{
    public function findGames(CriteriaCollection $criteriaCollection): Collection;
}
