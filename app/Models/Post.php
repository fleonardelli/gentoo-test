<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $topic
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Post extends Model
{
    /**
     * @var string
     */
    protected $table = 'posts';

    /**
     * @var array<string>
     */
    protected $fillable = [
        'topic',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var bool
     */
    public $timestamps = true;
}
