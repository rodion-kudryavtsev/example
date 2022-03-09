<?php
namespace ____;

use
	\WD\Pro\Helper,
	\WD\Pro\PluginManager,
	\WD\Pro\Sms\ProfileTable as Profile;

final class Manager{

	public const PLUGIN_TYPE_PRIMARY = 'PRIMARY';
	public const PLUGIN_TYPE_SECONDARY = 'SECONDARY';

	public static $arPluginMap = [
		self::PLUGIN_TYPE_PRIMARY => 'PROVIDER_PRIMARY',
		self::PLUGIN_TYPE_SECONDARY => 'PROVIDER_SECONDARY'
	];

	static public function send($intProfileId, array $arData){
		$arResponseObjects = [];
		$arPluginObjects = self::getPluginsFromProfile($intProfileId);
		if($arPluginObjects[self::PLUGIN_TYPE_PRIMARY] && $arPluginObjects[self::PLUGIN_TYPE_SECONDARY]){
			$arResponseObjects[self::PLUGIN_TYPE_PRIMARY] = $arPluginObjects[self::PLUGIN_TYPE_PRIMARY]->sendMessage($arData);
			if(!$arResponseObjects[self::PLUGIN_TYPE_PRIMARY]->getSuccess()){
				$arResponseObjects[self::PLUGIN_TYPE_SECONDARY] = $arPluginObjects[self::PLUGIN_TYPE_SECONDARY]->sendMessage($arData);
			}
		}
		elseif($arPluginObjects[self::PLUGIN_TYPE_PRIMARY]){
			$arResponseObjects[self::PLUGIN_TYPE_PRIMARY] = $arPluginObjects[self::PLUGIN_TYPE_PRIMARY]->sendMessage($arData);
		}
	}

	static private function getPluginsFromProfile($intProfileId):array{
		$arPluginObjects = [];
		if($arProfile = Profile::getList(['filter' => ['ID' => $intProfileId]])->fetch()){
			$arPlugins = PluginManager::getPlugins('sms');
			foreach ($arPlugins as $arPlugin){
				foreach (self::$arPluginMap as $strPluginType => $strColValue){
					if($arPlugin['CODE'] == $arProfile[$strColValue]){
						$arPluginObjects[$strPluginType] = new $arPlugin['CLASS_NAME']($strPluginType, $arProfile);
					}
				}
			}
		}
		return $arPluginObjects;
	}
 }
