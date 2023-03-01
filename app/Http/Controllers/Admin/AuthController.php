<?php

namespace LaraDev\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaraDev\Contract;
use LaraDev\Http\Controllers\Controller;
use LaraDev\Property;
use LaraDev\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        /*$user = User::where('id', 1)->first();
        $user->password = 'teste';
        $user->save();*/

        if (Auth::check() === true) {
            return redirect()->route('admin.home');
        }

        return view('admin.index');
    }

    public function home()
    {
        $lessors = User::lessors()->count();
        $lessees = User::lessees()->count();
        $team = User::where('admin', 1)->count();

        $propertyAvailable = Property::available()->count();
        $propertyUnavailable = Property::unavailable()->count();
        $propertiesTotal = Property::all()->count();

        $contractActive = Contract::active()->count();
        $contractCanceled = Contract::canceled()->count();
        $contractPending = Contract::pending()->count();
        $contractTotal = Contract::all()->count();

        $contracts = Contract::orderby('id', 'DESC')->limit(10)->get();

        $properties = Property::orderby('id', 'DESC')->limit(3)->get();

        //dd($lessors, $lessees, $team, $propertyAvailable, $propertyUnavailable, $propertiesTotal,$contractActive, $contractCanceled, $contractPending, $contractTotal);

        return view('admin.dashboard', [
            "lessors" => $lessors,
            "lessees" => $lessees,
            "team" => $team,
            "propertyAvailable" => $propertyAvailable,
            "propertyUnavailable" => $propertyUnavailable,
            "propertiesTotal" => $propertiesTotal,
            "contractActive" => $contractActive,
            "contractCanceled" => $contractCanceled,
            "contractPending" => $contractPending,
            "contractTotal" => $contractTotal,
            "contracts" => $contracts,
            "properties" => $properties
        ]);
    }

    public function login(Request $request)
    {
        if(in_array('', $request->only('email', 'password'))){
            $json['message'] = $this->message->error('Ooops, informe todos os dados para efetuar o login')->render();
            return response()->json($json);
        }

        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            $json['message'] = $this->message->error('Ooops, informe um email valido')->render();
            return response()->json($json);
        }


        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(!Auth::attempt($credentials)){
            $json['message'] = $this->message->error('Ooops, usuario ou senha invalido')->render();
            return response()->json($json);
        }

        $this->authenticated($request->getClientIp());

        $json['redirect'] = route('admin.home');
        return response()->json($json);
        //var_dump($request->all());
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    private function authenticated(string $ip)
    {
        $user = User::where('id', Auth::user()->id);
        $user->update([
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $ip
        ]);
    }
}
