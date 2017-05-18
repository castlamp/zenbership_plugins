<?php

class sendSms extends pluginLoader {

    protected $plugin;

	protected $apiUrl = 'https://api.plivo.com/v1';
	protected $apiSid;
	protected $apiAuthToken;
	protected $cell;
	protected $msg;
	protected $media;
	protected $providerPhone;

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
		$this->apiAuthToken = $keys['token'];

        return $this;
	}

    /**
     * Assumes the number is properly formatted already by sms class.
     * +19995550987
     *
     * @param   string $cell
     *
     * @return $this
     */
	public function setCell($cell)
	{
		$this->cell = $cell;

		return $this;
	}

    /**
     * Assumes the number is properly formatted already in the options.
     * +19995550987
     *
     * @param   string $number
     *
     * @return $this
     */
	public function setProviderPhoneNumber($number)
	{
		$this->providerPhone = $number;

		return $this;
	}

    /**
     * The formatted outgoing message.
     *
     * @param   string  $msg
     *
     * @return $this
     */
	public function setMsg($msg)
	{
		$this->msg = $msg;
		
		return $this;
	}

    /**
     * The SMS media URL, if any.
     *
     * @param   string  $mediaUrl
     *
     * @return $this
     */
	public function setMedia($mediaUrl)
	{
		$this->media = $mediaUrl;
		
		return $this;
	}

    /**
     * Actually sends the SMS.
     * https://www.plivo.com/docs/api/message/
     *
     * @return string   json reply
     */
	public function send()
	{
        $sending = array(
            'src' => $this->providerPhone,
            'dst' => $this->cell,
            'text' => $this->msg,
            'type' => 'sms',
        );

        $reply = $this->curl_call(
    		$this->apiUrl . '/Account/' . $this->apiSid . '/Message/',
            $sending,
            '0',
            $this->apiSid . ':' . $this->apiAuthToken
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
        if (! empty($json['error'])) {
            return array(
                'error' => true,
                'code' => 'X',
                'message' => $json['error'],
                'raw' => $json,
            );
        } else {
            return array(
                'id' => $json['message_uuid']['0'],
                'code' => '',
                'message' => '',
                'raw' => $json,
            );
        }
    }

}