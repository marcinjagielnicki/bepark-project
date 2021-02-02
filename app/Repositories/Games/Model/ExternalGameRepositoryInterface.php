<?php
declare(strict_types=1);


namespace App\Repositories\Games\Model;


use App\GameDictionaries\Search\CriteriaCollection;
use Illuminate\Support\Collection;

interface ExternalGameRepositoryInterface
{
    public function findGames(CriteriaCollection $criteriaCollection): Collection;
}
