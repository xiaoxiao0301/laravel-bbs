<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function saving(User $user)
    {
        if (empty($user->avatar)) {
            $user->avatar = "https://cdn.learnku.com/uploads/images/201710/14/1/s5ehp11z6s.png";
        }
    }
}
