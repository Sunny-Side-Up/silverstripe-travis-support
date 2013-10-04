#!/usr/bin/env php
<?php


$opts = getopt('', array(
	'if-env:',
	'base-url:',
));

// --if-env=BEHAT_TEST means that this script will only be executed if the given environment var is set
if(!empty($opts['if-env']) && !getenv($opts['if-env'])) {
	echo "Apache skipped; {$opts['if-env']} wasn't set.\n";
	exit(0);
}

echo "Starting Sauce Connect...\n";

passthru("sh -e /etc/init.d/xvfb start");
passthru("export DISPLAY=:99.0");
passthru("wget http://selenium.googlecode.com/files/selenium-server-standalone-2.31.0.jar");
passthru("java -jar selenium-server-standalone-2.31.0.jar > /dev/null &");
sleep(5);

passthru("export BEHAT_PARAMS=\"extensions[SilverStripe\BehatExtension\MinkExtension][base_url]={$opts['base-url']}\"");
passthru("php framework/cli-script.php dev/generatesecuretoken path=mysite/_config/behat.yml");
