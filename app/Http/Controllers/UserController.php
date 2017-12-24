<?php

namespace CQRS\Http\Controllers;

use CQRS\Events\UserCreatedCommand;
use CQRS\Repositories\State\UserRepository;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $dispatcher;

    private $command;

    private $repository;

    public function __construct(Dispatcher $dispatcher, UserCreatedCommand $command, UserRepository $userRepository)
    {
        $this->dispatcher = $dispatcher;

        $this->command = $command;

        $this->repository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->repository->all();

        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate(
                $request,
                [
                    'name' => ['required', 'string'],
                    'email' => ['required', 'string'],
                    'password' => ['required', 'string']
                ]
            );

            $this->command->handle(
                $request->get('name'),
                $request->get('email'),
                $request->get('password')
            );

            $this->dispatcher->dispatch($this->command);
        }
        catch (\Exception $exception)
        {
            return response()->json([
                'error' => $exception->getMessage()
            ]);
        }

        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
