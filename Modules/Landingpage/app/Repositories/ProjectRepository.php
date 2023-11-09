<?php

namespace Modules\Landingpage\app\Repositories;

use Modules\Landingpage\app\Models\News;

class NewsRepository extends BaseRepository
{
    /**
     * Init model associate with this repository
     */
    protected function model()
    {
        return new News();
    }


    /**
     * Get project list by condition
     *
     * @var string $status
     * @var array $condition
     * @var string $order
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getListByCondition($condition, $perPage = 15)
    {
        $query = $this->model()->with("image")->get("original_url");
        return $query
            ->where($condition)
            ->paginate($perPage);
    }
}
