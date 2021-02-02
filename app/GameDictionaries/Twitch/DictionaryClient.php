<?php
declare(strict_types=1);


namespace App\GameDictionaries\Twitch;


use App\GameDictionaries\DictionaryClientInterface;
use App\GameDictionaries\DTO\GameDTO;
use App\GameDictionaries\Search\Criteria\GameNameFilter;
use App\GameDictionaries\Search\CriteriaCollection;
use Illuminate\Support\Collection;
use MarcReichel\IGDBLaravel\Builder;
use MarcReichel\IGDBLaravel\Exceptions\AuthenticationException;
use MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException;
use MarcReichel\IGDBLaravel\Models\Game;

class DictionaryClient implements DictionaryClientInterface
{

    /**
     * @param CriteriaCollection|null $criteriaCollection
     * @return Collection
     * @throws AuthenticationException
     * @throws MissingEndpointException
     */
    public function findGames(?CriteriaCollection $criteriaCollection = null): Collection
    {
        $gameObject = new Builder('games');
        if ($criteriaCollection) {
            $this->applyGameCriteria($criteriaCollection, $gameObject);
        }

        $igdbGames = $gameObject->with(['cover' => ['url', 'image_id']])->get();
        $games = [];
        /** @var Game $resultGame */
        foreach ($igdbGames as $resultGame) {
            $games[] = $this->createGameDTO($resultGame);
        }
        return collect($games);

    }

    protected function applyGameCriteria(CriteriaCollection $criteriaCollection, Builder $builder): Builder
    {

        foreach ($criteriaCollection->getCriteria() as $criteria) {
            if ($criteria instanceof GameNameFilter) {
                $builder = $this->applyGameNameFilter($criteria, $builder);
            }
        }

        return $builder;
    }

    protected function applyGameNameFilter(GameNameFilter $filter, Builder $builder): Builder
    {
        $settings = $filter->getSettings();
        $builder->search($settings[GameNameFilter::GAME_NAME_SETTING]);

        return $builder;
    }

    protected function createGameDTO($resultItem): GameDTO
    {
        $dto = new GameDTO($resultItem->name);
        $dto->setImageUrl($resultItem->cover->url ?? null);
        $dto->setDescription($resultItem->summary ?? null);

        return $dto;
    }
}
