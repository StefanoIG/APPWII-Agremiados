<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewUserRegistered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'lasname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'titulo_pdf' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'qr_pdf' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'idn' => ['nullable', 'string', 'max:45'],
            'phone' => ['nullable', 'string', 'max:45'],
            'address' => ['nullable', 'string', 'max:255'],
            'brithday' => ['nullable', 'date'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $tituloPath = null;
        $qrPath = null;
        if (request()->hasFile('titulo_pdf')) {
            $tituloPath = request()->file('titulo_pdf')->store('titulos', 'public');
        }
        if (request()->hasFile('qr_pdf')) {
            $qrPath = request()->file('qr_pdf')->store('qrs', 'public');
        }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'titulo_pdf' => $tituloPath,
            'qrpdt' => $qrPath,
            'is_active' => 0,
            'created_at' => now(),
        ]);
        $user->assignRole('user');
        return $user;
    }

    /**
     * Sobrescribe el registro para no loguear automáticamente y mostrar mensaje de espera.
     */
    public function register(\Illuminate\Http\Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        
        // Obtener todos los usuarios con rol de secretaria para notificar
        $secretarias = User::role('secretaria')->get();
        
        if ($secretarias->count() > 0) {
            // Enviar notificación a todas las secretarias
            Notification::send($secretarias, new NewUserRegistered($user));
        }
        
        // No loguear automáticamente
        return redirect('/login')->with('status', 'Registro exitoso. Tu cuenta está pendiente de activación por un administrador o secretaria.');
    }
}
