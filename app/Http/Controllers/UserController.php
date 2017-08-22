<?php

namespace App\Http\Controllers;

use App\User;
use App\Fuel;
use App\Tire;
use App\Maintain;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $user;
    protected $fuel;
    protected $tire;
    protected $maintain;
    
    function __construct(User $user, Fuel $fuel, Tire $tire, Maintain $maintain) {
        $this->user = $user;
        $this->fuel = $fuel;
        $this->tire = $tire;
        $this->maintain = $maintain;
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

    public function getTruckDriver()
    {
        $truckDriver = $this->user->where('role', 'truck_driver')->get();
        return response()->json($truckDriver);
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
        $rules = [
            'identificationNumber' => 'unique:users,identification_number|numeric',
            'username' => 'unique:users,username',
            'phone' => 'numeric',
        ];
        $messages = [
            'unique' => ':attribute ไม่สามารถซ้ำได้',
        ];

        $validator = $this->validate($request, $rules, $messages);

        $params = [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'identification_number' => $request->identificationNumber,
            'driver_license' => $request->driverLicense,
            'email'     => $request->email,
            'phone'       => $request->phone,
            'photo'     => $request->photo,
            'address'   => $request->address,
            'role'      => $request->role,
            'username'  => $request->has('password') ? $request->username : null,
            'password'  => $request->has('password') ? $request->password : null,
        ];

        $this->user->create($params);
        
        return redirect('backoffice');
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

        $rules = [
            'identificationNumber' => [
                'numeric',
                Rule::unique('users', 'identification_number')->ignore($id)
            ],    
            'driverLicense' => [
                Rule::unique('users', 'driver_license')->ignore($id)
            ],                   
            'username' => Rule::unique('users')->ignore($id),
            'phone' => 'numeric',
        ];
        $messages = [
            'unique' => ':attribute ไม่สามารถซ้ำได้',
        ];

        $validator = $this->validate($request, $rules, $messages);

        $params = [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'identification_number' => $request->identificationNumber,
            'driver_license' => $request->driverLicense,
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
            return redirect('backoffice')->withErrors('ไม่พบข้อมูลพนักงาน');
        }

        $user->update($params);
        return redirect('backoffice');
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

        $fuel = $this->fuel->where('truck_driver', $id)->count();
        $tire = $this->tire->where('truck_driver', $id)->count();
        $maintain = $this->maintain->where('truck_driver', $id)->count();
        
        if($fuel || $tire || $maintain) {
            return redirect('backoffice')->withErrors('ไม่สามารถลบพนักงานได้เนื่องจากมีการใช้งานอยู่');
        }
        if(is_null($user)) {
            return redirect('backoffice')->withErrors('ไม่พบข้อมูลพนักงาน');
        }

        $user->delete();
        return redirect('backoffice');        
    }

    public function auth(Request $request)
    {
        $user = $this->user
                ->where('username', $request->username)
                ->where('password', $request->password);
        if ($user->count() != 0) {
            Auth::login($user->get()->first());
            
            return redirect('/');
        } else {
            return redirect('login')->withErrors('ไม่พบข้อมูลบัญชีผู้ใช้นี้');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }
}
