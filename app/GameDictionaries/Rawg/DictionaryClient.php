<?php


namespace App\GameDictionaries\Rawg;


use App\GameDictionaries\DictionaryClientInterface;
use App\GameDictionaries\DTO\GameDTO;
use App\GameDictionaries\DTO\GamePlatformDTO;
use App\GameDictionaries\Search\Criteria\GameNameFilter;
use App\GameDictionaries\Search\CriteriaCollection;
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

    protected function createGameDTO(array $resultItem): GameDTO
    {
        $dto = new GameDTO($resultItem['name']);
        $dto->setImageUrl($resultItem['background_image'] ?? null);
        $dto->setMetacriticRating($resultItem['metacritic'] ?? null);
        $dto->setRating($resultItem['rating'] ?? null);
        $dto->setReleasedAt($resultItem['released'] ? Carbon::createFromFormat('Y-m-d', $resultItem['released']) : null);

        foreach ($resultItem['platforms'] as $platform) {
            $platformDto = new GamePlatformDTO($platform['platform']['name'], $platform['platform']['id']);
            $dto->addPlatform($platformDto);
        }

        return $dto;
    }

    protected function parseGameCriteria(CriteriaCollection $criteriaCollection): array
    {
        $params = [];

        foreach ($criteriaCollection->getCriteria() as $criteria) {
            if ($criteria instanceof GameNameFilter) {
                $params = $this->applyGameNameFilter($criteria, $params);
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
}
