<?php

require_once 'PHPUnit/Framework.php';


set_include_path(dirname(__FILE__) . '/../../../../library' . PATH_SEPARATOR . get_include_path());

require_once 'Zend/Loader/Autoloader.php';
//Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
Zend_Loader_Autoloader::getInstance()->registerNamespace('Diggin_');


class Digggin_RobotRules_ImportNutch_NutchTest extends PHPUnit_Framework_TestCase
{

    public function testAcceptAll()
    {
        $accepter = new Diggin_RobotRules_Accepter_Txt;

        $robotstxt = $this->_getRobotsStrings();
        $accepter->setProtocol(new Diggin_RobotRules_Protocol_Txt($robotstxt[0]));
        
        $agent = $this->_agents[0];
        $accepter->setUserAgent($agent);
        $allowed = $this->_getAllowed();
        $allowed0 = current(array_shift($allowed));
        foreach($this->_testPaths as $key => $path) {
            var_dump($path);
            var_dump($allowed0[$key]);
            
            var_dump($accepter->isAllow($path));
            //$this->
        }
    }
    


   //////////////////////////////////////////////////import from nutch
   const CR = "\n";


   /*
   static public function dump($method)
   {
       $self = new self;
       var_dump(call_user_func(array($self, $method)));
   }
   */

   private $_acceptAll = array(
       true,   // "/a",
       true,   // "/a/",
       true,   // "/a/bloh/foo.html"
       true,   // "/b",
       true,   // "/b/a",
       true,   // "/b/a/index.html",
       true,   // "/b/b/foo.html",
       true,   // "/c",
       true,   // "/c/a",
       true,   // "/c/a/index.html",
       true,   // "/c/b/foo.html",
       true,   // "/d",
       true,   // "/d/a",
       true,   // "/e/a/index.html",
       true,   // "/e/d",
       true,   // "/e/d/foo.html",
       true,   // "/e/doh.html",
       true,   // "/f/index.html",
       true,   // "/foo/bar.html",
       true,   // "/f/",
   );

   private function _getRobotsStrings()
   {
       return array(
       "User-Agent: Agent1 #foo" . self::CR
       . "Disallow: /a" . self::CR
       . "Disallow: /b/a" . self::CR
       . "#Disallow: /c" . self::CR
       . "" . self::CR
       . "" . self::CR
       . "User-Agent: Agent2 Agent3#foo" . self::CR
       . "User-Agent: Agent4" . self::CR
       . "Disallow: /d" . self::CR
       . "Disallow: /e/d/" . self::CR
       . "" . self::CR
       . "User-Agent: *" . self::CR
       . "Disallow: /foo/bar/" . self::CR,
       null  // Used to test EMPTY_RULES
      );
   }

   private $_agents = array(
       "Agent1",
       "Agent2",
       "Agent3",
       "Agent4",
       "Agent5",
   );
   private $_notInRobotsString = array(
       array (
         false,
         false,
         false,
         false,
         true,
       ),
       array(
         false,
         false,
         false,
         false,
         true,
       )
   );

   private $_testPaths = array(
       "/a",
       "/a/",
       "/a/bloh/foo.html",
       "/b",
       "/b/a",
       "/b/a/index.html",
       "/b/b/foo.html",
       "/c",
       "/c/a",
       "/c/a/index.html",
       "/c/b/foo.html",
       "/d",
       "/d/a",
       "/e/a/index.html",
       "/e/d",
       "/e/d/foo.html",
       "/e/doh.html",
       "/f/index.html",
       "/foo/bar/baz.html",
       "/f/",
   );

   private function _getAllowed()
   {
    return    array(
 array(
   // ROBOTS_STRINGS[0]
   array( // Agent1
       false,  // "/a",
       false,  // "/a/",
       false,  // "/a/bloh/foo.html"
       true,   // "/b",
       false,  // "/b/a",
       false,  // "/b/a/index.html",
       true,   // "/b/b/foo.html",
       true,   // "/c",
       true,   // "/c/a",
       true,   // "/c/a/index.html",
       true,   // "/c/b/foo.html",
       true,   // "/d",
       true,   // "/d/a",
       true,   // "/e/a/index.html",
       true,   // "/e/d",
       true,   // "/e/d/foo.html",
       true,   // "/e/doh.html",
       true,   // "/f/index.html",
       true,   // "/foo/bar.html",
       true,   // "/f/",
     ),
   array( // Agent2
       true,   // "/a",
       true,   // "/a/",
       true,   // "/a/bloh/foo.html"
       true,   // "/b",
       true,   // "/b/a",
       true,   // "/b/a/index.html",
       true,   // "/b/b/foo.html",
       true,   // "/c",
       true,   // "/c/a",
       true,   // "/c/a/index.html",
       true,   // "/c/b/foo.html",
       false,  // "/d",
       false,  // "/d/a",
       true,   // "/e/a/index.html",
       true,   // "/e/d",
       false,  // "/e/d/foo.html",
       true,   // "/e/doh.html",
       true,   // "/f/index.html",
       true,   // "/foo/bar.html",
       true,   // "/f/",
     ),
   array( // Agent3
       true,   // "/a",
       true,   // "/a/",
       true,   // "/a/bloh/foo.html"
       true,   // "/b",
       true,   // "/b/a",
       true,   // "/b/a/index.html",
       true,   // "/b/b/foo.html",
       true,   // "/c",
       true,   // "/c/a",
       true,   // "/c/a/index.html",
       true,   // "/c/b/foo.html",
       false,  // "/d",
       false,  // "/d/a",
       true,   // "/e/a/index.html",
       true,   // "/e/d",
       false,  // "/e/d/foo.html",
       true,   // "/e/doh.html",
       true,   // "/f/index.html",
       true,   // "/foo/bar.html",
       true,   // "/f/",
     ),
   array( // Agent4
       true,   // "/a",
       true,   // "/a/",
       true,   // "/a/bloh/foo.html"
       true,   // "/b",
       true,   // "/b/a",
       true,   // "/b/a/index.html",
       true,   // "/b/b/foo.html",
       true,   // "/c",
       true,   // "/c/a",
       true,   // "/c/a/index.html",
       true,   // "/c/b/foo.html",
       false,  // "/d",
       false,  // "/d/a",
       true,   // "/e/a/index.html",
       true,   // "/e/d",
       false,  // "/e/d/foo.html",
       true,   // "/e/doh.html",
       true,   // "/f/index.html",
       true,   // "/foo/bar.html",
       true,   // "/f/",
     ),
   array( // Agent5/"*"
       true,   // "/a",
       true,   // "/a/",
       true,   // "/a/bloh/foo.html"
       true,   // "/b",
       true,   // "/b/a",
       true,   // "/b/a/index.html",
       true,   // "/b/b/foo.html",
       true,   // "/c",
       true,   // "/c/a",
       true,   // "/c/a/index.html",
       true,   // "/c/b/foo.html",
       true,   // "/d",
       true,   // "/d/a",
       true,   // "/e/a/index.html",
       true,   // "/e/d",
       true,   // "/e/d/foo.html",
       true,   // "/e/doh.html",
       true,   // "/f/index.html",
       false,  // "/foo/bar.html",
       true,   // "/f/",
   )
 ),
   array(
    // ROBOTS_STRINGS[1]
     $this->_acceptAll, // Agent 1
     $this->_acceptAll, // Agent 2
     $this->_acceptAll, // Agent 3
     $this->_acceptAll, // Agent 4
     $this->_acceptAll // Agent 5
   )
 );
   }


}
