<?php

use Jenssegers\Agent\Agent;

function browser_agent($user_agent)
{
    $agent = tap(new Agent(), fn ($agent) => $agent->setUserAgent($user_agent));

    return $agent->platform().' - '.$agent->browser();
}

function checkUuid($fieldUuid)
{
    abort_if((request()->uuid != $fieldUuid), 404);
}
