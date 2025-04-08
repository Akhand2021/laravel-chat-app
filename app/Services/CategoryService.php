<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    /**
     * Get all categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategories()
    {
        return Category::where('is_active', true)->get();
    }

    /**
     * Get all categories with their parent categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategoriesWithParents()
    {
        return Category::with('parent')->where('is_active', true)->get();
    }

    /**
     * Get a category by ID.
     *
     * @param int $id
     * @return \App\Models\Category
     */
    public function getCategoryById($id)
    {
        return Category::findOrFail($id);
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return \App\Models\Category
     */
    public function createCategory($data)
    {
        $data['slug'] = $this->generateUniqueSlug($data['name']);
        
        return DB::transaction(function () use ($data) {
            return Category::create($data);
        });
    }

    /**
     * Update a category.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Category
     */
    public function updateCategory($id, $data)
    {
        $category = Category::findOrFail($id);
        
        // Only update slug if name has changed
        if (isset($data['name']) && $data['name'] !== $category->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $id);
        }
        
        return DB::transaction(function () use ($category, $data) {
            $category->update($data);
            return $category;
        });
    }

    /**
     * Delete a category.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        
        return DB::transaction(function () use ($category) {
            // Set parent_id to null for all child categories
            Category::where('parent_id', $category->id)->update(['parent_id' => null]);
            
            // Set category_id to null for all posts in this category
            $category->posts()->update(['category_id' => null]);
            
            return $category->delete();
        });
    }

    /**
     * Generate a unique slug for a category.
     *
     * @param string $name
     * @param int|null $excludeId
     * @return string
     */
    private function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    /**
     * Check if a slug exists.
     *
     * @param string $slug
     * @param int|null $excludeId
     * @return bool
     */
    private function slugExists($slug, $excludeId = null)
    {
        $query = Category::where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
} 