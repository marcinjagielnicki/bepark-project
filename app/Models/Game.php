<?php
declare(strict_types=1);

namespace App\Models;

use App\Collections\GameCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $image_url
 * @property int|null $rating
 * @property int|null $metacritic_rating
 * @property array|null $metadata
 * @property \Illuminate\Support\Carbon|null $released_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static GameCollection|static[] all($columns = ['*'])
 * @method static GameCollection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereMetacriticRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereReleasedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $sync_status
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereSyncStatus($value)
 */
class Game extends Model
{
    public const SYNC_GAME_DATA = 1;
    public const SYNC_GAME_DATA_IN_PROGRESS = 2;
    public const SYNC_GAME_DATA_COMPLETED = 3;
    public const SYNC_GAME_DATA_NOT_FOUND = 4;
    public const SYNC_GAME_DATA_ERROR = 5;


    use HasFactory;

    protected $casts = [
        'metadata' => 'array'
    ];

    protected $dates = [
        'released_at'
    ];

    protected $fillable = [
        'sync_status', 'name', 'description', 'image_url', 'rating', 'metacritic_rating', 'metadata'
    ];

    public function newCollection(array $models = [])
    {
        return new GameCollection($models);
    }
}
