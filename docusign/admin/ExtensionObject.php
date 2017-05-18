<?php
/**
 *
 * Zenbership Membership Software
 * Copyright (C) 2013-2016 Castlamp, LLC
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Castlamp
 * @link        http://www.castlamp.com/
 * @link        http://www.zenbership.com/
 * @copyright   (c) 2013-2016 Castlamp
 * @license     http://www.gnu.org/licenses/gpl-3.0.en.html
 * @project     Zenbership Membership Software
 */

class ExtensionObject extends pluginAdminTools {


    /**
     * @return string
     */
    public function getPluginId() { return 'docusign'; }


	/**
	 * Add
	 *
	 * @param 	array 	$data
	 *
	 * @return 	array
	 */
	public function add($data, $name = '', $email = '')
	{
        $rules = array(
            'template_id' => array('required'),
            'due_date' => array('required','datetime','check_future'),
            'member_id' => array('only_if:user_type:member','required'),
            'contact_id' => array('only_if:user_type:contact','required'),
        );
        $validate = $this->validate($data, $rules, 'new');
        if ($validate->error_found == '1') {
            $this->throwAjaxError($validate->plain_english);
        }

        $final_data = $validate->final_data;

        if (! empty($name) && ! empty($email)) {

        } else {
            if (! empty($final_data['contact_id'])) {
                $type = 'contact';
                $uid = $final_data['contact_id'];
                $contact = new contact;
                $user_name = $contact->get_contact($uid);
                $name = $user_name['data']['first_name'] . ' ' . $user_name['data']['last_name'];
                $email = $user_name['data']['email'];
            } else {
                $type = 'member';
                $uid = $final_data['member_id'];
                $user = new user;
                $user_name = $user->get_user($uid);
                $name = $user_name['data']['first_name'] . ' ' . $user_name['data']['last_name'];
                $email = $user_name['data']['email'];
            }
        }

        $envelop = $this->plugin->load('sendTemplate');

        $returnPackage = $envelop->sendEnvelop($final_data['template_id'], $email, $name);

        if (! empty($returnPackage['errorCode'])) {
            echo "0+++Docusign Error: " . $returnPackage['message'];
            exit;
        }

        $save_data = array(
            'date' => current_date(),
            'url' => $returnPackage['uri'],
            'user_type' => (! empty($type)) ? $type : '',
            'user_id' => (! empty($uid)) ? $uid : '',
            'template_id' => $final_data['template_id'],
            'envelop_id' => $returnPackage['envelopeId'],
            'due_date' => $final_data['due_date'],
            'confirmed' => '0',
            'sent' => 'Sent',
        );

        $add = $this->save('zen_plugin_docusign', $save_data);

		return array(
			'error' => false,
            'id' => $add,
			'message' => 'Envelop Sent!',
            'commands' => array(
                'close_popup' => '1',
            ),
		);
	}


    /**
     * @param $id
     *
     * @return mixed
     */
    public function getFromEnvelopId($id)
    {
        return $this->get_array("
            SELECT *
            FROM `zen_plugin_docusign`
            WHERE `envelop_id`='" . $this->mysql_clean($id) . "'
            LIMIT 1
        ");
    }


    /**
     * @param $id
     */
    public function get($id)
    {
        return $this->get_array("
            SELECT *
            FROM `zen_plugin_docusign`
            WHERE `id`='" . $this->mysql_clean($id) . "'
            LIMIT 1
        ");
    }

	/**
	 * Edit
	 *
	 * @param 	array 	$data
	 *
	 * @return 	array
	 */
	public function edit($data)
	{
		$fields = $this->updateFields($data);

		$edit = $this->update("
			UPDATE `zen_plugin_docusign`
			SET " . $fields . "
			WHERE id='" . $this->mysql_cleans($data['id']) . "'
			LIMIT 1
		");

		return array(
			'error' => false,
			'message' => 'Edited item.',
		);
	}


    public function getTriggers()
    {
        $triggers = array();
        $get = $this->run_query("
            SELECT *
            FROM `zen_plugin_docusign_triggers`
        ");
        while ($row = $get->fetch()) {
            $triggers[] = $row;
        }
        return $triggers;
    }


    public function triggers($data)
    {
        $rules = array(
            'template_id' => array('required'),
            'trigger_type' => array('required'),
            'form_id' => array('only_if:trigger_type:form','required'),
            'product_id' => array('only_if:trigger_type:product','required'),
        );
        $validate = $this->validate($data, $rules, 'new');
        if ($validate->error_found == '1') {
            $this->throwAjaxError($validate->plain_english);
        }

        $final_data = $validate->final_data;

        if ($data['trigger_type'] == 'form') {
            $trigger_id = $data['form_id'];
        } else {
            $trigger_id = $data['product_id'];
        }

        $save_data = array(
            'trigger' => $data['trigger_type'],
            'trigger_id' => $trigger_id,
            'template_id' => $data['template_id'],
        );

        $add = $this->save('zen_plugin_docusign_triggers', $save_data);

        return array(
            'error' => false,
            'id' => $add,
            'message' => 'Trigger Created.',
            'commands' => array(
                'redirect_popup' => array(
                    'page' => 'docusign-triggers',
                ),
            ),
        );
    }

    public function delete_trigger($data)
    {
        $q = $this->delete("
            DELETE FROM `zen_plugin_docusign_triggers`
            WHERE `id`='" . $this->mysql_clean($data['id']) . "'
            LIMIT 1
        ");

        return array(
            'error' => false,
            'id' => $data['id'],
            'message' => 'Item Deleted',
            'commands' => array(
                'add_class' => array(
                    'id'    => 'td-cell-' . $_POST['id'],
                    'class' => 'been_deleted',
                ),
            ),
        );
    }

}