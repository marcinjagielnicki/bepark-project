<?php


namespace App\Repositories\Games;


use App\GameDictionaries\DictionaryClientInterface;
use App\GameDictionaries\Search\CriteriaCollection;
use App\Repositories\Games\Model\ExternalGameRepositoryInterface;
use App\Repositories\Games\Model\GameRepositoryInterface;
use Illuminate\Support\Collection;

class ExternalGameRepository implements ExternalGameRepositoryInterface
{
    /**
     * @var GameRepositoryInterface
     */
    private GameRepositoryInterface $gameRepository;

    public function __construct(GameRepositoryInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /** @var DictionaryClientInterface[] */
    protected array $sources = [];

    /**
     * Add external data source.
     * @param DictionaryClientInterface $dictionaryClient
     * @return $this
     */
    public function addSource(DictionaryClientInterface $dictionaryClient): self
    {
        $this->sources[] = $dictionaryClient;

        return $this;
    }

    public function findGames(CriteriaCollection $criteriaCollection): Collection
    {
        $games = collect();

        // Iterate over each source and collect all games found.
        foreach ($this->sources as $source) {
            foreach ($source->findGames($criteriaCollection) as $game) {
                $games->push($game);
            }
        }
        return $games;
    }
}
