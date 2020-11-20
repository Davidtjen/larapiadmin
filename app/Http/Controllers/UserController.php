<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\User;
use Auth;
use Gate;
use Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @OA\Get(path="/users",
     *      security={{"bearerAuth":{}}},
     *      tags={"Users"},
     *  @OA\Response(
     *      response=200,
     *      description="User Collection"
     *  ),
     *  @OA\Parameter(
     *      name="page",
     *      description="Pagination page",
     *      in="query",
     *      @OA\Schema(
     *          type="integer"
     *      ),
     *  ),
     * )
     */
    public function index()
    {
        Gate::authorize('view', "Users");

        $users = User::paginate();
        return UserResource::collection($users);
    }

    /**
     * @OA\Get(path="/users/{id}",
     *      security={{"bearerAuth":{}}},
     *      tags={"Users"},
     *  @OA\Response(
     *      response=200,
     *      description="User"
     *  ),
     *  @OA\Parameter(
     *      name="id",
     *      description="User ID",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      ),
     *  ),
     * )
     */
    public function show($id)
    {
        Gate::authorize('view', "Users");

        $user = User::find($id);

        return new UserResource($user);
    }

    /**
     * @OA\Post(
     *      path="/users",
     *      security={{"bearerAuth":{}}},
     *      tags={"Users"},
     *      @OA\Response(
     *          response=201,
     *          description="User Create"
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              ref="#/components/schemas/UserCreateRequest"
     *          )
     *      )
     *  )
     */
    public function store(UserCreateRequest $request)
    {
        Gate::authorize('edit', "Users");

        // No password because the admin will create user
        // and afterwards the user will be able to change it
        $user = User::create(
            $request->only('first_name', 'last_name', 'email', 'role_id')
            + ['password' => Hash::make(123)]
        );

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *      path="/users/{id}",
     *      security={{"bearerAuth":{}}},
     *      tags={"Users"},
     *      @OA\Response(
     *          response=202,
     *          description="User Update"
     *      ),
     *     @OA\Parameter(
     *      name="id",
     *      description="User ID",
     *      in="path",
     *      required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              ref="#/components/schemas/UserUpdateRequest"
     *          )
     *      )
     *  )
     */
    public function update(UserUpdateRequest $request, $id)
    {
        Gate::authorize('edit', "Users");

        $user = User::find($id);
        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *      path="/users/{id}",
     *      security={{"bearerAuth":{}}},
     *      tags={"Users"},
     *  @OA\Response(
     *      response=204,
     *      description="User Delete"
     *  ),
     *  @OA\Parameter(
     *      name="id",
     *      description="User ID",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      ),
     *  ),
     * )
     */
    public function destroy($id)
    {
        Gate::authorize('edit', "Users");

        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *      path="/user",
     *      security={{"bearerAuth":{}}},
     *      tags={"Profile"},
     *  @OA\Response(
     *      response=200,
     *      description="Authenticated User"
     *  )
     * )
     */
    public function user()
    {
        $user = Auth::user();
        return (new UserResource($user))->additional([
            'data' => [
                'permissions' => $user->permissions()
            ]
        ]);
    }

    /**
     * @OA\Put(
     *      path="/users/info",
     *      security={{"bearerAuth":{}}},
     *      tags={"Profile"},
     *      @OA\Response(
     *          response=202,
     *          description="User Info Update"
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              ref="#/components/schemas/UpdateInfoRequest"
     *          )
     *      )
     *  )
     */
    public function updateInfo(UpdateInfoRequest $request)
    {
        $user = Auth::user();
        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Put(
     *      path="/users/password",
     *      security={{"bearerAuth":{}}},
     *      tags={"Profile"},
     *      @OA\Response(
     *          response=202,
     *          description="User Password Update"
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              ref="#/components/schemas/UpdatePasswordRequest"
     *          )
     *      )
     *  )
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }
}