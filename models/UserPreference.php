<?php namespace Wpjscc\Api\Models;

use Auth;
use stdClass;
use Winter\Storm\Auth\Models\Preferences as PreferencesBase;

/**
 * All preferences for the backend user
 *
 * @package winter\wn-backend-module
 * @author Alexey Bobkov, Samuel Georges
 */
class UserPreference extends PreferencesBase
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'user_preferences';

    public $timestamps = false;

    protected static $cache = [];

    /**
     * Checks for a supplied user or uses the default logged in. You should override this method.
     * @param mixed $user An optional back-end user object.
     * @return User object
     */
    public function resolveUser($user)
    {
        $user = Auth::getUser();

        return $user;
    }

    public static function getConfigByUserId($userId)
    {
        $configs = self::query()->where('user_id', $userId)->get()->groupBy('namespace');
        $data = [];
        foreach ($configs as $namespace => $namespaceConfigs) {
            $groupConfigs = $namespaceConfigs->groupBy('group');
            foreach ($groupConfigs as $group => $itemConfigs) {
                foreach ($itemConfigs as $itemConfig) {
                    $data[$namespace.'_'.$group.'_'.$itemConfig->item] = $itemConfig->value ?: new stdClass;
                }
            }
        }

        return empty($data) ? new stdClass : $data;
    }
}
