<?php

namespace Tests\Repositories\Games;

use App\Collections\GameCollection;
use App\GameDictionaries\Search\Criteria\GameNameFilter;
use App\GameDictionaries\Search\CriteriaCollection;
use App\Models\Game;
use App\Repositories\Games\DatabaseGameRepository;
use Illuminate\Database\Eloquent\Builder;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

class DatabaseGameRepositoryTest extends TestCase
{


    public function testFindGame()
    {
        $model = \Mockery::mock(Game::class);

        $builderMock = \Mockery::mock(Builder::class);
        $builderMock->allows(['where' => $builderMock, 'select' => $builderMock, 'limit' => $builderMock]);
        $builderMock->allows()->get()->andReturn(new GameCollection([new Game()]));

        $model->allows()->newModelQuery()->andReturns($builderMock);


        $repository = new DatabaseGameRepository($model);

        $criteria = new CriteriaCollection();
        $criteria->push(new GameNameFilter('test'));

        $game = $repository->findGame($criteria);
        $this->assertInstanceOf(Game::class, $game);
    }
}
