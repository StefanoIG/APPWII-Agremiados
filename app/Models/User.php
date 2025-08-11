<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'identification_number',
        'email',
        'phone',
        'birth_date',
        'address',
        'gender',
        'emergency_contact_name',
        'emergency_contact_phone',
        'profession',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'titulo_pdf',
        'qrpdt',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'birth_date' => 'date',
        ];
    }
    
    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'is_active' => false,
    ];

    /**
     * Get the profile URL for AdminLTE.
     */
    public function adminlte_profile_url()
    {
        return route('home');
    }

    /**
     * Get the profile image URL for AdminLTE.
     */
    public function adminlte_image()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the user description for AdminLTE.
     */
    public function adminlte_desc()
    {
        $roles = $this->getRoleNames();
        return $roles->isNotEmpty() ? $roles->first() : 'Usuario';
    }

    /**
     * Relación con las deudas del usuario
     */
    public function debts()
    {
        return $this->hasMany(UserDebt::class);
    }

    /**
     * Relación con los recibos de pago
     */
    public function paymentReceipts()
    {
        return $this->hasMany(PaymentReceipt::class);
    }

    /**
     * Verificar si el usuario tiene deudas pendientes
     */
    public function hasPendingDebts()
    {
        return $this->debts()->pending()->exists() || 
               $this->debts()->overdue()->exists();
    }

    /**
     * Verificar si el usuario puede participar en competencias
     */
    public function canParticipateInCompetitions()
    {
        return !$this->hasPendingDebts() && $this->is_active;
    }

    /**
     * Obtener deudas pendientes del usuario
     */
    public function getPendingDebts()
    {
        return $this->debts()->with('monthlyCut')->pending()->get();
    }

    /**
     * Obtener deudas vencidas del usuario
     */
    public function getOverdueDebts()
    {
        return $this->debts()->with('monthlyCut')->overdue()->get();
    }

    /**
     * Calcular total de deudas pendientes
     */
    public function getTotalPendingDebt()
    {
        return $this->debts()->where('status', '!=', 'paid')->sum('amount');
    }

    /**
     * Relación con las membresías de equipos
     */
    public function teamMemberships()
    {
        return $this->hasMany(CompetitionTeamMember::class);
    }

    /**
     * Relación con las invitaciones de equipos
     */
    public function teamInvitations()
    {
        return $this->hasMany(TeamInvitation::class);
    }
}
