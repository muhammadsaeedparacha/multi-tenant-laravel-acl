<?php

namespace Paracha\Acl\Middleware;

use Closure;

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
        $company = Company::loggedInCompany(config('acl.tenantIdentification'));
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
                    if (\DB::connection('tenant')->getDatabaseName() == "")
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
