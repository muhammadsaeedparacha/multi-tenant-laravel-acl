<?php

namespace Yajra\Acl\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Acl\Traits\RoleHasPermission;

/**
 * @property \Yajra\Acl\Models\Permission permissions
 * @property bool system
 */
class Role extends Model
{
    use RoleHasPermission;
    protected $connection = 'tenant';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Fillable fields.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description', 'system'];
}
