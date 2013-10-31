<?php
/**
 * Created by Ant-WEB S.L.
 * Developer: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 14/10/13
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ant\ChateaClient\Service\Client;

use Guzzle\Common\Collection;

/**
 * A FileStore represents a store pull for save data
 *
 * @package Ant\ChateaClient\Service\Client
 *
 * @see Collection
 */
class FileStore implements StoreInterface
{

    /**
     * array|\Guzzle\Common\Collection $config Configuration data
     */
    protected $config;
    /**
     * This store only support this type data to save
     *
     * @var array Collection support data to save
     */
    protected static $kSupportedKeys = array('access_token', 'token_refresh','token_expires_at');

    /**
     *  The algorithn used for signed data
     */
    const SIGNED_ALGORITHM = 'HMAC-SHA256';

    /**
     * Initializes a new instance of this class.
     *
     * @param array $config Associative array can configure the File store. The parameters are:
     *                      file_directory      The directory  where save the file
     *                      file_name           The name of file of data
     *                      SIGNED_ALGORITHM    The algorithn used for signed data.
     */
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

    /**
     * Save data in file.
     *
     * @param string $content The data to save
     */
    protected function saveInFile($content)
    {
        $filename = $this->config->get('file_directory').DIRECTORY_SEPARATOR.$this->config->get('file_name');
        $fp = fopen($filename,"a+b");
        //TODO: Cifrar contido
        fwrite($fp,$content.";");
        fclose($fp);
    }

    /**
     * Get all data in file.
     *
     * @return array Associative array with all data of file
     */
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