<?php

namespace economy\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

use economy\Main;

class SetBalanceCommand extends Command{

    public function __construct($name){
        parent::__construct($name);
        Main::registerPermission("setbalance.command");
        $this->setDescription("Set a player's balance.");
        $this->setPermission("setbalance.command");
        $this->setAliases(["setbal"]);
    }

    public function execute(CommandSender $sender, string $label, array $args) : bool{
        if(Server::getInstance()->isOp($sender->getName()) or $sender->hasPermission("setbalance.command")){
            if(isset($args[0]) and isset($args[1])){
                if(is_string($args[0])){
                    if(is_numeric($args[1])){
                        $cm = Main::getDatabase();
                        if(($player = Server::getInstance()->getPlayerByPrefix($args[0])) instanceof Player){
                            $cm->setMoney($player->getName(), $args[1]);
                            $player->sendMessage("§aYour balance has been set to: $" . number_format($args[1]) . ".");
                            $sender->sendMessage("§aSet " . $player->getName() . "'s balance to: $" . number_format($args[1]) . ".");
                        } else {
                            $cm->setMoney($args[0], $args[1]);
                            $sender->sendMessage("§aSet " . $args[0] . "'s balance to: $" . number_format($args[1]) . ".");
                        }
                    } else {
                        $sender->sendMessage("§cThe amount has to be a number.");
                        return false;
                    }
                } else {
                    $sender->sendMessage("§cThat is not a player's name.");
                    return false;
                }
            } else {
                $sender->sendMessage("§cUsage: /setbalance <player> <amount>");
                return false;
            }
        } else {
            $sender->sendMessage("§cYou don't have permission to use this command.");
            return false;
        }
        return true;
    }
}