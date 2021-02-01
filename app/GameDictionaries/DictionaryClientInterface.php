<?php


namespace App\GameDictionaries;


use App\GameDictionaries\Search\CriteriaCollection;
use Illuminate\Support\Collection;

interface DictionaryClientInterface
{
    public function findGames(?CriteriaCollection $criteriaCollection = null): Collection;
}
