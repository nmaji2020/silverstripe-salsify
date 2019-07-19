<?php

namespace Dynamic\Salsify\Model;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;

/**
 * Class Service
 * @package Dynamic\Salsify\Model
 *
 * @mixin Configurable
 * @mixin Extensible
 * @mixin Injectable
 */
abstract class Service
{
    use Configurable;
    use Extensible;
    use Injectable;

    /**
     * @var string
     */
    protected $importerKey;

    /**
     * @var string
     */
    protected $serviceName;


    public function __construct($importerKey)
    {
        $this->importerKey = $importerKey;
        $this->serviceName = static::class . '.' . $this->importerKey;

        $serviceConfig = Config::inst()->get($this->serviceName);
        foreach ($serviceConfig as $key => $value) {
            $this->config()->set($key, $value);
        }
    }
}