<?php

namespace App\Models\Universal;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;

/**
 * @SWG\Definition(
 *      definition="Company",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="registered_name",
 *          description="registered_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="logo",
 *          description="logo",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="acronym",
 *          description="acronym",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="map_location",
 *          description="map_location",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="address",
 *          description="address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="contact",
 *          description="contact",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="ntn",
 *          description="ntn",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="stn",
 *          description="stn",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="db_connection",
 *          description="db_connection",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="db_host",
 *          description="db_host",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="db_port",
 *          description="db_port",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="subdomain",
 *          description="subdomain",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="db_database",
 *          description="db_database",
 *          type="string"
 *      ),
 * )
 */
class Company extends Model
{
    use SoftDeletes;
    
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
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function owner()
    {
        return $this->belongsTo(\App\User::class);
    }
    
    public function isOwner()
    {
        return $this->user_id == Auth::User()->id;
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function users()
    {
        return $this->belongsToMany(\App\User::class, 'company_user')->withPivot('settings', 'authorized');
    }
    
    public static function loggedInCompany(){
        //Return the Company that the current user is visiting through the SubDomain
        $request = Request::getHost();
        $subdomain = explode('.', $request);
        $subdomain = array_slice($subdomain, 0, count($subdomain) - 2 );
        $subdomain = $subdomain[0];
        $company = Company::where('subdomain', $subdomain)->first();
        return $company;
    }
    
    public function createTenantDatabase(){
        // encrypt($company->id . 'password') replace 'passwrd' with this
        \DB::statement('CREATE DATABASE ' . $this->db_database);
        $query = 'grant all privileges on ' . $this->db_database . '.* to "' . $this->id . '"@"' . env('db_host') . '" identified by "' . 'password' . '";';
        \DB::statement($query);
        $query = 'grant all privileges on ' . env('DB_DATABASE') . '.users to "' . $this->id . '"@"' . env('db_host') . '" identified by "' . 'password' . '";';
        \DB::statement($query);
        $query = 'grant all privileges on ' . env('DB_DATABASE') . '.permissions to "' . $this->id . '"@"' . env('db_host') . '" identified by "' . 'password' . '";';
        \DB::statement($query);
    }
    
    public function setTenantConnection(){
        \Config::set('database.connections.tenant.host', $this->db_host);
        \Config::set('database.connections.tenant.database', 'db_' . $this->id);
        \Config::set('database.connections.tenant.username', $this->id);
        \Config::set('database.connections.tenant.password', 'password');
        
        //If you want to use query builder without having to specify the connection
        \DB::purge('tenant');
        \DB::reconnect('tenant');
    }
    
    public function migrateTenant(){
        $mig = app()->make('migrator');
        $this->setTenantConnection();
        \Config::set('database.connections.tenant.username', config('database.connections.master.username'));
        \Config::set('database.connections.tenant.password', config('database.connections.master.password'));
        \DB::purge('tenant');
        \DB::reconnect('tenant');
        
        $mig->setConnection('tenant');
        $mig->getRepository()->createRepository();
        $path = base_path('database/migrations');
        $mig->run($path);
        $mig->run($path . '/tenants');
        $mig->run($path . '/tenants/master');
        $mig->run($path . '/tenants/master/users');
        $mig->run($path . '/tenants/master/inventory');
        $mig->run($path . '/tenants/accounts');
        $mig->run($path . '/tenants/hrm');
    }
    
    public static function dummy(Company $company = null){
        $company = $company != null ?: Company::find(1);
        
        $company->createTenantDatabase();
        $company->migrateTenant();
    }
}