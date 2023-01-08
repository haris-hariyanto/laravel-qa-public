<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use Illuminate\Support\Facades\Gate;

class Permission
{
    private $group;
    private $groupPermissions;
    private $type;

    public function __construct(Group $group, $type)
    {
        $this->group = $group;
        $this->groupPermissions = $group
            ->groups_permissions()
            ->where('type', $type)
            ->pluck('permission')
            ->toArray();
        $this->type = $type;
    }

    public function authorize($action)
    {
        if ($this->type == 'admin') {
            if ($this->group->is_admin == 'Y') {
                if ($this->group->is_admin_restricted == 'N') {
                    return true;
                }
                else {
                    return in_array($action, $this->groupPermissions);
                }
            }
            else {
                return false;
            }
        }
        elseif ($this->type == 'member') {
            if ($this->group->is_member_restricted == 'N') {
                return true;
            }
            else {
                return in_array($action, $this->groupPermissions);
            }
        }

        return false;
    }
}