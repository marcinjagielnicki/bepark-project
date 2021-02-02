<?php
declare(strict_types=1);


namespace App\GameDictionaries\DTO;


use App\Models\Game;
use Carbon\CarbonInterface;

class GameDTO
{
    protected string $name;
    protected ?string $description = null;
    /** @var GamePlatformDTO[] */
    protected array $platforms = [];
    protected ?int $rating = null;
    protected ?int $metacriticRating = null;
    protected ?CarbonInterface $releasedAt = null;
    protected ?string $imageUrl = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addPlatform(GamePlatformDTO $gamePlatformDTO)
    {
        $this->platforms[] = $gamePlatformDTO;
    }

    public function toModel(): Game
    {
        $model = new Game();
        $model->name = $this->name;
        $model->description = $this->description;
        $model->rating = $this->rating;
        $model->metacritic_rating = $this->metacriticRating;
        $model->released_at = $this->releasedAt;
        $model->image_url = $this->imageUrl;
        $meta = [
            'platforms' => []
        ];

        if (count($this->platforms)) {
            foreach ($this->platforms as $platform) {
                $meta['platforms'][] = $platform->toArray();
            }
        }

        $model->metadata = $meta;


        return $model;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): void
    {
        $this->rating = $rating;
    }

    public function getMetacriticRating(): ?int
    {
        return $this->metacriticRating;
    }

    public function setMetacriticRating(?int $metacriticRating): void
    {
        $this->metacriticRating = $metacriticRating;
    }

    public function getReleasedAt(): ?CarbonInterface
    {
        return $this->releasedAt;
    }

    public function setReleasedAt(?CarbonInterface $releasedAt): void
    {
        $this->releasedAt = $releasedAt;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
}
