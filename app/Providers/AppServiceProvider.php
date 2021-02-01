<?php

namespace App\Providers;

use App\GameDictionaries\Rawg\Client;
use App\GameDictionaries\Rawg\DictionaryClient;
use App\Models\Game;
use App\Repositories\Games\CacheGameRepositoryDecorator;
use App\Repositories\Games\DatabaseGameRepositoryDecorator;
use App\Repositories\Games\ExternalGameRepository;
use App\Repositories\Games\GameRepository;
use App\Repositories\Games\Model\ExternalGameRepositoryInterface;
use App\Repositories\Games\Model\GameRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->bind(GameRepositoryInterface::class, GameRepository::class);


        $this->app->bind(Client::class, function($app) {
            $guzzle = new \GuzzleHttp\Client([
                'base_uri' => config('rawg.api_endpoint')
            ]);

            $apiKey = config('rawg.api_key');

            return new Client($guzzle, $apiKey);
        });

        $this->app->bind(ExternalGameRepositoryInterface::class, function($app) {
            $repository = new ExternalGameRepository($app->make(GameRepositoryInterface::class));
            $repository->addSource($app->make(DictionaryClient::class));
            $repository->addSource($app->make(\App\GameDictionaries\Twitch\DictionaryClient::class));

            return $repository;
        });

        $this->app->extend(GameRepository::class, function(GameRepository $service, $app) {
            return new CacheGameRepositoryDecorator(new DatabaseGameRepositoryDecorator($app->make(Game::class), $service));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
