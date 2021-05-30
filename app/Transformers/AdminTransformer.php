<?php

namespace App\Transformers;

use App\Models\Admin;
use League\Fractal\TransformerAbstract;

class AdminTransformer extends TransformerAbstract {
    public function transform(Admin $user) {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'is_locked' => $user->is_locked,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
    }
}
