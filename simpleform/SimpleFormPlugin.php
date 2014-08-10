<?php
namespace Craft;

class SimpleFormPlugin extends BasePlugin
{
	function getName()
	{
		return Craft::t('SimpleForm');
	}

	function getVersion()
	{
		return '1.0a';
	}

	function getDeveloper()
	{
		return 'Plain Language.';
	}

	function getDeveloperUrl()
	{
		return 'http://plainlanguage.co';
	}

	function getSourceLanguage()
	{
		return 'en_us';
	}

	protected function defineSettings()
	{
		return array();
	}

	public function getSettingsHtml()
	{
		return '';
	}

	public function prepSettings($settings)
	{
		// Modify settings from POST here
		
		return $settings;
	}

	public function hasCpSection()
	{
		return true;
	}

	public function registerCpRoutes()
	{
		return array(
			'simpleform'                  => array('action' => 'simpleForm/index'),
			'simpleform/forms/new'        => array('action' => 'simpleForm/newForm'),
			'simpleform/(?P<entryId>\d+)' => array('action' => 'simpleForm/viewEntry'),
		);
	}
}