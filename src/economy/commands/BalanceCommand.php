<?php

namespace economy\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

use economy\Main;

class BalanceCommand extends Command{

    public function __construct($name){
        parent::__construct($name);
        Main::registerPermission("balance.command");
        $this->setDescription("Check your current money balance.");
        $this->setPermission("balance.command");
        $this->setUsage("/balance");
        $this->setAliases(["bal"]);
    }

    public function execute(CommandSender $sender, string $label, array $args) : bool{
        if(Server::getInstance()->isOp($sender->getName()) or $sender->hasPermission("balance.command")){
            if(isset($args[0])){
                $balance = Main::getDatabase()->getMoney($args[0]);
                $sender->sendMessage("§a" . $args[0]  . "'s current balance is: $" . number_format($balance) . ".");
            } else {
                if($sender instanceof Player){
                    $name = $sender->getName();
                    $balance = Main::getDatabase()->getMoney($name);
                    $sender->sendMessage("§aYour current balance is: $" . number_format($balance) . ".");
                } else {
                    $sender->sendMessage("§cThis command can only be used in-game.");
                    $sender->sendMessage("§aYou can do /balance <player> to check another player's balance.");
                    return false;
                }
            }
        } else {
            $sender->sendMessage("§cYou don't have permission to use this command.");
            return false;
        }
        return true;
    }
}