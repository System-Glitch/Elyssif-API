<?php
/*
 * Elyssif-API
 * Copyright (C) 2019 Jérémy LAMBERT (System-Glitch)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequestMandatory;
use App\Http\Requests\UserPasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Response;

class UserController extends Controller
{

    /**
     *
     * @var \App\Repositories\UserRepository
     */
    private $userRepository;

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
     * @param \App\Http\Requests\SearchRequestMandatory
     * @return array
     */
    public function index(SearchRequestMandatory $request)
    {
        return $this->userRepository->getPaginateWhere('name', 'LIKE', $request->input('search'));
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
        $data = $request->only('name', 'email', 'address');

        if(empty($data['name'])) {
            unset($data['name']);
        }

        if(empty($data['email'])) {
            unset($data['email']);
        }

        if(empty($data['address']) && ! $this->validatePendingFiles($user)) {
            return response()->json([
                'error' => __('validation.has_pending_files')
            ], Response::HTTP_FORBIDDEN);
        }

        $this->userRepository->update($user, $data);
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Validate paid pending files. Returns true if there is no paid files pending.
     * @param User $user
     * @return boolean
     */
    private function validatePendingFiles(User $user) {
        return ! $user->sentFiles()->where('price', '>', 0)->whereNull('deciphered_at')->select('id', 'address')->exists();
    }

    /**
     * Update the password of the currently authenticated user
     *
     * @param  \App\Http\Requests\UserPasswordRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(UserPasswordRequest $request)
    {
        $this->userRepository->update($request->user(), $request->only(['password']));
        return new Response('', Response::HTTP_NO_CONTENT);
    }

}
