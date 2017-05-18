<?php

class send extends pluginLoader {

    protected $plugin;

	protected $apiUrl = 'https://api.mailgun.net/v3';
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

        $this->apiDomain = $keys['domain'];

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
        $sending = array(
            'to' => $this->to,
            'from' => $this->from,
            'subject' => $this->subject,
        );

        if (! empty($this->text_message)) {
            $sending['text'] = $this->text_message;
        }

        if (! empty($this->html_message)) {
            $sending['html'] = $this->html_message;
        }

        if (! empty($this->bcc)) {
            $sending['bcc'] = $this->bcc;
        }

        if (! empty($this->cc)) {
            $sending['cc'] = $this->cc;
        }

        if (! empty($this->tag)) {
            $sending['o:tag'] = $this->tag;
        }

        if (! empty($this->campaing)) {
            $sending['o:campaign'] = $this->campaign;
        }

        if (! empty($this->tracking)) {
            $sending['o:tracking'] = 'yes';
            $sending['o:tracking-opens'] = 'yes';
        }

        if (! empty($this->linkTracking)) {
            $sending['o:tracking-clicks'] = 'yes';
        }

        $method = 0;
        if (! empty($this->attachments)) {
            $up = 0;
            foreach ($this->attachments as $item) {
                $name = 'attachment[' . $up++ . ']';
                $sending[$name] = '@' . $item;
            }
            $method = 3;
        }

        $reply = $this->curl_call(
    		$this->apiUrl . '/' . $this->apiDomain . '/messages',
            $sending,
            $method,
            'api:' . $this->apiSid
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
        if (empty($json['id'])) {
            return array(
                'error' => true,
                'code' => 'X',
                'message' => $json['message'],
            );
        } else {
            return array(
                'id' => $json['id'],
                'code' => '',
                'message' => $json['message'],
                'raw' => $json,
            );
        }
    }

}