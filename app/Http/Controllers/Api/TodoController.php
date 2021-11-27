<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response as HttpResponse;
use JetBrains\PhpStorm\Pure;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return TodoResource::collection(Todo::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param   StoreTodoRequest  $request
     *
     * @return TodoResource
     */
    public function store(StoreTodoRequest $request): TodoResource
    {
        $todo = auth()->user()->todos()->create($request->validated());

        return new TodoResource($todo);
    }

    /**
     * Display the specified resource.
     *
     * @param   Todo  $todo
     *
     * @return TodoResource
     */
    #[Pure] public function show(Todo $todo): TodoResource
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
