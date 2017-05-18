<?php

/**
 * 
 * 
 * @author  j-belelieu
 * @date    3/20/16
 */

class bbsync extends pluginLoader {

    /**
     * @param array $data
     * @param int   $forceUserGroup
     *
     * @return string
     */
    public function create(array $data, $forceUserGroup = '')
    {
        if (empty($data['password'])) {
            $data['password'] = generate_id();
        }

        $user_group = (! empty($forceUserGroup)) ? $forceUserGroup : $this->plugin->options['new_user_group'];

        $loginkey = generate_id('random', 50);

        $salt = $this->salt();

        $salt_password = $this->encode_password($data['password'], $salt);

        $id = $this->insert("
            INSERT INTO `" . $this->plugin->global['mysql_prefix'] . "users` (
                `username`,
                `password`,
                `salt`,
                `loginkey`,
                `email`,
                `usergroup`,
                `regdate`
            ) VALUES (
                '" . $this->mysql_clean($data['username']) . "',
                '" . $this->mysql_clean($salt_password) . "',
                '" . $this->mysql_clean($salt) . "',
                '" . $this->mysql_clean($loginkey) . "',
                '" . $this->mysql_clean($data['email']) . "',
                '" . $this->mysql_clean($user_group) . "',
                '" . time() . "'
            )
        ");

        return $id;
    }


    public function get($id)
    {
        return $this->get_array("
            SELECT *
            FROM `" . $this->plugin->global['mysql_prefix'] . "users`
            WHERE `uid`='" . $this->mysql_clean($id) . "'
            LIMIT 1
        ");
    }


    public function getFromUsername($username)
    {
        return $this->get_array("
            SELECT *
            FROM `" . $this->plugin->global['mysql_prefix'] . "users`
            WHERE `username`='" . $this->mysql_clean($username) . "'
            LIMIT 1
        ");
    }


    public function salt()
    {
        return generate_id('random', 8);
    }


    public function encode_password($password, $salt)
    {
        return md5(md5($salt) . md5($password));
    }


    public function sessionId()
    {
        return generate_id('random', 28);
    }


    /**
     * Generally 1 = guest / 4 = user / 7 = banned
     *
     * @param $id
     * @param string $group
     *
     * @return string
     */
    public function changeStatus($id, $group = '1')
    {
        return $this->update("
            UPDATE `" . $this->plugin->global['mysql_prefix'] . "users`
            SET `usergroup`='$group'
            WHERE `uid`='" . $this->mysql_clean($id) . "'
            LIMIT 1
        ");
    }


    public function startSession($uid)
    {
        $get = $this->get($uid);

        $sid = $this->sessionId();

        $ip_address = get_ip();

        if ($ip_address == '127.0.0.1') $ip_address = '';

        $add = $this->insert("
            INSERT INTO `" . $this->plugin->global['mysql_prefix'] . "sessions` (
                `sid`,
                `uid`,
                `ip`,
                `time`,
                `location`,
                `useragent`
            ) VALUES (
                '" . $this->mysql_clean($sid) . "',
                '" . $this->mysql_clean($uid) . "',
                '" . $this->mysql_clean($ip_address) . "',
                '" . time() . "',
                '',
                '" . $this->mysql_clean($_SERVER['HTTP_USER_AGENT']) . "'
            )
        ");

        $this->create_cookie('mybbuser', $uid . '_' . $get['loginkey'], 'none', $this->plugin->global['cookie_domain']);

        $this->create_cookie('sid', $sid, 'none', $this->plugin->global['cookie_domain']);

        return $sid;
    }


    public function killSession($uid)
    {
        $this->delete("
            DELETE FROM `" . $this->plugin->global['mysql_prefix'] . "sessions`
            WHERE `uid`='" . $this->mysql_clean($uid) . "'
        ");

        $this->delete_cookie('mybbuser');

        $this->delete_cookie('sid');
    }


    public function edit($id, array $data)
    {
        $update = '';
        foreach ($data as $key => $value) {
            if ($key == 'password') {
                $salt = $this->salt();
                $value = $this->encode_password($value, $salt);
                $data['salt'] = $salt;
            }
            $update .= ",`" . $this->mysql_cleans($key) . "`='" . $this->mysql_clean($value) . "'";
        }
        $update = ltrim($update, ',');

        $this->update("
            UPDATE `" . $this->plugin->global['mysql_prefix'] . "users`
            SET $update
            WHERE `uid`='" . $this->mysql_clean($id) . "'
            LIMIT 1
        ");
    }


    public function delete_user($uid)
    {
        return $this->run_query("
            DELETE FROM `" . $this->plugin->global['mysql_prefix'] . "users`
            WHERE `uid`='" . $this->mysql_clean($uid) . "'
            LIMIT 1
        ");
    }

}