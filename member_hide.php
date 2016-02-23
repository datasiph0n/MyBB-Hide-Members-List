<?php
/*
# Hide Member List
# Originally for siph0n forum.
# Coded by: sn
*/

if(!defined("IN_MYBB"))
{
	die("No direct access allowed.");
}

$plugins->add_hook('memberlist_start', 'member_hide_main');


function member_hide_info()
{
	return array(
		"name" 			=> "Hide Member List",
		"description" 	=> "Hides Member List from specific usergroups.",
		"website"		=> "http://siph0n.in",
		"author"		=> "sn",
		"authorsite"	=> "http://siph0n.in",
		"version"		=> "1.0",
		"guid"			=> "",
		"codename"		=> str_replace('.php', '', basename(__FILE__)),
		"compatibility"	=> "18*"
	);
}

function member_hide_main()
{	
	global $mybb, $templates;
	if($mybb->settings['member_hide_enable'] == 1)
	{
		$u_groupid = $mybb->user['usergroup'];
		$deny_group = $mybb->settings['member_hide_groups'];
		if(strpos($deny_group, ",") !== FALSE) {
			$deny_group = explode(",", $array);
		} else {
			$deny_group = array($mybb->settings['member_hide_groups']);
		}
		if(in_array($u_groupid, $deny_group)) {
			error("You do not have permission to access this page. This is for the following reason:<ol></br><li>Your account is in a restricted usergroup.</li></ol></br>You are currently logged in with the username: 'admin' ");
		}
	}
}

function member_hide_install() // Called when "Install" button is pressed
{
	global $db, $mybb, $templates;
	$settings_group = array(
    	'name' => 'member_hide',
    	'title' => 'Member List Hide',
    	'description' => 'This is my plugin and it does some things',
    	'disporder' => 5, // The order your setting group will display
    	'isdefault' => 0
	);
	$gid = $db->insert_query("settinggroups", $settings_group);
	$setting_array = array(
    	'member_hide_enable' => array(
        	'title' => 'Member List Hide:',
        	'description' => 'Do we want to activate this plugin?:',
        	'optionscode' => 'yesno',
        	'value' => '1', // Default
        	'disporder' => 1
    	),
	    'member_hide_groups' => array(
	        'title' => 'Hide From Group:',
	        'description' => 'Please enter the groups to block followed by a ",":',
	        'optionscode' => "text",
	        'value' => "",
	        'disporder' => 2
	    ),
	);
	foreach($setting_array as $name => $setting)
	{
    	$setting['name'] = $name;
    	$setting['gid'] = $gid;
	    $db->insert_query('settings', $setting);
	}
	rebuild_settings();
}

function member_hide_is_installed()
{
	global $mybb;
	if($mybb->settings["member_hide_enable"])
	{
		return true;
	}
	return false;
}

function member_hide_uninstall()
{
	global $db;

	$db->delete_query('settings', "name IN ('member_hide_enable','member_hide_groups')");
	$db->delete_query('settinggroups', "name = 'member_hide'");
	rebuild_settings();
}

function member_hide_activate()
{

}

function member_hide_deactivate()
{

}

