<?php

namespace App\Transformers;

//use App\Models\Rol;
use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Role;

class RolesTransformer extends TransformerAbstract {
    public function transform(Role $role) {
        return [
            'id' => $role->id,
            'cn_name' => $role->cn_name,
            'permission' => $role->permissions()->select('id', 'cn_name')->get()
        ];
    }
}
