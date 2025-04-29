<?php
namespace App\Services\Impl\Permission;

use App\Traits\Loggable;
use App\Services\Interfaces\Permission\PermissionServiceInterface;
use App\Services\Impl\BaseService;
use App\Repositories\Permission\PermissionRepositories;
use Illuminate\Http\Request;
class PermissionService extends BaseService implements PermissionServiceInterface{
    use Loggable;

    private $repositories;
    private const CACHE_KEY_PREFIX = 'permissions';

    public function __construct(
        PermissionRepositories $repositories
    ){
        $this->repositories = $repositories;
        $this->cacheKeyPrefix = self::CACHE_KEY_PREFIX;
        parent::__construct($repositories);
    }
    protected function prepareModelData(Request $request): self
    {
        return $this->initializeBasicData( $request);
    }

    protected function initializeBasicData(Request $request): self{
        $fillAble = $this->repositories->getFillAble();
        $this->modelData = $request->only($fillAble);
        return $this;
    }
}
