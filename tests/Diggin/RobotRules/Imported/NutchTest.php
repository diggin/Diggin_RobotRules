<?php

namespace Diggin\RobotRules\Imported;

/**
 * This file is borrowed Apache's Nutch test.
 *
 * @see plugin/lib-http/src/test/org/apache/nutch/protocol/http/api TestRobotRulesParser
 */
class NutchTest extends \PHPUnit_Framework_TestCase
{
    
    protected function getParserResult($robotstxt)
    {
        //new Diggin\RobotRules\Parser\TxtParser($robotstxt[0]));
        $config = array('space_as_separator' => true);
        return \Diggin\RobotRules\Parser\TxtStringParser::parse($robotstxt, $config);
    }

    public function testAcceptAll()
    {
        $accepter = new \Diggin\RobotRules\Accepter\TxtAccepter;

        $robotstxt = $this->_getRobotsStrings();
        $accepter->setRules($this->getParserResult($robotstxt[0]));

        $allowed = $this->_getAllowed();

        foreach ($this->_agents as $agent) {
            $accepter->setUserAgent($agent);
            foreach($this->_testPaths as $key => $path) {
                $this->assertTrue($allowed[0][$agent][$key] === $accepter->isAllow($path), 
                                  'agent :'.var_export($agent, true).PHP_EOL.
                                  'path :'.var_export($path, true).PHP_EOL.
                                  'boolean :'.var_export($accepter->isAllow($path), true).PHP_EOL
                                  
                                  );
            }
        }

        $accepter->setRules($this->getParserResult($robotstxt[1]));
        foreach ($this->_agents as $agent) {
            $accepter->setUserAgent($agent);
            foreach($this->_testPaths as $key => $path) {
                $this->assertTrue($allowed[1][$agent][$key] === $accepter->isAllow($path));
            }
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
       '',//null  // Used to test EMPTY_RULES
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
   'Agent1' =>
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
   'Agent2' =>
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
    'Agent3'=>
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
     'Agent4' =>
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
     'Agent5' =>
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
     'Agent1' => $this->_acceptAll, // Agent 1
     'Agent2' => $this->_acceptAll, // Agent 2
     'Agent3' => $this->_acceptAll, // Agent 3
     'Agent4' => $this->_acceptAll, // Agent 4
     'Agent5' => $this->_acceptAll // Agent 5
   )
 );
   }

}

    
