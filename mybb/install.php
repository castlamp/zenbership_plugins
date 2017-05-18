<?php

/**
 * Establish the plugin's data.
 * id: Plugin ID.
 * name: Plugin's name.
 * description: Plugin description.
 */
$plugin = array(
    'id'                => 'mybb',
    'name'              => 'MyBB',
    'description'       => 'Integrates MyBB with the Zenbership login and registration.',
    'author'            => 'Castlamp',
    'author_url'        => 'https://www.castlamp.com/',
    'author_twitter'    => 'castlamp',
    'version'           => '1.0',
    'app_creator'       => 'MyBB',
    'app_creator_url'   => 'http://www.mybb.com',
);

/**
 * Options array. Create an array within the $options
 * array for each widget option.
 *
 * display: Display name
 * value: Value
 * description: Description
 * type: 'text','select','radio','checkbox','timeframe','special','file_size','textarea'
 * width: Width of field
 * maxlength:
 * options: array of options for select or radio, separated by vertical bar "|", or for special, "list:[list_type]"
 */
$options = array();

// New Option
$options[] = array(
    'id'            => 'new_user_group',
    'name'          => 'New User Group ID',
    'value'         => '2',
    'description'   => 'What group ID should new users be added into in MyBB?',
    'type'          => 'text',
    'width'         => '300',
    'maxlength'     => '',
    'options'       => '',
);

$options[] = array(
    'id'            => 'content_id',
    'name'          => 'Zenbership Content ID',
    'value'         => '',
    'description'   => 'What is the ID number of the content that secures the forum? Members will need to have access to this content to access the forum.',
    'type'          => 'text',
    'width'         => '300',
    'maxlength'     => '',
    'options'       => '',
);

/**
 * Hooks array.
 * trigger:
 * specific_trigger:
 * type: 1 = PHP Include, 2 = email, 3 = MySQL Query, 4 = Function name
 * data:    PHP: path to file.
 *          Email: E-mail array.
 *          MySQL Query: Array of commands to run.
 *          Function: Array of function names to run.
 * when: 1 = before action, 2 = after action
 *
 * Use %path% in the "data" field to let Zenbership
 * determine the correct path.
 */

$hooks = array();

// New Hook
$hooks[] = array(
    'name'              => 'MyBB Member Login',
    'trigger'           => 'login',
    'specific_trigger'  => '',
    'type'              => '1',
    'data'              => '%path%/hooks/login.php',
    'when'              => '2',
);
$hooks[] = array(
    'name'              => 'MyBB Member Logout',
    'trigger'           => 'logout',
    'specific_trigger'  => '',
    'type'              => '1',
    'data'              => '%path%/hooks/logout.php',
    'when'              => '2',
);
$hooks[] = array(
    'name'              => 'MyBB Member Created',
    'trigger'           => 'member_create',
    'specific_trigger'  => '',
    'type'              => '1',
    'data'              => '%path%/hooks/register.php',
    'when'              => '2',
);
$hooks[] = array(
    'name'              => 'MyBB Member Status Changed',
    'trigger'           => 'member_status_change',
    'specific_trigger'  => '',
    'type'              => '1',
    'data'              => '%path%/hooks/status.php',
    'when'              => '2',
);
$hooks[] = array(
    'name'              => 'MyBB Member Edited',
    'trigger'           => 'member_edit',
    'specific_trigger'  => '',
    'type'              => '1',
    'data'              => '%path%/hooks/edit.php',
    'when'              => '2',
);
$hooks[] = array(
    'name'              => 'MyBB Member Deleted',
    'trigger'           => 'member_delete',
    'specific_trigger'  => '',
    'type'              => '1',
    'data'              => '%path%/hooks/delete.php',
    'when'              => '1',
);
$hooks[] = array(
    'name'              => 'MyBB Member Grant Access to Content',
    'trigger'           => 'content_access_add',
    'specific_trigger'  => '',
    'type'              => '1',
    'data'              => '%path%/hooks/content_access.php',
    'when'              => '2',
);
$hooks[] = array(
    'name'              => 'MyBB Member Content Access Removed',
    'trigger'           => 'content_access_lost',
    'specific_trigger'  => '',
    'type'              => '1',
    'data'              => '%path%/hooks/content_removed.php',
    'when'              => '2',
    'order'             => '9',
);

/**
 * Activity Feed
 * Creates a notice in the activity
 * feed when an action takes place
 * in the plugin.
 */

$feed = array();


/**
 * Secure Content
 * Secures content in a specific folder.
 */

$folders = array();

// New Folder
/*
$options[] = array(
    'name'          => 'Member Forums',
    'path'          => '/path/to/forums',
    'url'           => '/forums',
);
*/


/**
 * -------------------------------------------------------------------------
 * Routes
 *
 * Controls potential routing requirements.
 * For example, if you are creating a directory
 * application, you would want to have a "profile"
 * page. You would create a route below as follows:
 *    'path' => '/directory/{username}',
 *    'resolve' => 'myPluginFile.php',
 * This would redirect all requests typed in as
 * http://www.yoursite.com/zenbership_folder/directory/johndoe
 * to the "myPluginFile.php" file, which needs to be inside the
 * plugin's folder. The {username} component would be part of the
 * request as a GET element.
 */

$routes = array();

/*
$routes[] = array(
    'path'          => '/directory/(username)',
    'resolve'       => 'listing.php',
);
$routes[] = array(
    'path'          => '/directory',
    'resolve'       => 'list.php',
);
*/


/**
 * -------------------------------------------------------------------------
 * Anything Else
 */

