<?php

namespace App\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Helpers\Permission;

class AdminComposer
{
    private $user;

    public function __construct(Request $request)
    {
        $this->user = $request->user();
    }

    public function compose(View $view)
    {
        if (!str_contains($view->name(), '_menu')) {
            $userAuth = new Permission($this->user->group, 'admin');

            $view->with('userAuth', $userAuth);
        }
    }
}