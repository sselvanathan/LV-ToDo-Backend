<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use \Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource based by userId.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $userId = auth()->id();
        $todos = Todo::query()
            ->select(
                [
                    'id',
                    'user_id',
                    'name'
                ])
            ->where('user_id', $userId)
            ->get();

        return TodoResource::collection($todos);
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @param Request $request
     * @return TodoResource
     */
    public function store(Request $request): TodoResource
    {
        $todo = new Todo();
        $todo->name = $request->input('name');
        $todo->user_id = Auth::id();
        $todo->save();


        return new TodoResource($todo);
    }

    /**
     * Display the specified resource.
     *
     * @param   Todo  $todo
     *
     * @return TodoResource
     */
    public function show(Todo $todo): TodoResource
    {
        if ($todo->user_id !== auth()->id()){
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return new TodoResource($todo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   StoreTodoRequest  $request
     * @param   Todo              $todo
     *
     * @return TodoResource
     */
    public function update(StoreTodoRequest $request, Todo $todo): TodoResource
    {
        $todo->update($request->validated());

        return new TodoResource($todo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   Todo  $todo
     *
     * @return HttpResponse
     */
    public function destroy(Todo $todo): HttpResponse
    {
        $todo->delete();

        return response()->noContent();
    }
}
