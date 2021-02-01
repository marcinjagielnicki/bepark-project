<?php

namespace App\Http\Controllers;

use App\GameDictionaries\Search\Criteria\GameNameFilter;
use App\GameDictionaries\Search\CriteriaCollection;
use App\Http\Requests\UserGames\SubmitUserGameRequest;
use App\Models\Game;
use App\Repositories\Games\Model\GameRepositoryInterface;

class UserGamesController extends Controller
{
    public function displayForm()
    {
        return view('new-game');
    }

    public function submitForm(SubmitUserGameRequest $request, GameRepositoryInterface $gameRepository)
    {
        // Create search criteria collection.
        $criteria = new CriteriaCollection();
        $criteria->push(new GameNameFilter($request->get('name')));

        $game = $gameRepository->findGame($criteria);

        $user = \Auth::user();
        // If game exists, attach to user.
        if ($game) {
            // Check if user doesn't have game already attached.
            if (!$user->games()->where('game_id', $game->id)->exists()) {
                $user->games()->attach($game->id);
                return redirect()->route('dashboard')->with('success', __('Game successfully added to your library'));
            } else {
                return redirect()->back()->with('errors', collect([__('You already own this game')]));
            }
        }

        // If not, create a stub entity and make sync of data using Job.
        $game = new Game();
        $game->name = $request->get('name');
        $game->sync_status = Game::SYNC_GAME_DATA;

        $gameRepository->saveGame($game);

        \Auth::user()->games()->attach($game->id);

        return redirect()->route('dashboard')->with('success', __('Game successfully added to your library. We will try to fetch more information about your game in the meantime.'));
    }
}
