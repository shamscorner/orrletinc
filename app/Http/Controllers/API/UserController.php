<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
    * Author: shamscorner
    * DateTime: 14/September/2019 - 17:19:32
    */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // limit the access
        //$this->authorize('isAdmin');

        if (\Gate::allows('isAdmin') || \Gate::allows('isManager')) {
            // The current user can edit settings
            return User::latest()->paginate(10);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // limit the access
        $this->authorize('isAdmin');
        
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|min:6'
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'photo' => $request['photo'],
            'password' => Hash::make($request['password']),
        ]);
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
     * Display the current user profile.
     *
     * @param  void
     */
    public function profile()
    {
        return auth('api')->user();
    }

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);

        $currentPhoto = $user->photo;

        if ($request->photo != $currentPhoto) {
            $name = time().
            '.'.
            explode(
                '/',
                explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1]
            )[1];

            // upload image with intervention
            \Image::make($request->photo)->save(public_path('img/profile/').$name);

            // change the name of the requested photo
            //$request->photo = $name;
            $request->merge(['photo' => $name]);

            // delete the old photo
            $userPhoto = public_path('img/profile/').$currentPhoto;
            if (file_exists($userPhoto)) {
                @unlink($userPhoto);
            }
        }

        // hash the password
        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());
        return ['message' => 'success'];
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
        // limit the access
        //$this->authorize('isAdmin');

        if (\Gate::allows('isAdmin') || \Gate::allows('isManager')) {
            $user = User::findOrFail($id);

            $this->validate($request, [
                'name' => 'required|string|max:191',
                'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
                'password' => 'sometimes|min:6'
            ]);

            $user->update($request->all());

            return ['message' => 'Updated Successfully'];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // limit the access
        $this->authorize('isAdmin');

        $user = User::findOrFail($id);
        
        // delete the user
        $user->delete();

        return ['message' => 'User has been deleted'];
    }

    /**
    * Author: shamscorner
    * DateTime: 16/September/2019 - 03:04:36
    *
    * search data from the user
    *
    */
    public function search()
    {
        if ($search = \Request::get('query')) {
            $users = User::where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->orWhere('id', 'LIKE', "%$search%")
                ->orWhere('type', 'LIKE', "%$search%");
            })->paginate(10);
        } else {
            $users = User::latest()->paginate(10);
        }
        return $users;
    }
}
