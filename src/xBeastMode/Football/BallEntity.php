<?php
namespace xBeastMode\Football;
use pocketmine\entity\Living;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
class BallEntity extends Living{
        public const NETWORK_ID = self::SLIME;

        public $hit = false;
        public $scale = 1;
        public $baseSize = 0.51;
        public $height = 0;
        public $width = 0;

        public $speed = 0;
        /** @var Vector3 */
        public $hitMotion = null;

        public function __construct(Level $level, CompoundTag $nbt){
                $this->height = ($this->baseSize * $this->scale) * $this->baseSize;
                $this->width = ($this->baseSize * $this->scale) * $this->baseSize;
                $this->motion = $this->hitMotion = new Vector3();
                parent::__construct($level, $nbt);
        }

        public function getName(): string{
                return "BallEntity";
        }

        public function entityBaseTick(int $tickDiff = 1): bool{
                if($this->hasMovementUpdate()){
                        $forceX = 0.9; // motion force for x
                        $forceZ = 0.9; // motion force for z
                        $forceY = 0.9; // motion force for y
                        $velDS = 0.08; //how fast the velocity decreases
                        $stopSpeed = 0.01; //speed where the ball stops

                        $vel = $this->getMotion();

                        if($this->onGround){
                                if($this->speed <= $stopSpeed){
                                        return parent::entityBaseTick($tickDiff);
                                }

                                $bounceX = abs($this->hitMotion->x - $vel->x);
                                $bounceZ = abs($this->hitMotion->z - $vel->z);

                                if($vel->x === 0){
                                        $vel->x = $vel->x + ((-$this->hitMotion->x) * $forceX);
                                }elseif($bounceX < 0.15){
                                        $vel->x = $vel->x + (($this->hitMotion->x * $forceX) + 0.075);
                                }

                                if(($vel->y === 0) && ($this->hitMotion->y < -0.1)){
                                        $vel->y = -$this->hitMotion->y * $forceY;
                                }

                                if($vel->z === 0.0){
                                        $vel->z = $vel->z + ((-$this->hitMotion->z) * $forceZ);
                                }elseif($bounceZ < 0.15){
                                        $vel->z = $vel->z + (($this->hitMotion->z * $forceZ) + 0.075);
                                }

                                $this->speed -= $velDS;

                                $this->setMotion($vel);
                                $this->hitMotion = $vel;
                        }
                }
                return parent::entityBaseTick($tickDiff);
        }

        public function onCollideWithPlayer(Player $player): void{
                $direc = $player->getDirectionVector();
                if(!$player->onGround){
                        $direc->y = 0.6;
                }
                if(!$player->isSprinting()){
                        $direc->divide(2);
                }
                $this->speed = 1;
                $this->setMotion($direc);
        }
}