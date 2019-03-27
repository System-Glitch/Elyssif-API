<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Response;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\UserRepository  userRepository
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\SearchRequest
     * @return array
     */
    public function index(SearchRequest $request)
    {
        return $request->input('search') ?
            $this->userRepository->getPaginateWhere('name', 'LIKE', $request->input('search')):
            $this->userRepository->getPaginate();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \App\Models\User
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UserUpdateRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->only('name', 'email');

        if(empty($data['name'])) {
            unset($data['name']);
        }

        if(empty($data['email'])) {
            unset($data['email']);
        }

        $this->userRepository->update($user, $data);
        return new Response('', Response::HTTP_NO_CONTENT);
    }

}
