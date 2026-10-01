<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\RoleRequest;
use App\Http\Resources\Authentication\RoleResource;
use App\Services\Authentication\RoleService;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function __construct(private RoleService $roles) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => RoleResource::collection($this->roles->all()),
        ]);
    }

    public function store(RoleRequest $request): JsonResponse
    {
        $role = $this->roles->create($request->validated());

        return response()->json([
            'message' => 'Role created.',
            'data'    => new RoleResource($role),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'data' => new RoleResource($this->roles->find($id)),
        ]);
    }

    public function update(RoleRequest $request, int $id): JsonResponse
    {
        $role = $this->roles->update($id, $request->validated());

        return response()->json([
            'message' => 'Role updated.',
            'data'    => new RoleResource($role),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->roles->delete($id);

        return response()->json(['message' => 'Role deleted.']);
    }

    public function syncPermissions(RoleRequest $request, int $id): JsonResponse
    {
        $role = $this->roles->syncPermissions($id, $request->input('permissions', []));

        return response()->json([
            'message' => 'Permissions synced.',
            'data'    => new RoleResource($role),
        ]);
    }
}