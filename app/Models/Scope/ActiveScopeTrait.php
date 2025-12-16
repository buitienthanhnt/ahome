<?php

namespace App\Models\Scope;

// https://p.softonsofa.com/laravel-how-to-define-and-use-eloquent-global-scopes/
use App\Models\PaperInterface;

trait ActiveScopeTrait
{
    public static function bootActiveScopeTrait(){
        static::addGlobalScope(new ActiveScope);
    }

    /**
     * Get the query builder without the scope applied.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function withDrafts()
    {
        return with(new static)->newQueryWithoutScope(new ActiveScope);
    }

    /**
     * Get the fully qualified "deleted at" column.
     *
     * @return string
     */
    public function getQualifiedActiveColumn()
    {
        return $this->qualifyColumn(PaperInterface::ATTR_ACTIVE);
    }
}
