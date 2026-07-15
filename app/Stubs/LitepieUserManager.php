<?php

namespace Litepie\User\Traits\Auth;

/**
 * Stub for the UserManager trait removed in lavalite/framework v12.
 * Provides getView() used by legacy app controllers.
 */
trait UserManager
{
    protected function getView(string $page): string
    {
        return 'user::' . ($this->home ?? 'user') . '.' . $page;
    }
}
