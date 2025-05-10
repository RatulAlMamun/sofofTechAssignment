<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'priority',
        'user_id'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
        ];
    }

    /**

     * Get the user that owns the task.

     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**

     * The users that belong to the task.

     */
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
