<?php namespace Aglipanci\Interspire;

use Illuminate\Config\Repository;

class Interspire {

 /**
 * Illuminate config repository.
 *
 * @var Illuminate\Config\Repository
 */
protected $config;



/**
 * Create a new Interspire instance.
 *
 * @param  Illuminate\Config\Repository  $config
 * @return void
 */
public function __construct(Repository $config)
{
    $this->config = $config;
}

/**
 * Enable the Interspire.
 *
 * @return void
 */
public function enable()
{
    $this->config->set('interspire::enabled', true);
}

/**
 * Disable the Interspire.
 *
 * @return void
 */
public function disable()
{
    $this->config->set('interspire::enabled', false);
}

private function postData($xml)
{
	$ch = curl_init($this->config->get('interspire::url'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	$result = @curl_exec($ch);


	if($result === false) 
	{
		return false;
	}
	else 
	{
		$xml_doc = simplexml_load_string($result);

		if ($xml_doc->status == 'SUCCESS') 
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}

}

public function addSubscriberToList($name, $surname, $email, $list_id){

	$xml = '<xmlrequest>
	<username>'.$this->config->get('interspire::api_user').'</username>
	<usertoken>'.$this->config->get('interspire::api_token').'</usertoken>
	<requesttype>subscribers</requesttype>
	<requestmethod>AddSubscriberToList</requestmethod>
	<details>
	<emailaddress>'.$email.'</emailaddress>
	<mailinglist>'.$list_id.'</mailinglist>
	<format>html</format>
	<confirmed>yes</confirmed>
	<customfields>
	<item>
	<fieldid>2</fieldid>
	<value>'.$name.'</value>
	</item>
	<item>
	<fieldid>3</fieldid>
	<value>'.$surname.'</value>
	</item>
	</customfields>
	</details> 
	</xmlrequest>
	';

	$this->postData($xml);
}


public function deleteSubscriber($email)
{
 
	$xml = '<xmlrequest>
	<username>'.$this->config->get('interspire::api_user').'</username>
	<usertoken>'.$this->config->get('interspire::api_token').'</usertoken>
	<requesttype>subscribers</requesttype>
	<requestmethod>DeleteSubscriber</requestmethod>
	<details>
	<emailaddress>'.$email.'</emailaddress>
	<list>1</list>
	</details>
	</xmlrequest>';

	$this->postData($xml);
}

}