<?php
namespace Craft;

class SimpleForm_FormModel extends BaseModel
{
	function __toString()
	{
		return Craft::t($this->name);
	}

	protected function defineAttributes()
	{
		return array(
			'id'                       => AttributeType::Number,
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
}