<?
namespace modules\users\controllers\frontend;

use modules\users\helpers\OAuthHelper;
use yii;
use yii\web\Controller;
use yii\authclient\OAuth2;
use yii\helpers\VarDumper;

class AuthController extends Controller {

    public function actions() {
        return [
            'index' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
                'successUrl' => yii\helpers\Url::toRoute(['/signup', 'oauth'=>1]),
            ],
        ];
    }

    /**
     * @param OAuth2 $client
     */
    public function successCallback($client) {
        $OAuthHelper = new OAuthHelper($client);
        $OAuthHelper->process();
    }

}