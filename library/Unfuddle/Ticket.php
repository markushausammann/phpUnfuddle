<?php

namespace Unfuddle;

/**
 * Class Ticket
 * @package Unfuddle
 */
class Ticket extends UnfuddleAbstract
{
	protected $urlPart = 'projects/%s/tickets.xml';
    protected $requestUri;
    protected $requestBody;

    protected $assigneeID;
    protected $descriptionFormat = 'markdown';
    protected $status = 'new';
    protected $priority = 3;
    protected $summary;
    protected $description = '';

	/**
	 * Ticket constructor.
	 *
	 * @param $connection
	 * @param $projectID
	 */
    public function __construct($connection, $projectID)
    {
        parent::__construct($connection);
        $this->setRequestUri($projectID);
    }

	/**
	 * @param        $assigneeID
	 * @param        $summary
	 * @param string $description
	 *
	 * @return $this
	 */
    public function setup($assigneeID, $summary, $description = '')
    {
        $this->setAssigneeID($assigneeID);
        $this->setSummary($summary);
        $this->setDescription($description);

        $this->requestBody = sprintf(
            '<ticket>
                <assignee-id type="integer">%s</assignee-id>
                <description-format>%s</description-format>
                <status>%s</status>
                <priority>%s</priority>
                <status>%s</status>
                <summary>%s</summary>
                <description>%s</description>
            </ticket>',
            $this->assigneeID,
            $this->descriptionFormat,
            $this->status,
            $this->priority,
            $this->status,
            $this->XMLStringFormat($this->summary),
            $this->XMLStringFormat($this->description));

        return $this;
    }

	/**
	 * @return bool|string
	 * @throws \Exception
	 */
    public function create()
    {
        $requestHeaders = $this->getHeaders(strlen($this->requestBody));
        $curlHandle = curl_init();

        curl_setopt($curlHandle, CURLOPT_URL, $this->requestUri);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_HEADER, true);
        curl_setopt($curlHandle, CURLOPT_USERPWD, $this->connection->getUsername() . ':' . $this->connection->getPassword());
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST,'POST');
        curl_setopt($curlHandle, CURLOPT_POST, true);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $this->requestBody);

        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $requestHeaders);

        // Do the POST and then close the session
        $response = curl_exec($curlHandle);
        curl_close($curlHandle);

        // Check for bad response and throw exception
        $this->testHttpStatusCode($response);

        // Otherwise get ticketID and return it
        $responseHeaders = $this->httpParseHeaders($response);
        $ticketApiUrl = $responseHeaders['Location'];

	    return substr($ticketApiUrl, strrpos($ticketApiUrl, '/') + 1);
    }

	/**
	 * @param $projectID
	 */
    public function setRequestUri($projectID)
    {
        $this->requestUri =  $this->baseUrl . sprintf($this->urlPart, $projectID);
    }

	/**
	 * @param $assigneeID
	 */
    public function setAssigneeID($assigneeID)
    {
        $this->assigneeID = $assigneeID;
    }

	/**
	 * @return mixed
	 */
    public function getAssigneeID()
    {
        return $this->assigneeID;
    }

	/**
	 * @param $description
	 */
    public function setDescription($description)
    {
        $this->description = $description;
    }

	/**
	 * @return string
	 */
    public function getDescription()
    {
        return $this->description;
    }

	/**
	 * @param $descriptionFormat
	 */
    public function setDescriptionFormat($descriptionFormat)
    {
        $this->descriptionFormat = $descriptionFormat;
    }

	/**
	 * @return string
	 */
    public function getDescriptionFormat()
    {
        return $this->descriptionFormat;
    }

	/**
	 * @param $priority
	 */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

	/**
	 * @return int
	 */
    public function getPriority()
    {
        return $this->priority;
    }

	/**
	 * @param $status
	 */
    public function setStatus($status)
    {
        $this->status = $status;
    }

	/**
	 * @return string
	 */
    public function getStatus()
    {
        return $this->status;
    }

	/**
	 * @param $summary
	 */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

	/**
	 * @return mixed
	 */
    public function getSummary()
    {
        return $this->summary;
    }
}