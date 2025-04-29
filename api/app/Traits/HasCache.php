<?php  
namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cache;

trait HasCache {

    protected $cacheKeyPrefix = '';
    protected $cacheTTL = 3600;

    protected function getRandomTTL(): int {
        return rand(3600, 4000);
    }

    protected function setCacheTTL(?int $ttl = null): self {
        $this->cacheTTL = $ttl ?? $this->getRandomTTL();
        return $this;
    }

    protected function cacheSingleRecord(): self {
        $cacheKey = $this->getSingleRecordCacheKey();
        Cache::put($cacheKey, $this->model, $this->cacheTTL);
        return $this;
    }

    protected function getSingleRecordCacheKey(): string{
        $id = $this->model ? $this->model->getKey() : request()->route(substr($this->cacheKeyPrefix, 0, -1));
        return "{$this->cacheKeyPrefix}:single:{$id}";
    }

    protected function clearSingleRecordCache(): self{
        $cacheKey = $this->getCacheKeyById($this->model->id);
        Cache::forget($cacheKey);
        return $this;
    }

    protected function getCacheKeyById(int $id = 0){
        return "{$this->cacheKeyPrefix}:single:{$id}";
    }

    protected function getPaginationCacheKey(array $requestParams = []){
        krsort($requestParams);
        $hash = md5(serialize($requestParams));
        return "{$this->cacheKeyPrefix}:collection:{$hash}";
    }

    protected function clearCollectionRecordCache(): self{
        Cache::tags(["{$this->cacheKeyPrefix}:collection"])->flush();
        return $this;
    }

    protected function clearBulkDeleteCache(array $ids = []): self {
        if(count($ids)){
            foreach($ids as $id){
                $cacheKey = $this->getCacheKeyById($id);
                Cache::forget($cacheKey);
            }
        }
        return $this;
    }
}
