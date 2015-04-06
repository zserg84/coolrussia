<?php

/*
 * Vkontakte
 * 'uid' => 3777662
 * 'first_name' => 'Roman'
 * 'last_name' => 'Meier'
 * 'sex' => 2
 * 'nickname' => ''
 * 'screen_name' => 'majer_rv'
 * 'bdate' => '15.11.1985'
 * 'city' => 49
 * 'country' => 1
 * 'timezone' => 5
 * 'photo' => 'http://cs10802.vk.me/v10802662/1477/zNiYMkVrTMc.jpg'
 * 'id' => 3777662
 *
 *
 * Facebook
 * 'id' => '10203338218805484'
 * 'email' => 'majer.rv@gmail.com'
 * 'first_name' => 'Roman'
 * 'gender' => 'male'
 * 'last_name' => 'Meier'
 * 'link' => 'https://www.facebook.com/app_scoped_user_id/10203338218805484/'
 * 'locale' => 'ru_RU'
 * 'name' => 'Roman Meier'
 * 'timezone' => 5
 * 'updated_time' => '2014-08-08T16:36:21+0000'
 * 'verified' => true
 *
*/

namespace modules\users\helpers;

use yii;
use modules\users\models\frontend\User;
use yii\web\Session;
use yii\helpers\Url;


class OAuthHelper {

    /** @var yii\authclient\BaseOAuth $_client */
    private $_client;

    /** @var array $_attributes */
    private $_attributes = array();

    /** @var yii\web\Session $_session */
    private $_session;

    public function __construct($client) {
        $this->_client = $client;
    }


    /**
     * @param User $user
     */
    private function _login($user) {
        $this->_session['users_social_auth_status'] = $user->status_id;
        if ($user->status_id === User::STATUS_ACTIVE) {
            return Yii::$app->user->login($user, 3600 * 24 * 30);
        }
    }


    public function process() {
        $this->_attributes = $this->_client->getUserAttributes();

        // по умолчанию не все отдают емайл, поэтому запрашиваем принудительно
        if (!isset($this->_attributes['email']) or !$this->_attributes['email']) {
            // VKontakte
            if ($this->_client->id == 'vkontakte') {
                $token = $this->_client->getAccessToken();
                if ($email = $token->getParam('email')) {
                    $this->_attributes['email'] = $email;
                }
            }
        }

        $this->_session = new Session();
        $this->_session->open();


        if (isset($this->_attributes['email']) and ($email = $this->_attributes['email'])) {
            if ($user = $this->_getUserByEmail($email)) {
                return $this->_login($user);
            }
        }
        $this->registerUser();
    }


    /**
     * @param string $email
     * @return User
     */
    private function _getUserByEmail($email) {
        return User::find()->andWhere(['email'=>$email])->one();
    }


    private function _getFullAttributes() {
        switch ($this->_client->id) {
            case 'vkontakte':
                $res = $this->_client->api('users.get', 'GET', ['fields' => 'photo_200']); // sex, bdate, city, country, photo_50, photo_100, photo_200_orig, photo_200, photo_400_orig, photo_max, photo_max_orig, photo_id, online, online_mobile, domain, has_mobile, contacts, connections, site, education, universities, schools, can_post, can_see_all_posts, can_see_audio, can_write_private_message, status, last_seen, common_count, relation, relatives, counters, screen_name, maiden_name, timezone, occupation,activities, interests, music, movies, tv, books, games, about, quotes, personal
                if (is_array($res) and isset($res['response'])) {
                    $data = reset($res['response']);
                    $this->_attributes['user_avatar'] = $data['photo_200'];
                }
                if ($this->_attributes['city']) {
                    $res = $this->_client->api('database.getCitiesById', 'GET', ['city_ids' => $this->_attributes['city']]);
                    if (is_array($res) and isset($res['response'])) {
                        $data = reset($res['response']);
                        $this->_attributes['city_name'] = $data['name'];
                    }
                }
                $this->_attributes['name'] = $this->_attributes['first_name'] . ' ' . $this->_attributes['last_name'];

                if (isset($this->_attributes['bdate'])) {
                    $date = new \DateTime($this->_attributes['bdate']);
                    $this->_attributes['birthday'] = $date->format('Y-m-d');
                }
                break;

            case 'facebook':
                $this->_attributes['user_avatar'] = 'http://graph.facebook.com/' . $this->_attributes['id'] . '/picture?width=200&height=200';
                $res = $this->_client->api('/me', 'GET', ['fields' => 'hometown']);
                if (is_array($res) and isset($res['hometown'])) {
                    $this->_attributes['city_name'] = is_array($res['hometown']) ? reset($res['hometown']) : $res['hometown'];
                }
                break;

            case 'twitter':
                if (isset($this->_attributes['location'])) {
                    $this->_attributes['city_name'] = $this->_attributes['location'];
                }
                if (isset($this->_attributes['profile_image_url'])) {
                    $this->_attributes['user_avatar'] = str_replace('_normal.', '.', $this->_attributes['profile_image_url']);
                }
                break;
        }
        $this->_attributes['password'] = mb_substr(md5(time()), rand(0, 16), 8, 'utf8');
    }


    public function registerUser() {
        $this->_getFullAttributes();
        $this->_session['users_social_auth'] = $this->_attributes;
    }


    public static function GetAttributes() {
        $session = new Session();
        $session->open();

        $authLoginStatus = $session->get('users_social_auth_status');

        if (!is_null($authLoginStatus) and ($authLoginStatus != User::STATUS_ACTIVE)) {
            Yii::$app->response->redirect(Url::toRoute('/users/guest/login'));
        }

        return $session->get('users_social_auth');
    }


    public static function DropSession() {
        $session = new Session();
        $session->open();
        $session->remove('users_social_auth');
        $session->remove('users_social_auth_status');
    }

}