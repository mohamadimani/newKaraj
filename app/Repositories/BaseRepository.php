<?php

namespace App\Repositories;

use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var Application
     */
    protected Application $app;

    /**
     * @param Application $app Application.
     *
     * @throws \Exception Exception.
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Configure the Model.
     *
     * @return string
     */
    abstract public function model(): string;

    /**
     * Make Model instance.
     *
     * @return Model
     * @throws \Exception Exception.
     */
    public function makeModel(): Model
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new \Exception(
                __(
                    'exceptions.class_must_beInstance_of',
                    ['model' => $this->model(), 'parent' => 'Illuminate\\Database\\Eloquent\\Model']
                )
            );
        }

        return $this->model = $model;
    }
}
