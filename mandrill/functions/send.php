<?php

class send extends pluginLoader {

    protected $plugin;

	protected $apiUrl = 'https://mandrillapp.com/api/1.0';
	protected $apiSid;
	protected $apiAuthToken;
    protected $apiDomain;

    protected $tag, $campaign;

    protected $tracking, $linkTracking;

    protected $to, $from, $cc, $bcc, $subject, $html_message, $text_message;
    protected $attachments = array();

    protected $error, $errorCode, $errorMessage;


    public function __construct(plugin $plugin)
    {
        $this->plugin = $plugin;
    }

	/**
	 * Keys for the API.
	 *
	 * @param 	array 	$keys 	Will contain 'key' and 'token'.
	 *							Called from sms class.
     *
     * @return  $this
	 */
	public function setKeys(array $keys)
	{
		$this->apiSid = $keys['key'];

        $this->apiDomain = (! empty($keys['domain'])) ? $keys['domain'] : '';

		$this->apiAuthToken = (! empty($keys['token'])) ? $keys['token'] : '';

        return $this;
	}

    /**
     * @param mixed $tracking
     *
     * @return  $this
     */
    public function setTracking($tracking)
    {
        $this->tracking = $tracking;

        return $this;
    }

    /**
     * @param mixed $linkTracking
     *
     * @return  $this
     */
    public function setLinkTracking($linkTracking)
    {
        $this->linkTracking = $linkTracking;

        return $this;
    }



    /**
     * @param mixed $campaign
     *
     * @return  $this
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;

        return $this;
    }


    /**
     * @param mixed $tag
     *
     * @return  $this
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @param mixed $to
     *
     * @return  $this
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param mixed $from
     *
     * @return  $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param mixed $cc
     *
     * @return  $this
     */
    public function setCc($cc)
    {
        $this->cc = $cc;

        return $this;
    }

    /**
     * @param mixed $bcc
     *
     * @return  $this
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;

        return $this;
    }

    /**
     * @param mixed $subject
     *
     * @return  $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @param mixed $html_message
     *
     * @return  $this
     */
    public function setHtmlMessage($html_message)
    {
        $this->html_message = $html_message;

        return $this;
    }

    /**
     * @param mixed $text_message
     *
     * @return  $this
     */
    public function setTextMessage($text_message)
    {
        $this->text_message = $text_message;

        return $this;
    }

    /**
     * @param string $attachment
     *
     * @return  $this
     */
    public function setAttachments($attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }



    /**
     * Actually sends the SMS.
     *
     * @return string   json reply
     */
	public function send()
	{
        $toArray = array();

        if (! empty($this->bcc)) {
            $bcc = explode(',', $this->bcc);
            foreach ($bcc as $go) {
                $toArray[] = array(
                    'email' => $go,
                    'name' => '',
                    'type' => 'bcc',
                );
            }
        }

        if (! empty($this->cc)) {
            $cc = explode(',', $this->cc);
            foreach ($cc as $go) {
                $toArray[] = array(
                    'email' => $go,
                    'name' => '',
                    'type' => 'cc',
                );
            }
        }

        $toArray[] = array(
            'email' => $this->to,
            'name' => '',
            'type' => 'to',
        );

        if (strpos($this->from, "<") !== false) {
            $exp = explode('<', $this->from);
            $fromemail = rtrim($exp['1'], '>');
            $fromname = trim($exp['0']);
        } else {
            $fromemail = $this->from;
            $fromname = '';
        }

        $sending = array(
            'to' => $toArray,
            'from_email' => $fromemail,
            'from_name' => $fromname,
            'subject' => $this->subject,
        );

        if (! empty($this->text_message)) {
            $sending['text'] = $this->text_message;
        }

        if (! empty($this->html_message)) {
            $sending['html'] = $this->html_message;
        }

        if (! empty($this->tag)) {
            $sending['tags'] = explode(',', $this->tag);
        }

        if (! empty($this->tracking)) {
            $sending['track_opens'] = '1';
        }

        if (! empty($this->linkTracking)) {
            $sending['track_clicks'] = '1';
        }

        $attachments = array();
        if (! empty($this->attachments)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            foreach ($this->attachments as $item) {
                $type = finfo_file($finfo, $item);
                $fname = explode('/', $item);
                $filename = array_pop($fname);
                $attachments[] = array(
                    "type" => $type,
                    "name" => $filename,
                    "content" => base64_encode(file_get_contents($item)),
                );
            }
            finfo_close($finfo);
            $sending['attachments'] = $attachments;
        }

        $final = array(
            'key' => $this->apiSid,
            'message' => $sending,
        );

        $reply = $this->curl_call(
    		$this->apiUrl . '/messages/send.json',
            $final,
            '2'
    	);

        $json = json_decode($reply, true);

        return $this->processReply($json);
	}

    /**
     * Process the reply and speak to SMS.
     *
     * @param array $json
     *
     * @return array
     */
    protected function processReply(array $json)
    {
        if (! empty($json['status']) && ($json['status'] == 'error' || $json['status'] == 'rejected')) {
            return array(
                'error' => true,
                'code' => $json['code'],
                'message' => $json['message'],
            );
        } else {
            return array(
                'id' => $json['0']['id'],
                'code' => '',
                'message' => $json['0']['status'],
                'raw' => $json,
            );
        }
    }

}