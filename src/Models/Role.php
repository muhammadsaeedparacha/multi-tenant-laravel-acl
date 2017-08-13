<?php

namespace Paracha\Acl\Models;

use Illuminate\Database\Eloquent\Model;
use Paracha\Acl\Traits\AclRole;

/**
 * @property \Paracha\Acl\Models\Permission permissions
 * @property bool system
 */
class Role extends Model
{
    use AclRole;
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
