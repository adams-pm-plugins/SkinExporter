<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 4/4/2020
 * Time: 2:56 PM
 */
declare(strict_types=1);

namespace ARTulloss\SkinExporter;

use Himbeer\LibSkin\SkinConverter;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use const DIRECTORY_SEPARATOR;
use function file_put_contents;
use function file_exists;
use function mkdir;

class SkinExportCommand extends PluginCommand {
    /**
     * SkinExportCommand constructor.
     * @param string $name
     * @param Plugin $owner
     */
    public function __construct(string $name, Plugin $owner) {
        parent::__construct($name, $owner);
        $this->setUsage('/skinexport <player>');
        $this->setDescription('Export skins of players!');
        $this->setPermission('skinexport.use');
    }
    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @throws \Exception
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args):void {
        if(!$this->testPermission($sender))
            return;
        if(isset($args[0])) {
            $player = $sender->getServer()->getPlayer($args[0]);
            if($player !== null) {
                $skin = $player->getSkin();
                $dataFolder = $this->getPlugin()->getDataFolder();
                $path = $dataFolder . DIRECTORY_SEPARATOR . $player->getName();
                if(!file_exists($path))
                    mkdir($path);
                $file = $path . DIRECTORY_SEPARATOR . $skin->getGeometryName();
                $geometryData = $skin->getGeometryData();
                file_put_contents($file . '.json', $geometryData);
                SkinConverter::skinDataToImageSave($skin->getSkinData(), $file . '.png');
                $sender->sendMessage(TextFormat::GREEN . "Exported the skin of {$player->getName()}");
            } else
                $sender->sendMessage(TextFormat::RED . 'That player is offline!');
        } else
            throw new InvalidCommandSyntaxException();
    }
}