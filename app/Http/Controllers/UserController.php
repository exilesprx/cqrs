<?php

namespace CQRS\Http\Controllers;

use CQRS\DomainModels\User;
use CQRS\Repositories\UserRepository;
use CQRS\Events\CommandFactory;
use CQRS\Events\UserCreatedCommand;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $dispatcher;

    private $commandFactory;

    private $repository;

    public function __construct(Dispatcher $dispatcher, CommandFactory $commandFactory, UserRepository $userRepository)
    {
        $this->dispatcher = $dispatcher;

        $this->commandFactory = $commandFactory;

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
        $user = User::fromRequest($request);

        $command = $this->commandFactory->make(UserCreatedCommand::class, $user);

        $this->dispatcher->dispatch($command);

        return response()->json($user);
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
