<?php namespace Aglipanci\Interspire;

use Config;

class Interspire {

	/**	
	 * [postData description]
	 * @param  [type] $xml [description]
	 * @return [type]      [description]
	 */
	private function postData($xml)
	{
		$ch = curl_init(Config::get('interspire::url'));
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

	/**
	 * [addSubscriberToList description]
	 * @param [type] $name    [description]
	 * @param [type] $surname [description]
	 * @param [type] $email   [description]
	 * @param [type] $list_id [description]
	 */
	public function addSubscriberToList($name, $surname, $email, $list_id){

		$xml = '<xmlrequest>
		<username>'.Config::get('interspire::api_user').'</username>
		<usertoken>'.Config::get('interspire::api_token').'</usertoken>
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

	/**	
	 * [deleteSubscriber description]
	 * @param  [type] $email [description]
	 * @return [type]        [description]
	 */
	public function deleteSubscriber($email)
	{
	 
		$xml = '<xmlrequest>
		<username>'.Config::get('interspire::api_user').'</username>
		<usertoken>'.Config::get('interspire::api_token').'</usertoken>
		<requesttype>subscribers</requesttype>
		<requestmethod>DeleteSubscriber</requestmethod>
		<details>
		<emailaddress>'.$email.'</emailaddress>
		<list>1</list>
		</details>
		</xmlrequest>';

		$this->postData($xml);
	}

	/**	
	 * [isOnList description]
	 * @param  [type] $list_id [description]
	 * @return void        [description]
	 */		
	public function isOnList($list_id)
	{
		$xml = '<xmlrequest>
		<username>'.Config::get('interspire::api_user').'</username>
		<usertoken>'.Config::get('interspire::api_token').'</usertoken>
		<requesttype>subscribers</requesttype>
		<requestmethod>IsSubscriberOnList</requestmethod>
		<details>
		<Email>email@yourdomain.com</Email>
		<List>'. $list_id .'</List>
		</details>
		</xmlrequest>';

		$this->postData($xml);
	}

}
