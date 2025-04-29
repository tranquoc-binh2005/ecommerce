<?php

namespace App\Http\Middleware;

use App\Enums\Config\Common;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\Loggable;
use Illuminate\Support\Str;
use App\Repositories\Permission\PermissionRepositories;
use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\Lang;

class checkApiPermission
{
    use Loggable;
    private PermissionRepositories $permissionRepositories;
    private $auth;

    public function __construct(
        PermissionRepositories $permissionRepositories
    )
    {
        $this->permissionRepositories = $permissionRepositories;
        $this->auth = auth(Common::API);
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    //    try {
    //        $action = $request->route()->getActionName();
    //        [$controller, $method] = explode('@', $action);
    //        $controllerName = str_replace('_controller', 's', Str::snake(class_basename($controller)));
    //        $permissionName = "{$controllerName}:{$method}";
    //        if(!$permissions = $this->permissionRepositories->findByName($permissionName)){
    //            return ApiResource::message(Lang::get('permission.not_found'), Response::HTTP_NOT_FOUND);
    //        }
    //        $requireValue = $permissions->value;

    //        /**
    //         * @var User $user
    //         */
    //        $user = $this->auth->user();
    //        if(!$user){
    //            return ApiResource::message(Lang::get('permission.unauthorized'), Response::HTTP_UNAUTHORIZED);
    //        }
    //        $user->load(['user_catalogues.permissions']);// load relation user_catalogues cua $user dong thoi load permissions cua user_catalogues
    //        if(!$user->user_catalogues){
    //            return ApiResource::message(Lang::get('permission.forbidden'), Response::HTTP_FORBIDDEN);
    //        }
    //        $hasPermission = false;
    //        foreach ($user->user_catalogues as $key => $value) {
    //            $permissions = $value->permissions->where('module', $controllerName)->pluck('value')->toArray();
    //            $totalPermission = array_reduce($permissions, function($carry, $item){ // carry la ket qua cong don
    //                return $carry | $item;
    //            }, 0);
    //            if(($totalPermission & $requireValue) === $requireValue){
    //                $hasPermission = true;
    //                break;
    //            }
    //        }
    //        print_r($permissions);
    //        if(!$hasPermission){
    //            return ApiResource::message(Lang::get('permission.forbidden'), Response::HTTP_FORBIDDEN);
    //        }

    //    } catch (\Exception $e) {
    //        return $this->handleLogException($e);
    //    }
        return $next($request);
    }
}
