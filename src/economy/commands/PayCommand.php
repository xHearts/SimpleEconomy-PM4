
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

use economy\Main;

class PayCommand extends Command{

    public function __construct($name){
        parent::__construct($name);
        Main::registerPermission("paycmd.command");
        $this->setDescription("pay money to a player's ");
        $this->setPermission("paycmd.command");
        $this->setUsage("/pay");
        $this->setAliases(["pay"]);
    }

    public function execute(CommandSender $sender, string $label, array $args) : bool{
        if(Server::getInstance()->isOp($sender->getName()) or $sender->hasPermission("paycmd.command")){
            if(isset($args[0]) and isset($args[1])){
                if(is_string($args[0])){
                    if(is_numeric($args[1])){
                        $cm = Main::getDatabase();
                        if($args[1] > $cm->getMoney($sender->getName())) {
                            $sender->sendMessage("§aYou dont have enough money");
                            return false:
                        }
                        if(($player = Server::getInstance()->getPlayerByPrefix($args[0])) instanceof Player){
                            $cm->addMoney($player->getName(), $args[1]);
                            $cm->removeMoney($sender->getName(), $args[1]);
                            $player->sendMessage("§aYou receive: " . number_format($args[1]) . " from ".$player->getName().".");
                            $sender->sendMessage("§aYou've payed: " . number_format($args[1]) . " to " . $player->getName() . ".");
                        } else {
                            $cm->addMoney($args[0], $args[1]);
                            $sender->sendMessage("§aYou've payed: " . number_format($args[1]) . " to " . $args[0] . ".");
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
                $sender->sendMessage("§cUsage: /pay <player> <amount>");
                return false;
            }
        } else {
            $sender->sendMessage("§cYou don't have permission to use this command.");
            return false;
        }
        return true;
    }

//copy and pasted w
}
