<?php

namespace IptcImport;

use Pimcore\API\Plugin as PluginLib;

class Plugin extends PluginLib\AbstractPlugin implements PluginLib\PluginInterface
{

    public function init()
    {
        parent::init();

        \Pimcore::getEventManager()->attach("asset.postAdd", array($this, "onAssetPostAdd"));

    }

    public function onAssetPostAdd($event)
    {
        $asset = $event->getTarget();
        $size = getimagesize($asset->getFileSystemPath(), $info);
		
		if(isset($info['APP13']))
		{
			$iptc = iptcparse($info['APP13']);
			foreach ($iptc as $iptcHeaderKey=>$iptcEntry) {
				if ($niceHeaderName = $this->getNiceHeaderName($iptcHeaderKey)) {
					$asset->addMetadata(
						$niceHeaderName,
						"input",
						implode(",", $iptcEntry), 
						"en"
					);
				}
			}
		}
		$asset->save();
    }

    public static function install()
    {
        // implement your own logic here
        return true;
    }
    
    public static function uninstall()
    {
        // implement your own logic here
        return true;
    }

    public static function isInstalled()
    {
        // implement your own logic here
        return true;
    }

	private function getNiceHeaderName($identifier)
	{
		$iptcHeaderArray = [
			'2#005'=>'DocumentTitle',
			'2#010'=>'Urgency',
			'2#015'=>'Category',
			'2#020'=>'Subcategories',
			'2#025'=>'Keywords',
			'2#040'=>'SpecialInstructions',
			'2#055'=>'CreationDate',
			'2#060'=>'CreationTime',
			'2#080'=>'AuthorByline',
			'2#085'=>'AuthorTitle',
			'2#090'=>'City',
			'2#092'=>'Sublocation',
			'2#095'=>'State',
			'2#100'=>'CountryCode',
			'2#101'=>'Country',
			'2#103'=>'OTR',
			'2#105'=>'Headline',
			'2#110'=>'Source',
			'2#115'=>'PhotoSource',
			'2#116'=>'Copyright',
			'2#120'=>'Caption',
			'2#122'=>'CaptionWriter'
		];
		if (array_key_exists($identifier, $iptcHeaderArray)) {
			return $iptcHeaderArray[$identifier];
		} else {
			return false;
		}
	}
}
