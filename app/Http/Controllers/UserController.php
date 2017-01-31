<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class UserController extends Controller
{
    protected $user;
    function __construct(User $user) {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->user->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validate($request, [
            'identificationNumber' => 'unique:users,identification_number|numeric',
            'phone' => 'numeric',
        ]);

        $params = [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'identification_number' => $request->identificationNumber,
            'email'     => $request->email,
            'phone'       => $request->phone,
            'photo'     => $request->photo,
            'address'   => $request->address,
            'role'      => $request->role,
            'username'  => $request->has('password') ? $request->username : null,
            'password'  => $request->has('password') ? $request->password : null,
        ];

        $this->user->create($params);
        
        return view('backoffice.main');
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
        return response()->json($this->user->find($id));
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
        $validator = $this->validate($request, [
            'identificationNumber' => 'numeric',
            'phone' => 'numeric',
        ]);

        $params = [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'identification_number' => $request->identificationNumber,
            'email'     => $request->email,
            'phone'       => $request->phone,
            'photo'     => $request->photo,
            'address'   => $request->address,
            'role'      => $request->role,
            'username'  => $request->has('password') ? $request->username : null,
            'password'  => $request->has('password') ? $request->password : null,
        ];

        $user = $this->user->find($id);

        if(is_null($user)) {
            return redirect('backoffice')->withErrors('Not Found User');
        }

        $this->user->update($params);
        return redirect('backoffice');
        
        // return view('backoffice.main');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->user->find($id);
        
        if(is_null($user)) {
            return redirect('backoffice')->withErrors('Not Found User');
        }

        $user->delete();
        return redirect('backoffice');        
    }
}
