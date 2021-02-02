<?php
declare(strict_types=1);


namespace App\GameDictionaries\DTO;


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

    public function getName(): string
    {
        return $this->name;
    }

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
