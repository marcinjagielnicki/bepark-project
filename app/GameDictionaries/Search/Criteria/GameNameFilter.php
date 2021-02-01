<?php


namespace App\GameDictionaries\Search\Criteria;


use App\GameDictionaries\Search\CriteriaInterface;

class GameNameFilter implements CriteriaInterface
{
    public const GAME_NAME_SETTING = 'game_name';
    public const GAME_NAME_PRECISE_SEARCH_SETTING = 'game_precise_search';

    private string $name;

    private bool $preciseSearch;

    public function __construct(string $name, bool $preciseSearch = false)
    {
        $this->name = $name;
        $this->preciseSearch = $preciseSearch;
    }

    public function getSettings(): array
    {
        return [
            self::GAME_NAME_SETTING => $this->name,
            self::GAME_NAME_PRECISE_SEARCH_SETTING => $this->preciseSearch
        ];
    }
}
