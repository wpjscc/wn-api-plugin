<?php namespace Wpjscc\Api\Models;

use Model;
use Backend\Classes\AuthManager;
use Winter\Storm\Auth\Models\Role as RoleBase;

/**
 * UserRole Model
 */
class UserRole extends RoleBase
{
    const CODE_DEVELOPER = 'developer';
    const CODE_PUBLISHER = 'publisher';
    /**
     * @var string The database table used by the model.
     */
    public $table = 'user_roles';


    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'name' => 'required|between:2,128|unique',
        'code' => 'unique',
    ];


    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [

    ];
    public $hasOneThrough = [];
    public $hasManyThrough = [];
    public $belongsTo = [];
    public $belongsToMany = [
        'users' => [User::class, 'table' => 'users_roles'],
        'users_count' => [User::class, 'table' => 'users_roles', 'count' => true],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
    public function filterFields($fields)
    {
        // System roles cannot have their code or permissions changed
        if ($this->isSystemRole()) {
            $fields->code->disabled = true;
            $fields->permissions->disabled = true;
        }
    }

    public function afterFetch()
    {
        // System role permissions are determined by the permissions that attach
        // themselves to the given role's code via the `roles` property.
        if ($this->isSystemRole()) {
            $this->permissions = $this->getDefaultPermissions();
        }
    }

    public function beforeSave()
    {
        // System roles cannot have their code or permissions changed
        if ($this->isSystemRole()) {
            $this->is_system = true;
            $this->permissions = [];
            if ($this->exists) {
                $this->code = $this->getOriginal('code');
            }
        }
    }

    public function isSystemRole()
    {
        // System roles must have a valid code property
        if (!$this->code || !strlen(trim($this->code))) {
            return false;
        }

        // Winter default system roles
        if ($this->is_system || in_array($this->code, [
            self::CODE_DEVELOPER,
            self::CODE_PUBLISHER
        ])) {
            return true;
        }

        // If any permission attaches itself to a given role's code
        // that role is now considered a system role
        return AuthManager::instance()->hasPermissionsForRole($this->code);
    }

    /**
     * Get the permissions that have attached themselves to the current role
     */
    public function getDefaultPermissions(): array
    {
        // Only the Develper role inherits all "orphaned" / unassigned permissions by default
        $includeOrphanedPermissions = false;
        if ($this->code === self::CODE_DEVELOPER) {
            $includeOrphanedPermissions = true;
        }

        return AuthManager::instance()->listPermissionsForRole($this->code, $includeOrphanedPermissions);
    }
}

