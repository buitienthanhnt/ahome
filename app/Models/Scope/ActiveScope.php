<?php
namespace App\Models\Scope;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
class ActiveScope implements Scope {

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $request = app('Illuminate\Http\Request');
        $builder->where($model->getQualifiedActiveColumn(), '=', 1)
                ->orderBy(
                    $request->get('orderBy', 'created_at'),
                    $request->get('dir', 'desc')
                );
    }
}
