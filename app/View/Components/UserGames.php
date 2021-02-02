<?php
declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class UserGames extends Component
{

    public $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        //
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.user-games');
    }
}
