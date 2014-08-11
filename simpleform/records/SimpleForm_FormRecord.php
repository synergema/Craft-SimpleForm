<?php
namespace Craft;

class SimpleForm_FormRecord extends BaseRecord
{
	public function getTableName()
	{
		return 'simpleform_forms';
	}

	public function defineAttributes()
	{
		return array(
			'name'                     => AttributeType::String,
			'handle'                   => AttributeType::Handle,
			'description'              => AttributeType::String,
			'emailSubject'             => AttributeType::String,
			'fromEmail'                => AttributeType::Mixed,
			'replyToEmail'             => AttributeType::Email,
			'toEmail'                  => AttributeType::Mixed,
			'notificationTemplatePath' => AttributeType::String,
		);
	}

	public function defineRelations()
	{
		return array(
			'entry' => array(static::HAS_MANY, 'SimpleForm_EntryRecord', 'id'),
		);
	}
}