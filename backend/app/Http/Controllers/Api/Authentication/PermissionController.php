<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\PermissionRequest;
use App\Http\Resources\Authentication\PermissionResource;
use App\Services\Authentication\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct(private PermissionService $permissions) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['module']);

        return response()->json([
            'data' => PermissionResource::collection($this->permissions->all($filters)),
        ]);
    }

    public function store(PermissionRequest $request): JsonResponse
    {
        $permission = $this->permissions->create($request->validated());

        return response()->json([
            'message' => 'Permission created.',
            'data'    => new PermissionResource($permission),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'data' => new PermissionResource($this->permissions->find($id)),
        ]);
    }

    public function update(PermissionRequest $request, int $id): JsonResponse
    {
        $permission = $this->permissions->update($id, $request->validated());

        return response()->json([
            'message' => 'Permission updated.',
            'data'    => new PermissionResource($permission),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->permissions->delete($id);

        return response()->json(['message' => 'Permission deleted.']);
    }
}