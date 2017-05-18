<?php

/**
 *
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

class sendTemplate extends pluginLoader {

    private $api_url = 'https://demo.docusign.net/restapi/v2';

    private $base_url = '';

    private $account_id = '';

    private $template_id;

    private $headers;


    public function setHeaders()
    {
        $this->headers = "<DocuSignCredentials>
            <Username>" . $this->plugin->options['api_username'] . "</Username>
            <Password>" . $this->plugin->options['api_password'] . "</Password>
            <IntegratorKey>" . $this->plugin->options['api_integrator_key'] . "</IntegratorKey>
        </DocuSignCredentials>";
    }


    public function setTemplate($id)
    {
        $this->template_id = $id;

        return $this;
    }


    public function sendEnvelop($templateId, $recipientEmail, $recipientName)
    {
        $base_url = $this->getBaseUrl();

        $url = $base_url . '/envelopes';

        $data = array(
            "accountId" => $this->account_id,
            "emailSubject" => "DocuSign Signature Request From " . $this->get_option('company_name'),
            "templateId" => $templateId,
            "templateRoles" => array(
                array(
                    "email" => $recipientEmail,
                    "name" => $recipientName,
                    "roleName" => 'Signer',
                ),
            ),
            "status" => "sent",
            "EventNotification" => array(
                'url' => PP_URL . '/custom/plugins/docusign/callback.php',
                'loggingEnabled' => 'true',
                'requireAcknowledgment' => 'true',
                'useSoapInterface' => 'false',
                'includeCertificateWithSoap' => 'false',
                'includeDocuments' => 'true',
                'includeEnvelopeVoidReason' => 'true',
                'includeTimeZone' => 'true',
                'EnvelopeEvents' => array(
                    array('envelopeEventStatusCode' => 'completed'),
                    array('envelopeEventStatusCode' => 'declined'),
                    array('envelopeEventStatusCode' => 'voided'),
                ),
            ),
        );

        $response = $this->call($url, $data);

        return $response;
    }


    public function getBaseUrl()
    {
        $this->setHeaders();

        $response = $this->call($this->api_url . '/login_information');

        $this->base_url = $response["loginAccounts"][0]["baseUrl"];
        $this->account_id = $response["loginAccounts"][0]["accountId"];

        return $this->base_url;
    }


    public function call($url, array $data = array())
    {
        $json = (! empty($data)) ? json_encode($data) : '';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "X-DocuSign-Authentication: " . $this->headers,
            "Content-type: application/json",
            "Content-Length: " . strlen($json)
        ));
        if (! empty($json)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        }
        $json_response = curl_exec($curl);
        curl_close($curl);

        return json_decode($json_response, true);
    }

}