<?php

class Diggin_RobotRules_Registry
{
    private static $_instance;

    /**
     * @var string
     */
    protected $_useragent;

    protected $_parser = array();

    protected $_storage;

    // array(Protocol => Accpeter
    protected $_accepters = array('txt' => 'Txt');


    /**
     * @param format
     * @param $type @todo detect?
     */
    public function addProtocolText($txt, $type = null)
    {


        return $this;
    }

    public function getAccepterLoader()
    {
        //if ()
        $accepterLoader = new Diggin_Loader_PluginLoader_Iterator($this->_accepters);

        //if ($resets = $this->accepterResets(()) {
        // new FilterIterator($accepterLoader);
        //}

        return $accepterLoader;
    }

    //load protocol(parser result)
    public function loadProtocol($key)
    {
    
    }

    /**
     * store current added protocols
     */
    public function store()
    {
        if ($this->_loadStorage()->isAvailable()) {
        }

    }

    private function __construct()
    {}

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

}
