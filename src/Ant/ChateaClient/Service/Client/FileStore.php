<?php

namespace Ant\ChateaClient\Service\Client;

use Guzzle\Common\Collection;

class FileStore implements StoreInterface
{
    protected $config;
    protected static $kSupportedKeys = array('access_token', 'token_refresh','token_expires_at');

    const SIGNED_ALGORITHM = 'HMAC-SHA256';

    public function __construct($config = array())
    {
        $default = array(
            'file_directory' => sys_get_temp_dir(),
            'file_name' => 'chatea_client',
            'SIGNED_ALGORITHM' => 'HMAC-SHA256'
        );
        $required = array(
            'file_directory',
            'file_name',
            'SIGNED_ALGORITHM'
        );

        // Merge in default settings and validate the config
        $this->config = Collection::fromConfig($config, $default, $required);
    }

    protected function saveInFile($content)
    {
        $filename = $this->config->get('file_directory').DIRECTORY_SEPARATOR.$this->config->get('file_name');
        $fp = fopen($filename,"a+b");
        //TODO: Cifrar contido
        fwrite($fp,$content.";");
        fclose($fp);
    }
    protected function getDataInFile()
    {
        $filename = $this->config->get('file_directory').DIRECTORY_SEPARATOR.$this->config->get('file_name');

        if(!file_exists($filename)){
            return array();
        }

        $fp = fopen($filename,"rb");
        //TODO: Descifrar
        $contenido = fread($fp, filesize($filename));
        fclose($fp);

        $data_ouput = array();

        foreach (explode(';',$contenido) as $key=>$value){
            $data_tmp = explode('=',$value);
            if(count($data_tmp) > 1){
                $data_ouput[$data_tmp[0]] = $data_tmp[1];
            }
        }
        return $data_ouput;
    }
    /**
     * Stores the given ($key, $value) pair, so that future calls to
     * getPersistentData($key) return $value. This call may be in another request.
     *
     * @param string $key
     * @param array  $value
     *
     * @throws AuthenticationException
     * @return void
     */
    public function setPersistentData ($key, $value)
    {
        if (!in_array($key, self::$kSupportedKeys)) {
            throw new AuthenticationException('Unsupported key passed to setPersistentData.');
        }
        $this->saveInFile($key.'='.$value);
    }

    /**
     * Get the data for $key, persisted by Client::setPersistentData()
     *
     * @param string  $key     The key of the data to retrieve
     * @param boolean $default The default value to return if $key is not found
     *
     * @return mixed
     */
    public function getPersistentData ($key, $default = false)
    {
        if (!in_array($key, self::$kSupportedKeys)) {
            return $default;
        }

        $lines = $this->getDataInFile();
        return array_key_exists($key, $lines) ?  $lines[$key] : $default;
    }

    /**
     * Clear the data with $key from the persistent storage
     *
     * @param string $key
     *
     * @return void
     */
    public function clearPersistentData ($key)
    {
        $filename = $this->config->get('file_directory').DIRECTORY_SEPARATOR.$this->config->get('file_name');

        if(!file_exists($filename)){
            return array();
        }

        $fp = fopen($filename,"rb");
        //TODO: Descifrar
        $contenido = fread($fp, filesize($filename));
        fclose($fp);

        $lines = explode(';',$contenido);
        $lines = array_key_exists($key, $lines) ? $lines[$key] = null: $lines;

        $fp = fopen($filename,"wb");

        foreach($lines as $key=>$value){
            fwrite($fp,$key.'=>'.$value.";");
        }
        fclose($fp);
    }

    /**
     * Clear all data from the persistent storage
     *
     * @return void
     */
    public function clearAllPersistentData ()
    {
        $filename = $this->config->get('file_directory').__DIR__.$this->config->get('file_name');
        unlink ($filename);
    }}