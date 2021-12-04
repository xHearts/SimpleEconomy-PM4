<?php

namespace economy\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

use economy\Main;

class TopBalanceCommand extends Command{

    public function __construct($name){
        parent::__construct($name);
        Main::registerPermission("topbalance.command");
        $this->setDescription("See the players with the most money.");
        $this->setPermission("topbalance.command");
        $this->setAliases(["topbal"]);
    }

    public function execute(CommandSender $sender, string $label, array $args) : bool{
        if(Server::getInstance()->isOp($sender->getName()) or $sender->hasPermission("topbalance.command")){
            $cm = Main::getDatabase()->getTopMoney($sender);
        } else {
            $sender->sendMessage("§cYou don't have permission to use this command.");
            return false;
        }
        return true;
    }
}