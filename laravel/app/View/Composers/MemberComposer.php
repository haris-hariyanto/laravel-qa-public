<?php

namespace App\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Helpers\Permission;

class MemberComposer
{
    private $user;

    public function __construct(Request $request)
    {
        $this->user = $request->user();
    }

    public function compose(View $view)
    {
        $allowedViews = [
            'main.layouts.navbar',
            'main.account.account-settings.index',
            'main.account.account-settings._sidebar',
        ];

        if ($this->user && in_array($view->name(), $allowedViews)) {
            $userAuth = new Permission($this->user->group, 'member');

            $view->with('userAuth', $userAuth);
        }
    }
}