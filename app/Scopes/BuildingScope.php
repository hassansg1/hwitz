<?php

namespace App\Scopes;

use App\Models\BuildingsUsers;
use App\Models\User;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BuildingScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $usersBuildings = BuildingsUsers::where('user_id', Auth::id())->pluck('building_id')->toArray();
        if (Auth::user()->isSuperAdmin()) {

        } else {
            $builder->whereIn('buildings.id', $usersBuildings);
            if (Session::get('admin_id')) {
                $usersBuildings = BuildingsUsers::where('user_id', Session::get('admin_id'))->pluck('building_id')->toArray();
                $builder->whereIn('buildings.id', $usersBuildings);
            }
        }
    }
}