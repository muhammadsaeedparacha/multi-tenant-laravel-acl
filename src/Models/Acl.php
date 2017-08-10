<?php

namespace Paracha\Acl\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Paracha\Acl\Models\Permission permissions
 * @property bool system
 */
class Acl extends Model
{
    public static $runMigrations = false;
}
