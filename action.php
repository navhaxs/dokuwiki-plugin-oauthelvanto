<?php

use dokuwiki\plugin\oauth\Adapter;
use dokuwiki\plugin\oauthelvanto\Elvanto;

/**
 * Service Implementation for oAuth Doorkeeper authentication
 */
class action_plugin_oauthelvanto extends Adapter
{

    /** @inheritdoc */
    public function registerServiceClass()
    {
        return Elvanto::class;
    }

    /** * @inheritDoc */
    public function getUser()
    {
        $oauth = $this->getOAuthService();
        $data = array();

        $url = 'https://api.elvanto.com/v1/people/currentUser.json';

        $raw = $oauth->request($url);
        $result = json_decode($raw, true);

        $preferredName = isset($result['person'][0]['preferred_name']) ? $result['person'][0]['preferred_name'] : $result['person'][0]['firstname'];

        $data['user'] = 'elvanto-' . $result['person'][0]['id'];
        $data['name'] = $preferredName;
        $data['mail'] = $result['person'][0]['email'];

        return $data;
    }

    /** @inheritDoc */
    public function getScopes()
    {
        return ['ManagePeople'];
    }

    /** @inheritDoc */
    public function getLabel()
    {
        return 'Elvanto';
    }

    /** @inheritDoc */
    public function getColor()
    {
        return '#70be3f';
    }

}
