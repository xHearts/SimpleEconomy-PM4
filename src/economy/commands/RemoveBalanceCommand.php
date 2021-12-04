<?php

namespace economy\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

use economy\Main;

class RemoveBalanceCommand extends Command{

    public function __construct($name){
        parent::__construct($name);
        Main::registerPermission("removebalance.command");
        $this->setDescription("Remove money from a player's balance.");
        $this->setPermission("removebalance.command");
        $this->setUsage("/removebalance");
        $this->setAliases(["rmvbal"]);
    }

    public function execute(CommandSender $sender, string $label, array $args) : bool{
        if(Server::getInstance()->isOp($sender->getName()) or $sender->hasPermission("removebalance.command")){
            if(isset($args[0]) and isset($args[1])){
                if(is_string($args[0])){
                    if(is_numeric($args[1])){
                        $cm = Main::getDatabase();
                        if($cm->getMoney($args[0]) - $args[1] >= 0) {
                            if(($player = Server::getInstance()->getPlayerByPrefix($args[0])) instanceof Player){
                                $cm->removeMoney($player->getName(), $args[1]);
                                $player->sendMessage("§a$" . number_format($args[1]) . " has been removed from your balance.");
                                $sender->sendMessage("§aRemoved: $" . number_format($args[1]) . " from " . $player->getName() . "'s money balance.");
                            } else {
                                $cm->removeMoney($args[0], $args[1]);
                                $sender->sendMessage("§aRemoved: $" . number_format($args[1]) . " from " . $args[0] . "'s money balance.");
                            }
                        } else {
                            $sender->sendMessage("§cThat amount is too big.");
                            return false;
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
                $sender->sendMessage("§cUsage: /removebalance <player> <amount>");
                return false;
            }
        } else {
            $sender->sendMessage("§cYou don't have permission to use this command.");
            return false;
        }
        return true;
    }
}