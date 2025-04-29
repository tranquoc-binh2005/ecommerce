<?php   
namespace App\Services\Interfaces;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

interface BaseServiceInterface {
    public function save(Request $request);
    public function show(int $id = 0): Model;
    public function destroy(int $id): bool;
    public function bulkDelete(Request $request): bool;
    public function attachOrDetach(Request $request, string $action = ''): bool | null;
}