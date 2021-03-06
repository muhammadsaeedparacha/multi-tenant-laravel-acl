<?php

namespace Paracha\Acl\Traits;

use Illuminate\Support\Str;
use Paracha\Acl\Models\Role;
use Paracha\Acl\Models\Company;

trait AclCompany
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function owner()
    {
        return $this->user();
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
        return $this->belongsToMany(config('acl.user','\App\User'), 'company_user')->withPivot('settings', 'authorized');
    }

    public function user()
    {
        return $this->belongsTo(config('acl.user','\App\User'));
    }
    
    public static function loggedInCompany($method){
        //Return the Company that the current user is visiting through the SubDomain
        switch ($method) {
            case 'subdomain':
            $request = request()->getHost();
            $subdomain = explode('.', $request);
            $subdomain = array_slice($subdomain, 0, count($subdomain) - 2 );
            $subdomain = $subdomain[0];
            $company = Company::where('subdomain', $subdomain)->first();
            return $company;
            break;

            case 'user':
            if (auth()->check()){
                return auth()->user()->company()->first();
            }
            break;
            default:
                # code...
            break;
        }
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
    
    public function setTenantConnection($dbConnection = ""){
        if($dbConnection == ""){
            $dbConnection = config('database.default');
        }
        switch ($dbConnection) {
            case 'mysql':
            \Config::set('database.connections.tenant.host', $this->db_host);
            \Config::set('database.connections.tenant.database', 'db_' . $this->id);
            \Config::set('database.connections.tenant.username', $this->id);
            \Config::set('database.connections.tenant.password', 'password');

            //Reconnect the original database connection
            \DB::purge('tenant');
            \DB::reconnect('tenant');
            break;
            
            default:
                # code...
            break;
        }
        
    }
    
    public function migrateTenant(){
        $mig = app()->make('migrator');
        $this->setTenantConnection();
        // fixit
        \Config::set('database.connections.tenant.username', config('database.connections.' . config('database.default') . '.username'));
        \Config::set('database.connections.tenant.password', config('database.connections.' . config('database.default') . '.password'));
        \DB::purge('tenant');
        \DB::reconnect('tenant');
        $mig->setConnection(config('acl.connections.tenant'));
        $mig->getRepository()->createRepository();
        // $path = base_path('database/migrations');
        // forEach (config('acl.tenantMigrations') as $tenantPath){
        $mig->run(config('acl.tenantMigrations'), ['step' => true]);
        // }
        $mig->setConnection(config('acl.connections.master'));
    }
    
    public static function dummy(Company $company = null){
        $company = $company != null ?: Company::find(1);
        
        $company->createTenantDatabase();
        $company->migrateTenant();
        $perm = config('acl.permission', \Paracha\Acl\Models\Permission::class)::where('resource','Accounting.Customer')->get();
        \Paracha\Acl\Models\CompanyUser::find(1)->permissions()->attach($perm);
        $perm = config('acl.permission', \Paracha\Acl\Models\Permission::class)::where('resource','Accounting.Sale')->get();
        \Paracha\Acl\Models\CompanyUser::find(1)->permissions()->attach($perm);
        $perm = config('acl.permission', \Paracha\Acl\Models\Permission::class)::where('resource','users')->get();
        \Paracha\Acl\Models\CompanyUser::find(1)->permissions()->attach($perm);
        $perm = config('acl.permission', \Paracha\Acl\Models\Permission::class)::where('resource','Accounting.Supplier')->get();
        \Paracha\Acl\Models\CompanyUser::find(1)->permissions()->attach($perm);
        $perm = config('acl.permission', \Paracha\Acl\Models\Permission::class)::where('resource','Accounting.ChartOfAccount')->get();
        \Paracha\Acl\Models\CompanyUser::find(1)->permissions()->attach($perm);
    }
}
