<?php

class sendSms extends pluginLoader {

    protected $plugin;

	protected $apiUrl = 'https://rest.nexmo.com/sms/json';
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
     *
     * @return string   json reply
     */
	public function send()
	{
        $sending = array(
            'api_key' => $this->apiSid,
            'api_secret' => $this->apiAuthToken,
            'from' => $this->providerPhone,
            'to' => $this->cell,
            'text' => $this->msg,
        );

        $reply = $this->curl_call(
    		$this->apiUrl . '?' . http_build_query($sending)
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
        if ($json['messages']['0']['status'] != '0') {
            return array(
                'error' => true,
                'code' => $json['messages']['0']['status'],
                'message' => $json['messages']['0']['error-text'],
            );
        } else {
            return array(
                'id' => $json['messages']['0']['message-id'],
                'code' => '',
                'message' => '',
                'raw' => $json,
            );
        }
    }

}