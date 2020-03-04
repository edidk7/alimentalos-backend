<?php

namespace App\Repositories;

use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class HandleBindingRepository {

    /**
     * Resolve binding parameters to create activity logs.
     *
     * @param Request $request
     */
    public static function resolve(Request $request) {
        $parameters = array_reverse(array_keys($request->route()->parameters()), false);
        if (count($parameters) > 0) {
            if (count($parameters) === 2) {
                static::createReferencedActionViaRequest($request, $parameters);
            } else {
                static::createSimpleReferencedActionViaRequest($request, $parameters);
            }
        } else {
            static::createSimpleActionViaRequest($request);
        }
    }

    /**
     * Create referenced action via request.
     *
     * @param Request $request
     * @param $parameters
     */
    public static function createReferencedActionViaRequest(Request $request, $parameters)
    {
        LoggerRepository::createReferencedAction(
            $request->user('api'),
            [
                'type' => 'success',
                'resource' => Route::currentRouteAction(),
                'parameters' => $request->route($parameters[0])->uuid ?? $parameters[0],
                'referenced' => $request->route($parameters[1])->uuid ?? $parameters[1]
            ]
        );
    }

    /**
     * Create simple referenced action via request.
     *
     * @param Request $request
     * @param $parameters
     */
    public static function createSimpleReferencedActionViaRequest(Request $request, $parameters)
    {
        LoggerRepository::createReferencedAction(
            $request->user('api'),
            [
                'type' => 'success',
                'resource' => Route::currentRouteAction(),
                'parameters' => $request->except('photo', 'password', 'password_confirmation', 'shape'),
                'referenced' => $request->route($parameters[0])->uuid ?? $parameters[0]
            ]
        );
    }

    /**
     * Create simple action via request.
     *
     * @param Request $request
     */
    public static function createSimpleActionViaRequest(Request $request)
    {
        LoggerRepository::createAction(
            $request->user('api'),
            'success',
            Route::currentRouteAction(),
            $request->except('photo', 'password', 'password_confirmation', 'shape')
        );
    }

    /**
     * Bind resource model instance.
     * (Uses prefix 'App')
     *
     * @param $class
     * @param $uuid
     * @return Builder|Model
     */
    public static function bindResourceModelInstance($class, $uuid)
    {
        return static::bindResourceModel($class)->where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Bind resource instance.
     * (Doesn't uses 'App' prefix)
     *
     * @param $resource
     * @param $uuid
     * @return mixed
     */
    public static function bindResourceInstance($resource, $uuid)
    {
        return static::bindResourceQuery($resource)->where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Detect resource type based on first request path parameter.
     *
     * @return mixed
     */
    public static function detectResourceType()
    {
        return explode('.', RequestFacade::route()->getName())[0];
    }

    /**
     * Bind resource model query.
     *
     * @param $class
     * @return Builder
     */
    public static function bindResourceModel($class) {
        return static::bindResourceQuery('App\\' . Str::camel(Str::singular($class)));
    }

    /**
     * Bind resource model class.
     *
     * @param $class
     * @return mixed
     */
    public static function bindResourceModelClass($class) {
        return static::bindResource('App\\' . Str::camel(Str::singular($class)));
    }

    /**
     * Bind resource.
     *
     * @param $resource
     * @return mixed
     */
    public static function bindResource($resource)
    {
        return resolve($resource);
    }

    /**
     * Bind resource query.
     *
     * @param $resource
     * @return mixed
     */
    public static function bindResourceQuery($resource)
    {
        return static::bindResource($resource)->query();
    }

    /**
     * Bind near resource model query using comma-separated coordinates.
     *
     * @param $resource
     * @param $coordinates
     * @return mixed
     */
    public static function bindNearModel($resource, $coordinates) {
        $model = static::bindResourceModel($resource);
        return $model->orderByDistance(
            static::bindResourceModelClass($resource)::DEFAULT_LOCATION_FIELD,
            LocationRepository::parsePointFromCoordinates($coordinates),
            'asc'
        );
    }
}
