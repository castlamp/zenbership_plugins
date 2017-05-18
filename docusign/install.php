<?php

/**
 * Establish the plugin's data.
 * id: Plugin ID.
 * name: Plugin's name.
 * description: Plugin description.
 */
$plugin = array(
    'id'                => 'docusign',
    'name'              => 'DocuSign',
    'description'       => 'Integrates DocuSign with the Zenbership.',
    'author'            => 'Castlamp',
    'author_url'        => 'https://www.castlamp.com/',
    'author_twitter'    => 'castlamp',
    'version'           => '1.0',
    'app_creator'       => 'Docusign',
    'app_creator_url'   => 'http://www.docusign.com/',
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
    'id'            => 'api_account_id',
    'name'          => 'API Account ID',
    'value'         => '',
    'description'   => '',
    'type'          => 'text',
    'width'         => '300',
    'maxlength'     => '',
    'options'       => '',
);
$options[] = array(
    'id'            => 'api_username',
    'name'          => 'API Username',
    'value'         => '',
    'description'   => '',
    'type'          => 'text',
    'width'         => '300',
    'maxlength'     => '',
    'options'       => '',
);
$options[] = array(
    'id'            => 'api_password',
    'name'          => 'API Password',
    'value'         => '',
    'description'   => '',
    'type'          => 'text',
    'width'         => '300',
    'maxlength'     => '',
    'options'       => '',
);
$options[] = array(
    'id'            => 'api_integrator_key',
    'name'          => 'API Integrator Key',
    'value'         => '',
    'description'   => '',
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

// New Hooks
$hooks[] = array(
    'trigger'           => 'event_add_registrant',
    'specific_trigger'  => '',
    'type'              => '1',
    'data'              => '%path%/hooks/triggers.php',
    'when'              => '2',
);
$hooks[] = array(
    'trigger'           => 'contact_create',
    'specific_trigger'  => '',
    'type'              => '1',
    'data'              => '%path%/hooks/triggers.php',
    'when'              => '2',
);
$hooks[] = array(
    'trigger'           => 'member_create',
    'specific_trigger'  => '',
    'type'              => '1',
    'data'              => '%path%/hooks/triggers.php',
    'when'              => '2',
);
$hooks[] = array(
    'trigger'           => 'transaction',
    'specific_trigger'  => '',
    'type'              => '1',
    'data'              => '%path%/hooks/triggers.php',
    'when'              => '2',
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

$tables = array();

$tables['zen_plugin_docusign'] = "
    CREATE TABLE `zen_plugin_docusign` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `envelop_id` varchar(50) DEFAULT NULL,
      `date` datetime DEFAULT NULL,
      `user_id` varchar(40) DEFAULT NULL,
      `user_type` varchar(10) DEFAULT NULL,
      `confirmed` tinyint(1) DEFAULT NULL,
      `date_confirmed` datetime DEFAULT NULL,
      `template_id` varchar(50) DEFAULT NULL,
      `due_date` datetime DEFAULT NULL,
      `url` varchar(255) DEFAULT NULL,
      `status` varchar(20) DEFAULT NULL,
      `storage_folder` varchar(150) DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `envelop_id` (`envelop_id`),
      KEY `user_id` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

$tables['zen_plugin_docusign_triggers'] = "
    CREATE TABLE `zen_plugin_docusign_triggers` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `trigger` varchar(30) DEFAULT NULL,
      `trigger_id` varchar(30) DEFAULT NULL,
      `template_id` varchar(50) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
