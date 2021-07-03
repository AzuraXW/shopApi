<?php

namespace App\Transformers;

//use App\Models\Rol;
use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Role;

class RolesTransformer extends TransformerAbstract {
    public function transform(Role $role) {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'cn_name' => $role->cn_name,
            'description' => $role->description,
            'is_locked' => $role->is_locked,
            'permission' => $role->permissions()->select('id', 'cn_name')->get()
        ];
    }
}
