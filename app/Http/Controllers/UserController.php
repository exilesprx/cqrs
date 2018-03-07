<?php

namespace CQRS\Http\Controllers;

use CQRS\Commands\CommandFactory;
use CQRS\Commands\CreateUser;
use CQRS\Commands\UpdateUserPassword;
use CQRS\Repositories\State\UserRepository;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $dispatcher;

    private $factory;

    private $repository;

    public function __construct(Dispatcher $dispatcher, CommandFactory $factory, UserRepository $userRepository)
    {
        $this->dispatcher = $dispatcher;

        $this->factory = $factory;

        $this->repository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $users = $this->repository->all();
        }
        catch(\Exception $exception)
        {
            $t = $exception->getMessage();
        }

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

            // TODO: Update to use mediator
            // TODO: Mediator to fire command based on data
            $command = $this->factory->make(
                CreateUser::class,
                $request->only(
                    [
                        'name',
                        'email',
                        'password'
                    ]
                )
            );

            $this->dispatcher->dispatch($command);
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
        try {
            $this->validate(
                $request,
                [
                    'name' => ['string'],
                    'password' => ['string']
                ]
            );

            if($request->exists('password')) {
                $command = $this->factory->make(
                    UpdateUserPassword::class,
                    [
                        'id' => $id,
                        'password' => $request->get('password'),
                    ]
                );
            }

            $this->dispatcher->dispatch($command);
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
