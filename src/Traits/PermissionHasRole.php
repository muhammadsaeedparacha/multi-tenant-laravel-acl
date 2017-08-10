<?php

namespace Yajra\Acl\Traits;

use Illuminate\Support\Str;
use Yajra\Acl\Models\Role;

trait PermissionHasRole
{
    /**
     * Get all of the posts that are assigned this tag.
     */
    public function roles()
    {
        return $this->morphedByMany(config('acl.role', 'App\Models\Universal\Permission'), 'permissionable');
    }

    /**
     * Get all of the videos that are assigned this tag.
     */
    public function companyusers()
    {
        return $this->morphedByMany(config('acl.companyUser', 'App\Models\Universal\CompanyUser'), 'permissionable', config()->get('database.connections.tenant.database') . '.permissionables');
    }

    /**
     * Attach a role to user using slug.
     *
     * @param $slug
     * @return bool
     */
    public function attachRoleBySlug($slug)
    {
        $role = Role::where('slug', $slug)->first();

        return $this->attachRole($role);
    }

    /**
     * Attach a role to user
     *
     * @param  Role $role
     * @return boolean
     */
    public function attachRole(Role $role)
    {
        return $this->assignRole($role->id);
    }

    /**
     * Assigns the given role to the user.
     *
     * @param  int $roleId
     * @return bool
     */
    public function assignRole($roleId = null)
    {
        $roles = $this->roles;

        if (! $roles->contains($roleId)) {
            return $this->roles()->attach($roleId);
        }

        return false;
    }

    /**
     * Query scope for user having the given roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $roles
     * @return mixed
     */
    public function scopeHavingRoles($query, array $roles)
    {
        return $query->whereExists(function ($query) use ($roles) {
            $query->selectRaw('1')
            ->from('role_user')
            ->whereRaw('role_user.user_id = users.id')
            ->whereIn('role_id', $roles);
        });
    }

    /**
     * Revokes the given role from the user using slug.
     *
     * @param  string $slug
     * @return bool
     */
    public function revokeRoleBySlug($slug)
    {
        $role = Role::where('slug', $slug)->first();

        return $this->roles()->detach($role);
    }

    /**
     * Revokes the given role from the user.
     *
     * @param  mixed $role
     * @return bool
     */
    public function revokeRole($role = "")
    {
        return $this->roles()->detach($role);
    }

    /**
     * Syncs the given role(s) with the user.
     *
     * @param  array $roles
     * @return bool
     */
    public function syncRoles(array $roles)
    {
        return $this->roles()->sync($roles);
    }

    /**
     * Revokes all roles from the user.
     *
     * @return bool
     */
    public function revokeAllRoles()
    {
        return $this->roles()->detach();
    }

    /**
     * Magic __call method to handle dynamic methods.
     *
     * @param  string $method
     * @param  array $arguments
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        // Handle isRoleSlug() methods
        if (Str::startsWith($method, 'is') and $method !== 'is') {
            $role = substr($method, 2);

            return $this->isRole($role);
        }

        // Handle canDoSomething() methods
        if (Str::startsWith($method, 'can') and $method !== 'can') {
            $permission = substr($method, 3);

            return $this->can($permission);
        }

        return parent::__call($method, $arguments);
    }

    /**
     * Checks if the user has the given role.
     *
     * @param  string $slug
     * @return bool
     */
    public function isRole($slug)
    {
        $slug = Str::lower($slug);

        foreach ($this->roles as $role) {
            if ($role->slug == $slug) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the given entity/model is owned by the user.
     *
     * @param \Illuminate\Database\Eloquent\Model $entity
     * @param string $relation
     * @return bool
     */
    public function owns($entity, $relation = 'user_id')
    {
        return $this->id === $entity->{$relation};
    }
}
