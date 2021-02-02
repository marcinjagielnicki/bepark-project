<?php
declare(strict_types=1);


namespace App\GameDictionaries\Search;


interface CriteriaInterface
{
    public function getSettings(): array;
}
