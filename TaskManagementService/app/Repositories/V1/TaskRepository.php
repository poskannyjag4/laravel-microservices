<?php

namespace App\Repositories\V1;

use App\Models\Task;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * @mixin Task
 */
class TaskRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    public function model()
    {
        return Task::class;
    }
}
