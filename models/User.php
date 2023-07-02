<?php namespace Wpjscc\Api\Models;

use Winter\User\Models\User as UserBase;
use Auth;

class User extends UserBase
{
    use \Laravel\Sanctum\HasApiTokens;

    /**
     * Returns an array of merged permissions based on the user's individual permissions
     * and their group permissions filtering out any permissions the impersonator doesn't
     * have access to (if the current user is being impersonated)
     *
     * @return array
     */
    public function getMergedPermissions()
    {
        if (!$this->mergedPermissions) {

            $permissions = [];
            $singleRole = $this->role;
            $roles = $this->roles ?: [];
            if ($singleRole) {
                $roles->merge($singleRole);
            }
            foreach ($roles as $role) {

                if ($role && is_array($role->permissions)) {
                    $permissions = array_merge($permissions, $role->permissions);
                }

                if (is_array($this->permissions)) {
                    $permissions = array_merge($permissions, $this->permissions);
                }

            }

            $this->mergedPermissions = $permissions;

            // If the user is being impersonated filter out any permissions the impersonator doesn't have access to already
            if (Auth::isImpersonator()) {
                $impersonator = Auth::getImpersonator();
                if ($impersonator && $impersonator !== $this) {
                    foreach ($permissions as $permission => $status) {
                        if (!$impersonator->hasAccess($permission)) {
                            unset($permissions[$permission]);
                        }
                    }
                    $this->mergedPermissions = $permissions;
                }
            }
        }

        return $this->mergedPermissions;
    }
}
