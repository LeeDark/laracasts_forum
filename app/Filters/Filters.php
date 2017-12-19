<?php

namespace App\Filters;

use function collect;
use Illuminate\Http\Request;
use function method_exists;

abstract class Filters
{
    protected $request, $builder;

    protected $filters = [];

    /**
     * ThreadFilters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

//        $this->getFilters()
//            ->filter(function ($filter) {
//                return method_exists($this, $filter);
//            })
//            ->each(function ($filter, $value) {
//                $this->$filter($value);
//            });

        return $this->builder;
    }

    public function getFilters()
    {
        return $this->request->only($this->filters);
//        return collect($this->request->only($this->filters))->flip();
    }
}