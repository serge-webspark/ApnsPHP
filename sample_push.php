<?php
/**
 * Push demo
 *
 * SPDX-FileCopyrightText: Copyright 2010 Aldo Armiento (aldo.armiento@gmail.com)
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-3-Clause
 */

// Adjust to your timezone
date_default_timezone_set('Europe/Rome');

// Report all PHP errors
error_reporting(-1);

// Using Composer autoload all classes are loaded on-demand
require_once 'vendor/autoload.php';

class SampleLogger extends \Psr\Log\AbstractLogger
{
	public function log($level, $message, array $context = array()): void
	{
		printf("%s: %s ApnsPHP[%d]: %s\n", date('r'), strtoupper($level), getmypid(), trim($message));
	}
}

// Instantiate a new ApnsPHP_Push object
$push = new \ApnsPHP\Push(
	\ApnsPHP\Push::ENVIRONMENT_SANDBOX,
	'server_certificates_bundle_sandbox.pem',
	new SampleLogger(),
);

// Set the Provider Certificate passphrase
// $push->setProviderCertificatePassphrase('test');

// Connect to the Apple Push Notification Service
$push->connect();

// Instantiate a new Message with a single recipient
$message = new \ApnsPHP\Message('1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485');

// Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
// over a ApnsPHP_Message object retrieved with the getErrors() message.
$message->setCustomIdentifier('7530A828-E58E-433E-A38F-D8042208CF96');

// Set badge icon to "3"
$message->setBadge(3);

// Set a simple welcome text
$message->setText('Hello APNs-enabled device!');

// Play the default sound
$message->setSound();

// Set a custom property
$message->setCustomProperty('acme2', array('bang', 'whiz'));

// Set another custom property
$message->setCustomProperty('acme3', array('bing', 'bong'));

// Set the expiry value to 30 seconds
$message->setExpiry(30);

// Add the message to the message queue
$push->add($message);

// Send all messages in the message queue
$push->send();

// Disconnect from the Apple Push Notification Service
$push->disconnect();

// Examine the error message container
$aErrorQueue = $push->getErrors();
if (!empty($aErrorQueue)) {
	var_dump($aErrorQueue);
}
