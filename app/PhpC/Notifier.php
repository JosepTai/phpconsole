<?php
/**
 * Created by PhpStorm.
 * User: JOSIAH
 * Date: 4/30/2018
 * Time: 1:38 PM
 */

namespace App\PhpC;

use Joli\JoliNotif\Notification;
use Joli\JoliNotif\Notifier\NullNotifier;
use Joli\JoliNotif\NotifierFactory;

class Notifier {

    public $title;
    public $message;
    public $icon;

    public function send() {
        $notifier = NotifierFactory::create();
        if (!($notifier instanceof NullNotifier)) {
            if ($this->icon == '') {
                $this->icon = public_path('img/icon-success.png');
            }
            $notification =
                (new Notification())
                    ->setTitle($this->title)
                    ->setBody($this->message)
                    ->setIcon($this->icon)
            ;
            $result = $notifier->send($notification);
        } else {
            $result = 'No supported notifier';
        }

        return $result;
    }

    public function title($title) {
        $this->title = $title;
        return $this;
    }

    public function message($message) {
        $this->message = $message;
        return $this;
    }

    public function icon($icon) {
        $this->icon = public_path($icon);
        return $this;
    }
}