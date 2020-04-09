<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Common\Grav;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class ElyiosSyntaxPlugin
 * @package Grav\Plugin
 */
class WebhookPlugin extends Plugin
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onFormProcessed' => ['onFormProcessed', 0]
        ];
    }

    public function onFormProcessed(Event $event)
    {
        $form = $event['form'];
        $action = $event['action'];
        $params = $event['params'];
        $slackWebhook = $this->config->get('plugins.slack-webhook.webhook_url');

        if (empty($slackWebhook)) {
            return;
        }

        switch ($action) {
        case 'slack':
            //do what you want

            $content = $this->grav['twig']->processString($params['body'], ['form' => $form]);

	    $useragent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
	    $payload = 'payload={"channel": "#xxx", "username": "webhookbot", "text": "'.$content.'", "icon_emoji": ":ghost:"}';

	    $ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent); //set our user agent
		curl_setopt($ch, CURLOPT_POST, TRUE); //set how many paramaters to post
		curl_setopt($ch, CURLOPT_URL,$slackWebhook); //set the url we want to use
		curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);

		curl_exec($ch); //execute and get the results
		curl_close($ch);


        }
    }
}
