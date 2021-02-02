<?php
declare(strict_types=1);


namespace App\Repositories\Games;


use App\GameDictionaries\DictionaryClientInterface;
use App\GameDictionaries\Search\CriteriaCollection;
use App\Repositories\Games\Model\ExternalGameRepositoryInterface;
use App\Repositories\Games\Model\GameRepositoryInterface;
use Illuminate\Support\Collection;

class ExternalGameRepository implements ExternalGameRepositoryInterface
{
    /** @var DictionaryClientInterface[] */
    protected array $sources = [];

    private GameRepositoryInterface $gameRepository;

    public function __construct(GameRepositoryInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

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
            try {
                foreach ($source->findGames($criteriaCollection) as $game) {
                    $games->push($game);
                }
            } catch (\Exception $exception) {
                \Log::error('External game source exception');
            }

        }
        return $games;
    }
}
