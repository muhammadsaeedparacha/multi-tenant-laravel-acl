<?php

namespace Yajra\Acl\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Yajra\Acl\Models\Permission permissions
 * @property bool system
 */
class Acl extends Model
{
    public static $runMigrations = false;
}
