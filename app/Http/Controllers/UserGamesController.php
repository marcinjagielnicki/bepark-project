<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UserGames\SubmitUserGameRequest;
use App\Models\Game;
use App\Services\Exceptions\GameAlreadyExistException;
use App\Services\UserGamesService;

class UserGamesController extends Controller
{
    public function displayForm()
    {
        return view('new-game');
    }

    public function submitForm(SubmitUserGameRequest $request, UserGamesService $userGamesService)
    {

        $userGame = new Game();
        $userGame->name = $request->get('name');

        try {
            $game = $userGamesService->attachGameToUser(\Auth::user(), $userGame);
            if ($game->sync_status == Game::SYNC_GAME_DATA) {
                return redirect()->route('dashboard')->with('success', __('Game successfully added to your library. We will try to fetch more information about your game in the meantime.'));
            } else {
                return redirect()->route('dashboard')->with('success', __('Game successfully added to your library.'));
            }
        } catch (GameAlreadyExistException $exception) {
            return redirect()->back()->with('errors', collect([__('You already own this game')]));
        }
    }
}
