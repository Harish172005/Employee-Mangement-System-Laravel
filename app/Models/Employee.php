<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

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
            'date_of_birth' => 'date',
            'date_of_joining' => 'date',
            'salary' => 'decimal:2',
            'gender' => Gender::class,
            'status' => Status::class,
        ];
    }

    protected static function booted(): void
    {
        //
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(
            fn (): string => trim("{$this->first_name} {$this->last_name}")
        );
    }

    protected function photoUrl(): Attribute
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        return Attribute::get(fn () => $this->profile_photo
            ? $disk->url($this->profile_photo)
            : asset('images/avatar.svg'));
    }

    public function scopeSearch($query, ?string $term)
    {
        return $query->when($term, function (Builder $query, $term) {

            $parts = preg_split('/\s+/', trim($term), 2);

            $query->where(function (Builder $query) use ($term, $parts) {

                foreach (['first_name', 'last_name', 'email', 'employee_code'] as $field) {
                    $query->orWhere($field, 'like', "%{$term}%");
                }

                if (count($parts) === 2) {
                    $query->orWhere(fn (Builder $q) => $q
                        ->where('first_name', 'like', "%{$parts[0]}%")
                        ->where('last_name', 'like', "%{$parts[1]}%")
                    );
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

    public function activityLogs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }
}
