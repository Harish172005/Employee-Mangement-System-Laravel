<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\Status;
use App\Scopes\ActiveEmployeeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Explicit allowlist for mass assignment; safer than $guarded because
     * newly added database columns are not writable until deliberately added.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_code',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'date_of_joining',
        'department_id',
        'designation',
        'salary',
        'address',
        'profile_photo',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth'   => 'date',
            'date_of_joining' => 'date',
            'salary'          => 'decimal:2',
            'gender'          => Gender::class,
            'status'          => Status::class,
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new ActiveEmployeeScope());
    }

    protected function fullName(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(
            fn (): string => trim("{$this->first_name} {$this->last_name}")
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::Active->value);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', Status::Inactive->value);
    }

    public function scopeSearch($query, ?string $term)
    {
        return $query->when($term, function ($query, $term) {
            $nameParts = preg_split('/\s+/', trim($term), 2);
            $query->where(function ($query) use ($term, $nameParts) {
                $query->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('employee_code', 'like', "%{$term}%");
                if (count($nameParts) === 2) {
                    $query->orWhere(fn ($query) => $query
                        ->where('first_name', 'like', "%{$nameParts[0]}%")
                        ->where('last_name', 'like', "%{$nameParts[1]}%"));
                }
            });
        });
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }

    public function activityLogs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }
}
