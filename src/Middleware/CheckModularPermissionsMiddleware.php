<?php

namespace Paracha\Acl\Middleware;

use Closure;

class CheckModularPermissionsMiddleware
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
        if(!config('acl.modular')){
            return response(["error" => ["Request at " . $path . " not Recognized"]], 403);
        }
        if (!$permission) {
            $uri = $request->path();
            $path = explode('/', $uri);
            $path = array_map('strtolower', $path);
            $pathCount = count($path);
            // Preparing variables for request permissions authorization
            // /Note this would fail when a request doesn't follow laravel standardized requests 
            // like: api/users/permissions or users/invite would not pass
            if ($pathCount > 2) {
                # Model
                $module = $path[1];
                $model = $path[2];
            }else {
                return response(["error" => ["Request at " . $path . " not Recognized"]], 403);
            }
            if ($pathCount > 3) {
                # Update or Report
                $create = $path[2] == "create" ? true : false;
                $record = is_int($path[2]);
                $recordNumber = $record ? $path[2]: false;
                $report = $path[2] == "reports" ? true : false;
            }
            if ($pathCount > 4) {
                # View record or Report
                if ($report) {
                # code...
                    $reportName = $path[3];
                } elseif ($path[3] == "view") {
                # code...
                    $view = true;
                }
            }
            switch ($pathCount) {
                # simply api/
                case 1:
                break;
                # api/module
                case 2:
                break;

                case 3:
                # index: api/sales get - model.read
                # store: api/sales post - model.create
                if($request->isMethod('get')){
                    #Check model.read
                    $permission = $model . ".read";
                } elseif ($request->isMethod('post')){
                    #Check for model.create
                    $permission = $model . ".create";
                }
                break;
                case 4:
                # create: api/sales/create get - model.create
                # show: api/sales/1 get - model.read
                # update: api/sales/1 patch - model.update
                # delete: api/sales/1 delete - model.delete
                # report: api/sales/reports get - model.report
                if($create){
                    #Check for model.create
                    $permission = $model . ".create";
                } elseif ($record){
                    switch ($request->method()) {
                        case 'get':
                            #Check for model.read
                        $permission = $model . ".read";
                        break;

                        case 'patch':
                            #Check for model.update
                        $permission = $model . ".update";
                        break;

                        case 'delete':
                            #Check for model.delete
                        $permission = $model . ".delete";
                        break;

                        default:
                        return response(["error" => ["You are not authorized to make this request"]],401);
                        break;
                    }
                } elseif ($report) {
                    #Check for model.report
                    $permission = $model . ".report";
                }
                break;
                case 5:
                # specificreport: api/sales/reports/xyz get - model.report
                # Edit : api/sales/1/edit - model.update
                if($report){
                    #Check for model.report
                    $permission = $model . ".report";
                } elseif ($record){
                    #Check for model.update
                    $permission = $model . ".update";
                }
                break;

                default:
                # code...
                break;
            }
        }
        if ($permission && (!$request->user() || !$request->get('companyUser')->can($module . '.' . $permission))){
            $error = ['error' => ["You are not authorized to view this content!"]];
            return response($error, 401); 
            
        }

        return $next($request);
    }

}
