<?php

namespace Mediashare\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MediashareUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
