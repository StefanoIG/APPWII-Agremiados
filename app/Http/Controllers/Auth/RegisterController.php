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
            'identification_number' => ['required', 'string', 'max:20', 'unique:users,identification_number'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'birth_date' => ['required', 'date', 'before:today'],
            'address' => ['required', 'string', 'max:500'],
            'gender' => ['required', 'in:M,F,Otro'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'profession' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'titulo_pdf' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'agree_terms' => ['required', 'accepted'],
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
        
        // Subir título PDF
        if (request()->hasFile('titulo_pdf')) {
            $tituloPath = request()->file('titulo_pdf')->store('titulos', 'public');
        }
        
        // Crear usuario con todos los campos
        $user = User::create([
            'name' => $data['name'],
            'identification_number' => $data['identification_number'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'birth_date' => $data['birth_date'],
            'address' => $data['address'],
            'gender' => $data['gender'],
            'emergency_contact_name' => $data['emergency_contact_name'],
            'emergency_contact_phone' => $data['emergency_contact_phone'],
            'profession' => $data['profession'],
            'password' => Hash::make($data['password']),
            'titulo_pdf' => $tituloPath,
            'is_active' => false, // Usuario inactivo hasta aprobación
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Asignar rol de usuario
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
        
        // Redirigir con mensaje sobre proceso de aprobación y suscripción
        return redirect('/login')->with('status', 
            'Registro exitoso. Tu cuenta está pendiente de activación. ' .
            'Una vez aprobada, deberás seleccionar y pagar un plan de suscripción para acceder completamente a la plataforma.'
        );
    }
}
