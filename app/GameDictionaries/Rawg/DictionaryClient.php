<?php
declare(strict_types=1);


namespace App\GameDictionaries\Rawg;


use App\GameDictionaries\DictionaryClientInterface;
use App\GameDictionaries\DTO\GameDTO;
use App\GameDictionaries\DTO\GamePlatformDTO;
use App\GameDictionaries\Search\Criteria\GameNameFilter;
use App\GameDictionaries\Search\CriteriaCollection;
use App\GameDictionaries\Search\Exceptions\CriteriaNotSupportedException;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DictionaryClient implements DictionaryClientInterface
{

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param CriteriaCollection|null $criteriaCollection
     * @return Collection[GameDTO]
     * @throws Exceptions\ClientException
     * @throws Exceptions\ClientResponseException
     */
    public function findGames(?CriteriaCollection $criteriaCollection = null): Collection
    {
        if ($criteriaCollection) {
            $params = $this->parseGameCriteria($criteriaCollection);
        }

        $response = $this->client->makeRequest('games', $params ?? []);
        $games = [];
        foreach ($response['results'] as $resultGame) {
            $games[] = $this->createGameDTO($resultGame);
        }
        return collect($games);

    }

    protected function parseGameCriteria(CriteriaCollection $criteriaCollection): array
    {
        $params = [];

        foreach ($criteriaCollection->getCriteria() as $criteria) {
            if ($criteria instanceof GameNameFilter) {
                $params = $this->applyGameNameFilter($criteria, $params);
            } else {
                throw new CriteriaNotSupportedException($criteria);
            }
        }

        return $params;
    }

    protected function applyGameNameFilter(GameNameFilter $filter, array $params): array
    {
        $settings = $filter->getSettings();
        $params['search'] = $settings[GameNameFilter::GAME_NAME_SETTING];
        if ($settings[GameNameFilter::GAME_NAME_PRECISE_SEARCH_SETTING]) {
            $params['search_precise'] = true;
        }

        return $params;
    }

    protected function createGameDTO(array $resultItem): GameDTO
    {
        $dto = new GameDTO($resultItem['name']);
        $dto->setImageUrl($resultItem['background_image'] ?? null);
        $dto->setMetacriticRating(isset($resultItem['metacritic']) ? (int)round($resultItem['metacritic']) : null);
        $dto->setRating(isset($resultItem['rating']) ? (int)round($resultItem['rating']) : null);
        $dto->setReleasedAt($resultItem['released'] ? Carbon::createFromFormat('Y-m-d', $resultItem['released']) : null);

        if (isset($resultItem['platforms']) && is_array($resultItem['platforms'])) {
            foreach ($resultItem['platforms'] as $platform) {
                $platformDto = new GamePlatformDTO($platform['platform']['name'], (string)$platform['platform']['id']);
                $dto->addPlatform($platformDto);
            }
        }


        return $dto;
    }
}
