<?php

namespace magi\services;

use Craft;
use GuzzleHttp\Exception\ClientException;
use magi\Magi;

class MemberService
{
    public function guilds()
    {
        return Magi::getInstance()->request->send('GET', 'users/@me/guilds');
    }
}