<?php

namespace Paracha\Acl\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Paracha\Acl\Traits\AclCompany;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;

class Company extends Model
{
    use SoftDeletes, AclCompany;
    
    public $table = 'companies';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $dates = ['deleted_at'];
    
    public $fillable = [
    'user_id',
    'name',
    'registered_name',
    'logo',
    'acronym',
    'map_location',
    'address',
    'email',
    'contact',
    'ntn',
    'stn',
    'db_connection',
    'db_host',
    'db_port',
    'subdomain',
    'db_database',
    ];
    
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    'id' => 'integer',
    'user_id' => 'integer',
    'name' => 'string',
    'registered_name' => 'string',
    'logo' => 'string',
    'acronym' => 'string',
    'map_location' => 'string',
    'address' => 'string',
    'email' => 'string',
    'contact' => 'string',
    'ntn' => 'string',
    'stn' => 'string',
    'db_connection' => 'string',
    'db_host' => 'string',
    'db_port' => 'string',
    'subdomain' => 'string',
    'db_database' => 'string',
    ];
    
    /**
     * Validation rules
     *
     * @var array
     */
    
    public static $rules = [
    'name' => 'required',
    'email' => 'required|unique:companies',
    'subdomain' => 'required|unique:companies',
    'logo' => 'dimensions:min_width=100,min_height=100|image'
    ];
}