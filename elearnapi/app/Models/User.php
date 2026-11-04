<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Course;
use App\Models\Enrollment;
use Laravel\Sanctum\HasApiTokens;

//pour media
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens;
    use InteractsWithMedia; //pour media
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
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
     * en relation avec les fonctions d'accès aux médias, pour ajouter les champs avatar_url et avatar_thumb à la réponse JSON de l'utilisateur
 avatarThumb()
 avatarUrl()
     */
    protected $appends = [
        'avatar_url',
        'avatar_thumb'
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
        ];
    }
    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
    /**
     * pour les médias
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png');
            }
        );
    }

    protected function avatarThumb(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->getFirstMediaUrl('avatar', 'thumb') ?: asset('images/default-avatar.png');
            }
            //?: asset('images/default-avatar.png'); }
        );
    }
    public function goals()
    {
        return $this->hasMany(Goal::class);
    }
}
