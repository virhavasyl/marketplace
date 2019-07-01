<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\Tree;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

/**
 * Class Category
 * @package App\Models
 */
class Category extends Model
{
    use HasTranslations, Tree;

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title',
        'description'
    ];

    /**
     * Translatable model.
     *
     * @var string
     */
    public $translationModel = 'App\Models\Translation\CategoryTranslation';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id'
    ];

    /**
     * The attributes that belong to the category.
     *
     * @return BelongsToMany
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Attribute', 'attribute_has_category');
    }

    /**
     * Get all of the products for the category.
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany('App\Models\Product');
    }

    /**
     * Get hierarchical tree of all categories
     *
     * @param array $columns - Columns to select.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getTree($columns = ['id'])
    {
        return self::with('children')
            ->joinTranslate()
            ->select($columns)
            ->root()
            ->orderBy('title')
            ->get();
    }

    /**
     * Structure categories by levels.
     *
     * @param \Illuminate\Database\Eloquent\Collection $categories
     *
     * @return array
     */
    public static function structureByLevel(Collection $categories = null): array
    {
        if (!$categories) {
            $categories = self::getTree();
        }
        $cats = [];
        foreach ($categories as $category) {
            $cats['level1'][$category->id] = [
                'parent_id' => $category->parent_id,
                'title' => $category->title
            ];
            foreach ($category->children as $subcategory) {
                $cats['level2'][$subcategory->id] = [
                    'parent_id' => $subcategory->parent_id,
                    'title' => $subcategory->title
                ];
                foreach ($subcategory->children as $subsubcategory) {
                    $cats['level3'][$subsubcategory->id] = [
                        'parent_id' => $subsubcategory->parent_id,
                        'title' => $subsubcategory->title
                    ];
                }
            }
        }

        return $cats;
    }

    /**
     * Get all parent categories ids and id itself.
     *
     * @return array
     */
    public function getAllParentCategories(): array
    {
        $ids = [$this->id];
        if ($this->parentEntity) {
            $ids[] = $this->parentEntity->id;
            if ($this->parentEntity->parentEntity) {
                $ids[] =  $this->parentEntity->parentEntity->id;
            }
        }

        return $ids;
    }

    /**
     * Get all children categories ids and id itself.
     *
     * @return array
     */
    public function getAllChildrenCategories(): array
    {
        $ids = [$this->id];
        if ($this->children) {
            foreach ($this->children as $child) {
                $ids[] =  $child->id;
                if ($child->children) {
                    foreach ($child->children as $subChild) {
                        $ids[] =  $subChild->id;
                    }
                }
            }
        }

        return $ids;
    }

    /**
     * Get product count of current and children categories
     *
     * @return int
     */
    public function getProductCount(): int
    {
        $cat_ids = $this->getAllChildrenCategories();
        //Log::info($cat_ids);

        return Product::whereIn('category_id', $cat_ids)->count();
    }

    /**
     * Get attributes by category and all parent categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllAttributes()
    {
        $categoryIds = $this->getAllParentCategories();
        $attributes = Attribute::category($categoryIds)
          ->orderBy('type', 'desc')
          ->get();

        return $attributes;
    }
}
