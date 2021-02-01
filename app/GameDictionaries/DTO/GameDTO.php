<?php


namespace App\GameDictionaries\DTO;


use App\Models\Game;
use Carbon\CarbonInterface;

class GameDTO
{
    protected string $name;
    protected ?string $description = null;
    /** @var GamePlatformDTO[]  */
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

        if(count($this->platforms)) {
            foreach ($this->platforms as $platform) {
                $meta['platforms'][] = $platform->toArray();
            }
        }

        $model->metadata = $meta;


        return $model;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int|null
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * @param int|null $rating
     */
    public function setRating(?int $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return int|null
     */
    public function getMetacriticRating(): ?int
    {
        return $this->metacriticRating;
    }

    /**
     * @param int|null $metacriticRating
     */
    public function setMetacriticRating(?int $metacriticRating): void
    {
        $this->metacriticRating = $metacriticRating;
    }

    /**
     * @return CarbonInterface|null
     */
    public function getReleasedAt(): ?CarbonInterface
    {
        return $this->releasedAt;
    }

    /**
     * @param CarbonInterface|null $releasedAt
     */
    public function setReleasedAt(?CarbonInterface $releasedAt): void
    {
        $this->releasedAt = $releasedAt;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param string|null $imageUrl
     */
    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
}
