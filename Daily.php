<?php

// Location of the server
$lattitude = 43.38453;
$longitude = -79.71803;
$zenith = 90 + (50/60);

// Expect this to be run at the beginning of the day right after midnight

$today = new DateTime();
$todayStamp = $today->getTimestamp();
$todayDay = getdate($todayStamp);

$offset = ($today->getOffset())/3600;

$morningOn = new DateTime("05:00");
$morningOnStamp = $morningOn->getTimestamp();

// 6:00pm
$frontEveningOn = new DateTime("18:00");
$frontEveningOnStamp = $frontEveningOn->getTimestamp();

// 8:00pm
$frontEveningOff = new DateTime("20:00");
$frontEveningOffStamp = $frontEveningOff->getTimestamp();

// 9:05pm
$backEarlyEveningOff = new DateTime("21:05");
$backEarlyEveningOffStamp = $backEarlyEveningOff->getTimeStamp();

// 10:05pm
$backLateEveningOff = new DateTime("22:05");
$backLateEveningOffStamp = $backLateEveningOff->getTimeStamp();


$sunrise = date_sunrise ( $todayStamp, SUNFUNCS_RET_TIMESTAMP, $lattitude, $longitude, $zenith, $offset );
$sunset = date_sunset ( $todayStamp, SUNFUNCS_RET_TIMESTAMP, $lattitude, $longitude, $zenith, $offset );

// Calculate 1/2 hour before sunset and 1/2 after sunrise

$beforeSunset = $sunset - 1800;
$afterSunrise = $sunrise + 1800;

echo "Morning Lights on at ",strftime ("%b %e,%G %I:%M %p",$morningOnStamp),"\n"; 
echo "Morning Lights off at ",strftime ("%b %e,%G %I:%M %p", $afterSunrise),"\n";
echo "Evening lights on at ", strftime ("%b %e,%G %I:%M %p", $beforeSunset),"\n";


// Turn the back outside light on at 5:00 am if it is at least 15 minutes until we turn the light off

echo ($afterSunrise - $morningOnStamp),"\n";

if ( ($afterSunrise - $morningOnStamp) > 900 ) {
	echo "We have time to turn the rear outside light on in the morning.\n";
	$execstr = exec('echo "x10Serial -d A12" | at 5:00');
        $execstr = exec('echo "x10Serial -d A13" | at '.strftime('%H:%M', $afterSunrise));	
}

// Turn on the outside ligths in the evening
// On 1/2 hour before sunset if there is at least 15 minutes until we turn them off
// Early off on sunday through thursday, Late off on friday and saturday

// Start with front light
if (($frontEveningOffStamp - $beforeSunset) >900 ) {
	echo "We have time to turn the front outside light on at night.\n";
	if ( $frontEveningOnStamp < $beforeSunset ) {
		echo "Turn front light on at sunset\n";
		$execstr = exec('echo "x10Serial -d A22" | at '.strftime('%H:%M', $beforeSunset));
		$execstr = exec('echo "x10Serial -d A23" | at 20:00');
	} else {
		echo "Turn front light on at 6:00\n";
		$execstr = exec('echo "x10Serial -d A22" | at 18:00');
		$execstr = exec('exho "x10Serial -d A23" | at 20:00');
	}
}

// Rear light early day
if ($todayDay['wday'] < 5) {
	// Sunday through Thursday
	if (($backEarlyEveningOffStamp - $beforeSunset) > 900) {
		echo "We can turn on the back outside light Early at night.\n";
		$execstr = exec('echo "x10Serial -d A12 A72 A62" | at '.strftime('%H:%M', $beforeSunset));
		$execstr = exec('echo "x10Serial -d A13 A73 A63" | at 21:05');
	}	
} else {
	// Friday and Saturday
	if (($backLateEveningOffStamp - $beforeSunset) > 900) {
		echo "We can turn on the back outside light Late at night.\n";
		$execstr = exec('echo "x10Serial -d A12 A72 A62" | at '.strftime('%H:%M', $beforeSunset));
		$execstr = exec('echo "x10Serial -d A13 A73 A63" | at 22:05');
	}
}

echo $todayDay["wday"],"\n";

?>
