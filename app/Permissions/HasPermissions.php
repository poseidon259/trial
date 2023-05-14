<?php

namespace App\Permissions;

trait HasPermissions
{
    protected $roles = [
        SLUG_PERMISSION_ADMIN => 1,
        SLUG_PERMISSION_STORE => 2,
        SLUG_PERMISSION_EMP => 3,
        SLUG_PERMISSION_USER => 4,
    ];

    public function hasPermission($slugPermission)
    {
        $roleId = $this->role_id;
        if (!$roleId) {
            return false;
        }

        if ($roleId == $this->roles[$slugPermission]) {
            return true;
        }

        return false;
    }

    /**
     * @param $slugRole
     * @return bool
     */
    public function hasRole($slugRole)
    {
        if ($this->roles->contains('slug', $slugRole)) {
            return true;
        }
        return false;
    }
}
