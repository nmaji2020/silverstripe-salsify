<?php

namespace Dynamic\Salsify\TypeHandler\Asset;

use SilverStripe\ORM\DataObject;

/**
 * Class FileHandler
 * @package Dynamic\Salsify\TypeHandler
 *
 * @property-read \Dynamic\Salsify\Model\Mapper|FileHandler $owner
 */
class FileHandler extends AssetHandler
{

    /**
     * @var array
     */
    private static $field_types = [
        'File',
        'ManyFiles',
    ];

    /**
     * @param string|DataObject $class
     * @param $data
     * @param $dataField
     * @param $config
     * @param $dbField
     * @return string|int
     *
     * @throws \Exception
     */
    public function handleFileType($class, $data, $dataField, $config, $dbField)
    {
        $data = $this->getAssetBySalsifyID($data[$dataField]);
        if (!$data) {
            return '';
        }

        $asset = $this->updateFile(
            $data['salsify:id'],
            $data['salsify:updated_at'],
            $data['salsify:url'],
            $data['salsify:name']
        );
        return preg_match('/ID$/', $dbField) ? $asset->ID : $asset;
    }

    /**
     * @param string|DataObject $class
     * @param $data
     * @param $dataField
     * @param $config
     * @param $dbField
     * @return array
     *
     * @throws \Exception
     */
    public function handleManyFilesType($class, $data, $dataField, $config, $dbField)
    {
        $files = [];
        $fieldData = $data[$dataField];
        foreach ($this->owner->yieldSingle($fieldData) as $fileID) {
            $entryData = array_merge($data, [
                $dataField => $fileID
            ]);
            $files[] = $this->owner->handleFileType($class, $entryData, $dataField, $config, $dbField, $class);
        }
        return $files;
    }
}
