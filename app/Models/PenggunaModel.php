<?php

namespace App\Models;

use App\Models\Akreditasi\Institution;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PenggunaModel extends Model
{
    use HasUuids;
    protected $table = 'users';

    protected $fillable = [
        'id',
        'name',
        'picture',
        'username',
        'email',
        'status',
        'status_message',
        'active',
        'last_active',
    ];

    public function identities(): HasMany
    {
        return $this->hasMany(AuthIdentity::class, 'user_id', 'id');
    }

    public function groupList(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $this->groups->map(fn($g) => $g->group)->toArray(),
        );
    }

    public function email(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $this->identities->where('type', 'email_password')->first()->secret,
        );
    }

    public function groups(): HasMany
    {
        return $this->hasMany(AuthGroupUser::class, 'user_id', 'id');
    }



    public function setEmailIdentity(array $identities): self
    {
        $attr = [
            'email' => null,
            'password' => null,
        ];
        $identity = array_merge($attr, array_intersect_key($identities, array_flip(['email', 'password'])));

        $emailIdentity = AuthIdentity::firstOrNew(
            ['user_id' => $this->id, 'type' => Session::ID_TYPE_EMAIL_PASSWORD]
        );
        if ($identity['email']) {
            $emailIdentity->secret = $identity['email'];
        }
        if ($identity['password']) {
            $emailIdentity->secret2 = service('passwords')->hash($identity['password']);
        }
        $emailIdentity->save();
        return $this;
    }

    public function addGroup(string ...$groups): self
    {
        $users = auth()->getProvider();
        $user = $users->findById($this->id);
        $user->addGroup(...$groups);
        return $this;
    }

    /**
     * Activate a User.
     */
    public function activate(): self
    {
        $users = auth()->getProvider();
        $user = $users->findById($this->id);
        $user->active = true;
        $users->save($user);
        return $this;
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (PenggunaModel $pengguna) {});
    }
}
