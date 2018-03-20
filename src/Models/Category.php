<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Categories.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrianFaust\Categories\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use NodeTrait, HasSlug;

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function categories(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @param $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function entries(string $class): MorphToMany
    {
        return $this->morphedByMany($class, 'categories', 'categories_relations');
    }

    /**
     * @return mixed
     */
    public static function tree(): array
    {
        return static::get()->toTree()->toArray();
    }

    public static function findByName(string $name)
    {
        return static::where('name', $name)->orWhere('slug', $name)->firstOrFail();
    }

    public static function findById(int $id)
    {
        return static::findOrFail($id);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
