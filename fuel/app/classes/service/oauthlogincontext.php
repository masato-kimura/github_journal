<?php
namespace service;

class OauthLoginContext {

    private $oauth_login;
    public function __construct(OauthLoginStrategy $oauth_login) {
        $this->oauth_login = $oauth_login;
    }

    public function get_user_info($req = null) {
        return $this->oauth_login->get_user_info($req);
    }

    public function logout() {
        return $this->oauth_login->logout();
    }

    public function get_login_url($type=null) {
        return $this->oauth_login->get_login_url($type);
    }

    public function get_request_token() {
        return $this->oauth_llogin->get_request_token();
    }

}
