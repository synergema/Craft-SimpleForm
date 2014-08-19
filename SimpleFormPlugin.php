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
		return '1.0a2.2';
	}

	function getDeveloper()
	{
		return 'Synergema';
	}

	function getDeveloperUrl()
	{
		return 'http://synergema.com';
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
			'simpleform'                          => array('action' => 'simpleForm/index'),
			'simpleform/forms/new'                => array('action' => 'simpleForm/newForm'),
			'simpleform/forms/(?P<formId>\d+)'    => array('action' => 'simpleForm/editForm'),
			'simpleform/entries'                  => array('action' => 'simpleForm/entriesIndex'),
			'simpleform/entries/(?P<entryId>\d+)' => array('action' => 'simpleForm/viewEntry'),
		);
	}
}