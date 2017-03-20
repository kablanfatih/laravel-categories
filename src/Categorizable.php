<?php

/*
 * This file is part of Laravel Categorizable.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

/*
 * This file is part of Laravel Categorizable.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrianFaust\Categorizable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Categorizable
{
    /**
     * @return mixed
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(config('categorizable.models.category'), 'categorizable', 'categories_relations');
    }

    /**
     * @return mixed
     */
    public function categoriesList(): aray
    {
        return $this->categories()
                    ->lists('name', 'id')
                    ->toArray();
    }

    /**
     * @param $categories
     */
    public function categorize($categories): void
    {
        foreach ($categories as $category) {
            $this->addCategory($category);
        }
    }

    /**
     * @param $categories
     */
    public function uncategorize($categories): void
    {
        foreach ($categories as $category) {
            $this->removeCategory($category);
        }
    }

    /**
     * @param $categories
     */
    public function recategorize($categories): void
    {
        $this->categories()->sync([]);

        $this->categorize($categories);
    }

    /**
     * @param Model $category
     */
    public function addCategory(Model $category): void
    {
        if (!$this->categories->contains($category->getKey())) {
            $this->categories()->attach($category);
        }
    }

    /**
     * @param Model $category
     */
    public function removeCategory(Model $category): void
    {
        $this->categories()->detach($category);
    }
}
