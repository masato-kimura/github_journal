<?php
namespace service;

interface OauthLoginStrategy {
    // return loginUrl
    public function logout();
    public function get_login_url();
    public function get_user_info();
    public function get_request_token();
}