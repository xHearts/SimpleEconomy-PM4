# SimpleEconomy-PM4

**Tutorial (To use this)**
1. Get our API:
```php
$simpleEco = $this->getServer()->getPluginManager()->getPlugin("SimpleEconomy");
```

2. Use one or many of our functions
```php
$simpleEco->addMoney($player->getName(), $amount);
$simpleEco->getMoney($player->getName(), $amount);
$simpleEco->reduceMoney($player->getName(), $amount);
```

**Goals:**

- Add the pay command ✅
- Make the plugin more configurable (Editable messages, max money, etc.) ❌
