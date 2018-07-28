<?php

declare(strict_types=1);

namespace xBeastMode\Football;

use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;

class Loader extends PluginBase{
	public function onEnable() : void{
		Entity::registerEntity(BallEntity::class, true);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		switch($command->getName()){
                        case "killballs":
                                $count = 0;
                                foreach($this->getServer()->getLevels() as $level){
                                        foreach($level->getEntities() as $entity){
                                                if($entity instanceof BallEntity){
                                                        $entity->close();
                                                        $count++;
                                                }
                                        }
                                }
                                $sender->sendMessage(TextFormat::GREEN . "Cleared " . TextFormat::YELLOW . $count . TextFormat::GREEN . " balls.");
                                return true;
			case "addball":
			        if($sender instanceof Player){
                                        if(count($args) > 0){
                                                switch(strtolower($args[0])){
                                                        case "small":
                                                                $nbt = Entity::createBaseNBT($sender->asVector3());
                                                                $ent = Entity::createEntity("BallEntity", $sender->level, $nbt);
                                                                $sender->sendMessage(TextFormat::GREEN . "Added new small ball.");
                                                                $ent->spawnToAll();
                                                                $sender->level->broadcastLevelSoundEvent($sender, LevelSoundEventPacket::SOUND_BLOCK_END_PORTAL_FRAME_FILL);
                                                                break;
                                                        case "medium":
                                                                $nbt = Entity::createBaseNBT($sender->asVector3());
                                                                $ent = Entity::createEntity("BallEntity", $sender->level, $nbt);
                                                                $sender->sendMessage(TextFormat::GREEN . "Added new medium ball.");
                                                                $ent->setScale(2);
                                                                $ent->spawnToAll();
                                                                $sender->level->broadcastLevelSoundEvent($sender, LevelSoundEventPacket::SOUND_BLOCK_END_PORTAL_FRAME_FILL);
                                                                break;
                                                        case "big":
                                                                $nbt = Entity::createBaseNBT($sender->asVector3());
                                                                $ent = Entity::createEntity("BallEntity", $sender->level, $nbt);
                                                                $sender->sendMessage(TextFormat::GREEN . "Added new big ball.");
                                                                $ent->setScale(3);
                                                                $ent->spawnToAll();
                                                                $sender->level->broadcastLevelSoundEvent($sender, LevelSoundEventPacket::SOUND_BLOCK_END_PORTAL_FRAME_FILL);
                                                                break;
                                                        default:
                                                                $sender->sendMessage(TextFormat::RED . "Ball not found. Please choose: " . TextFormat::YELLOW . "small, medium, big");
                                                                break;
                                                }
                                        }else{
                                                $nbt = Entity::createBaseNBT($sender->asVector3());
                                                $ent = Entity::createEntity("BallEntity", $sender->level, $nbt);
                                                $sender->sendMessage(TextFormat::GREEN . "Added new small ball.");
                                                $ent->spawnToAll();
                                                $sender->level->broadcastLevelSoundEvent($sender, LevelSoundEventPacket::SOUND_BLOCK_END_PORTAL_FRAME_FILL);
                                        }
                                }else{
			                $sender->sendMessage("Use command in-game.");
                                }
				return true;
			default:
				return false;
		}
	}
}
