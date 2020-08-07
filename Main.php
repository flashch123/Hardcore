<?php

namespace KStudio\DS;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;

use pocketmine\block\TNT;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerInteractEvent;

use onebone\economyapi\EconomyAPI;

use pocketmine\scheduler\Task;


use pocketmine\math\Vector3;

use pocketmine\level\Position;

class main extends PluginBase implements Listener {

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function Item(PlayerInteractEvent $event){
		$i = $event->getItem();
		
		if($i->getId() == "399"){
			$this->Fly($player);
		}
	}
	
	public function Fly($player){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null) {
			$result = $data;
			if($result === null){
				return true;
			}
			
			switch($result){
				case 0:
				         $player->setAllowFlight(true);
				break;
				case 1:
				         $player->setAllowFlight(false);
				break;
			}
		});
		$form->setTitle("§a§lFly");
		$form->setContent("§l§aApakah kamu ingin terbang ?");
		$form->addButton("aktif");
		$form->addButton("nonaktif");
	}

	public function onDamage(EntityDamageEvent $event){
	    $player = $event->getEntity();
	    
	    if($player instanceof Player and $player->getHealth() - $event->getBaseDamage() <= 0) {
	        $player->sendTitle("§cYou died!");
	        $player->setHealth(20);
	        $player->setFood(20);
	        $player->getInventory()->clearAll();
	        $player->getArmorInventory()->clearAll();
	        $player->getCursorInventory()->clearAll();
	        $player->removeAllEffects();
	        $player->setGamemode(3);
	    }
	}
	
	public function onPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        if(!$this->inGame($player)) {
            return;
        }

        $block = $event->getBlock();
        if($block instanceof TNT) {
            $block->ignite(50);
            $event->setCancelled(true);
            $player->getInventory()->removeItem(Item::get($block->getItemId()));
        }
    }
}