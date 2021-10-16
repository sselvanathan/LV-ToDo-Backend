<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use JetBrains\PhpStorm\Pure;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return TodoResource::collection(Todo::select('id','name')->get());
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
        return new TodoResource(Todo::create($request->validated()));
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
     * @return Response
     */
    public function destroy(Todo $todo): Response
    {
        $todo->delete();

        return response()->noContent();
    }
}
