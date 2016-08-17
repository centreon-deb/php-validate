--TEST--
uri.phpt: Unit tests for Validate::uri()
--FILE--
<?php
// $Id: uri.phpt,v 1.3 2005/08/31 08:25:15 toggg Exp $
// Validate test script
$noYes = array('NO', 'YES');
require 'Validate.php';

echo "Test Validate::uri()\n";

$uris = array(
        // with no options (no domain_check and no allowed_schemes
        'not @ goodurl123' , // NOK
        'http://www.ics.uci.edu/pub/ietf/uri/#Related' , // OK
        'http://user:password@www.ics.uci.edu:8080/pub/ietf/uri;rfc2396?test=ok&end=next#Related' , // OK
        '//127.0.0.1', // OK
        '//127.0.333.1', // NOK
        'http://user:password@127.0.0.1:8080/pub/ietf/uri;rfc2396?test=ok&end=next#Related' , // OK
        '127.0.0.1', // NOK
        '/tkik-wkik_rss.php?ver=2http://www.hyperlecture.info//http://www.hyperlecture.info/accueil', // NOK
        // Try dns lookup
        array('//example.org', 'domain_check' => true), // OK
        array('//example.gor', 'domain_check' => true), // NOK
        // Try schemes lookup
        array('//example.org', 'allowed_schemes' => array('ftp', 'http')), // NOK
        array('http://example.org', 'allowed_schemes' => array('ftp', 'http')), // OK
        array('http://example.org', 'allowed_schemes' => array('ftp', 'http'),
                                    'domain_check' => true), // OK
        array(
        '/tkik-wkik_rss.php?ver=2http://www.hyperlecture.info//http://www.hyperlecture.info/accueil',
            'strict' => '') // OK
    );

foreach ($uris as $uri) {
    if (is_array($uri)) {
        $options = $uri;
        unset($options[0]);
        echo "{$uri[0]}: schemes(" .
            (isset($options['allowed_schemes']) ?
                implode(',', $options['allowed_schemes']) : '') .") with".
            (isset($options['domain_check']) && $options['domain_check'] ?
                             '' : 'out') . ' domain check : '.
            (isset($options['strict']) ? "(strict : {$options['strict']}) " : '') .
            $noYes[Validate::uri($uri[0], $options )]."\n";
    } else {
        echo "{$uri}: ".
            $noYes[Validate::uri($uri)]."\n";
    }
}
?>
--EXPECT--
Test Validate::uri()
not @ goodurl123: NO
http://www.ics.uci.edu/pub/ietf/uri/#Related: YES
http://user:password@www.ics.uci.edu:8080/pub/ietf/uri;rfc2396?test=ok&end=next#Related: YES
//127.0.0.1: YES
//127.0.333.1: NO
http://user:password@127.0.0.1:8080/pub/ietf/uri;rfc2396?test=ok&end=next#Related: YES
127.0.0.1: NO
/tkik-wkik_rss.php?ver=2http://www.hyperlecture.info//http://www.hyperlecture.info/accueil: NO
//example.org: schemes() with domain check : YES
//example.gor: schemes() with domain check : NO
//example.org: schemes(ftp,http) without domain check : NO
http://example.org: schemes(ftp,http) without domain check : YES
http://example.org: schemes(ftp,http) with domain check : YES
/tkik-wkik_rss.php?ver=2http://www.hyperlecture.info//http://www.hyperlecture.info/accueil: schemes() without domain check : (strict : ) YES
