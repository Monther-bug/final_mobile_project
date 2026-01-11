<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'total_points',
        'current_streak',
        'longest_streak',
        'last_solved_at',
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
            'last_solved_at' => 'date',
        ];
    }

    /**
     * Update user streak when they solve a problem
     */
    public function updateStreak(): void
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        if ($this->last_solved_at === null) {
            // First time solving a problem
            $this->current_streak = 1;
            $this->longest_streak = 1;
        } elseif ($this->last_solved_at->toDateString() === $today) {
            // Already solved today, no change
            return;
        } elseif ($this->last_solved_at->toDateString() === $yesterday) {
            // Consecutive day - increment streak
            $this->current_streak++;
            if ($this->current_streak > $this->longest_streak) {
                $this->longest_streak = $this->current_streak;
            }
        } else {
            // Streak broken - reset to 1
            $this->current_streak = 1;
        }

        $this->last_solved_at = $today;
        $this->save();
    }

    public function solutions()
    {
        return $this->hasMany(Solution::class);
    }

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }
}
