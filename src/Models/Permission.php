<?php

namespace Yajra\Acl\Models;

use Illuminate\Support\Collection;
use Yajra\Acl\Traits\PermissionHasRole;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string resource
 * @property string name
 * @property string slug
 * @property bool system
 */
class Permission extends Model
{
    use PermissionHasRole;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';
    protected $connection = 'master';

    /**
     * @var array
     */
    protected $fillable = ['name', 'slug', 'resource'];

    // public function categories()
    // {
    //     return $this->morphedByMany(config('acl.user', App\User::class), 'categoriable');
    // }

    /**
     * Create a permissions for a resource.
     *
     * @param $resource
     * @param bool $system
     * @return \Illuminate\Support\Collection
     */
    // public static function createResource($resource, $manager = false)
    public static function createResource($resource)
    {
        $group        = ucfirst($resource);
        $slug         = strtolower($group);
        $permissions  = [
        [
        'slug'     => $slug . '.read',
        'resource' => $slug,
        'name'     => 'Read ' . $group,
        // 'manager'   => $manager,
        ],
        [
        'slug'     => $slug . '.create',
        'resource' => $slug,
        'name'     => 'Create ' . $group,
        // 'manager'   => $manager,
        ],
        [
        'slug'     => $slug . '.update',
        'resource' => $slug,
        'name'     => 'Update ' . $group,
        // 'manager'   => $manager,
        ],
        [
        'slug'     => $slug . '.delete',
        'resource' => $slug,
        'name'     => 'Delete ' . $group,
        // 'manager'   => $manager,
        ],
        [
        'slug'     => $slug . '.report',
        'resource' => $slug,
        'name'     => 'Report ' . $group,
        // 'manager'   => $manager,
        ],
        ];

        $collection = new Collection;
        foreach ($permissions as $permission) {
            try {
                $collection->push(static::create($permission));
            } catch (\Exception $e) {
                // permission already exists.
            }
        }

        return $collection;
    }
}
