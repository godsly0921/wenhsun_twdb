<?php

namespace twdb\presenters;

class SinglePresenter
{
    /**
     * @var \CActiveRecord|\CActiveRecord[]|null
     */
    private static $categories;
    /**
     * @var \Single
     */
    private $entity;

    public function __construct(\Single $entity)
    {
        $this->entity = $entity;
    }

    public function status()
    {
        switch ($this->entity->store_status) {
            case 1:
                return '良好';
            case 2:
                return '輕度破損';
            case 3:
                return '嚴重破損';
        }
    }

    public function index_limit()
    {
        switch ($this->entity->index_limit) {
            case 0: return '不開放';
            case 1: return '開放';
            case 2: return '限制';
        }

        return null;
    }

    public function original_limit()
    {
        switch ($this->entity->original_limit) {
            case 0: return '不開放';
            case 1: return '開放';
            case 2: return '限閱';
            case 3: return '限印';
        }

        return null;
    }

    public function photo_limit()
    {
        switch ($this->entity->photo_limit) {
            case 0: return '不開放';
            case 1: return '開放';
            case 2: return '限文訊內部使用';
            case 3: return '供API使用';
        }

        return null;
    }

    public function category()
    {
        $categoryIds = explode(',', $this->entity->category_id);
        $categoryNames = [];
        foreach ($categoryIds as $categoryId) {
            $category = $this->getCategory($categoryId);
            if (!$category) {
                continue;
            } elseif ($category->parent) {
                $categoryNames[] = "{$category->parent->name}_{$category->name}";
            } else {
                $categoryNames[] = $category->name;
            }
        }

        return implode('、', $categoryNames);
    }

    public function date_taken()
    {
        $result = "";

        if ($this->entity->filming_date) {
            $result .= $this->entity->filming_date;
        }
        if ($this->entity->filming_date_text) {
            $result .= "-" . $this->entity->filming_date_text;
        }

        return $result;
    }

    public function __get($name)
    {
        if (method_exists($this, $name)) {
            return $this->{$name}();
        }

        return $this->entity->{$name};
    }

    private function getCategory($categoryId)
    {
        if (!self::$categories) {
            $categories = \Category::model()->with('parent')->findAll();
            self::$categories = [];
            foreach ($categories as $category) {
                self::$categories[$category->category_id] = $category;
            }
        }

        return self::$categories[$categoryId];
    }
}