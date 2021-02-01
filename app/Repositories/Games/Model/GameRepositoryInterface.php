<?php


namespace App\Repositories\Games\Model;


use App\GameDictionaries\Search\CriteriaCollection;
use App\Models\Game;

interface GameRepositoryInterface
{
    public function findGame(CriteriaCollection $criteriaCollection): ?Game;

    public function saveGame(Game $game);

    public function updateOrCreateNewGame(Game $game);
}
