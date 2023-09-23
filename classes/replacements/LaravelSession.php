<?php

namespace Waka\SalesForce\Classes\Replacements;


use Omniphx\Forrest\Providers\Laravel\LaravelSession as OriginalLaravelSession;
use Illuminate\Contracts\Config\Repository as Config;

class LaravelSession extends OriginalLaravelSession
{

    public function __construct(Config $config, Session $session)
    {
        $this->path = $config->get('forrest.storage.path');
        $this->session = $session;
    }
}