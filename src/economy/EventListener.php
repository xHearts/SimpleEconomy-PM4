<?php

namespace economy;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use economy\Main;

class EventListener implements Listener{

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        if($player->hasPlayedBefore() == false){
           $database = Main::getDatabase();
           $database->setMoney($name, 0);
        }
    }
}