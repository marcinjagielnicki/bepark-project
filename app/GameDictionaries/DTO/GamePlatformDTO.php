<?php


namespace App\GameDictionaries\DTO;


use App\Models\Game;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;

class GamePlatformDTO implements Arrayable
{
    protected string $name;
    protected string $externalId;

    public function __construct(string $name, string $externalId)
    {
        $this->name = $name;
        $this->externalId = $externalId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->externalId
        ];
    }
}
