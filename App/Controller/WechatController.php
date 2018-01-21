<?php
namespace App\Controller;

use App\Components\Wechat\WechatAbstract;
use App\Components\Wechat\WechatRequest;
use Core\Uti\Tools\Config;
use EasyWeChat\Factory;

class WechatController extends WechatAbstract
{
    public function index()
    {
        // TODO: Implement index() method.
    }

    public function server()
    {
        if(isset($_GET['echostr'])){
            $this->response()->write($_GET['echostr']);
        }else {
            $config = Config::getInstance()->getConfig('config.wechat');
            $app    = Factory::officialAccount($config);
            $server = $app->server;
            $server->setRequest(WechatRequest::createFromGlobals());
            $user   = $app->user;

            $server->push(function($message) use ($user) {
                $fromUser = $user->get($message['FromUserName']);

                return "{$fromUser->nickname} 您好！欢迎关注 zmisgod!";
            });

            $response = $server->serve();
            $this->response()->write($response->getContent());
        }
    }
}