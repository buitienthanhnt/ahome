<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends Model implements PermissionInterface
{
    use HasFactory;

    protected $table = self::TABLE_NAME;
    protected $guarded = [];

    function rulePermission(): HasMany
    {
        return $this->hasMany(PermissionRules::class, "permission_id");
    }

    function allRules(): array
    {
        $rules = $this->rulePermission()->getResults();
        $rules = $rules->map(fn($rule) => str_replace(["\\", "/", "@"], ["-", "__", "_"], $rule->rule_value));
        return $rules->toArray();
    }
}
