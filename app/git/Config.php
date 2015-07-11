<?php

namespace Deploy;


use Nette\Neon\Neon;
use Tracy\Debugger;


class Config {
    /**
     * @var mixed
     */
    private $configuration;

    /**
     * @var string
     */
    private $configurationFile;


    /**
     * @param $applicationDirectory
     * @throws \Exception
     */
    function __construct($applicationDirectory) {
        $dataDirectory = realpath($applicationDirectory . '/../') . '/data';
        $this->ensureDirectoryExist($dataDirectory.'/x');
        $this->ensureDirectoryExist($dataDirectory);
        $this->configurationFile = $dataDirectory . '/config.neon';
        $this->ensureFileExist($this->configurationFile);
        $data = $this->ensureGetFileContent($this->configurationFile);

        try {
            $configuration = Neon::decode($data);
        } catch (\Exception $e) {
            throw new \Exception('Configuration file $ is broken.', null, $e);
        }

        $this->configuration = $configuration;
    }

    /**
     * @return mixed
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param mixed $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @throws \Exception
     */
    public function save() {
        $configuration = Neon::encode($this->configuration);
        $this->ensureSetFileContent($this->configurationFile, $configuration);
    }



    /**
     * @param $directory
     * @throws \Exception
     */
    private function ensureDirectoryExist($directory) {
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0770, true)) {
                throw new \Exception('Can not create directory ' . $directory);
            }
        }
    }

    /**
     * @param $filename
     * @throws \Exception
     */
    private function ensureFileExist($filename) {
        if (!file_exists($filename)) {
            if (file_put_contents($filename, '') === false) {
                throw new \Exception('Can not create file ' . $filename);
            }
        }
    }

    /**
     * @param $filename
     * @return string
     * @throws \Exception
     */
    private function ensureGetFileContent($filename) {
        $data = file_get_contents($filename);

        if ($data === false) {
            throw new \Exception("File $filename is not readable.");
        }
        return $data;
    }

    /**
     * @param $filename
     * @param $content
     * @return string
     * @throws \Exception
     */
    private function ensureSetFileContent($filename, $content) {
        if (file_put_contents($filename, $content) === false) {
            throw new \Exception("File $filename is not writable.");
        }
    }
}