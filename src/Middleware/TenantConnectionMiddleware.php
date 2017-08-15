<?php

namespace Paracha\Acl\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Paracha\Acl\Models\Company;
use Illuminate\Support\Facades\Route;
use Paracha\Acl\Models\CompanyUser;

class TenantConnectionMiddleware
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request $request
    * @param  \Closure $next
    * @param  string $permission
    * @return mixed
    */
    public function handle($request, Closure $next, $permission = null)
    {
        $tenantIdentification = config('acl.tenantIdentification');
        $company = Company::loggedInCompany($tenantIdentification);
        if($company)
        {
            $companyUser = CompanyUser::where('company_id', $company->id)->where('user_id', $request->user()->id)->first();
            // $companyUser = $company->users()->where('users.id', '=', Auth::guard('api')->user()->id)->where('confirmed',1)->first();
            if(!$request->user()->confirmed)
            {
                $error = ['error' => ["Please Confirm your email"]];
                return response($error, 403);
            }
            if($companyUser)
            {
                if($companyUser->authorized){
                    $company->setTenantConnection();
                    if (config('database.connections.tenant.database') == "")
                    {
                        $error = ['error' => ["Database Error"]];
                        return response($error, 422);
                    }
                    $request->attributes->add(['loggedInCompany' => $company]);
                    $request->attributes->add(['companyUser' => $companyUser]);
                }else {
                    $error = ['error' => ["Your access has been revoked"]];
                    return response($error, 403);
                }
            } else{
                $error = ['error' => ["You do not have access to this Company"]];
                return response($error, 403);
            }
        } elseif($request->path() != 'login')
        {
            $error = ['error' => ["Company does not exist"]];
            return response($error, 422);
        }
        return $next($request);
    }

}
