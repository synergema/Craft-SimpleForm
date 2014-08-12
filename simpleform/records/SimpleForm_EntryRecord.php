<?php
namespace Craft;

class SimpleForm_EntryRecord extends BaseRecord
{
	public function getTableName()
	{
		return 'simpleform_entries';
	}

	public function defineAttributes()
	{
		return array(
			'formId' => AttributeType::Number,
			'title'  => AttributeType::String,
			'data'   => AttributeType::Mixed,
		);
	}

	public function defineRelations()
	{
		return array(
			'element' => array(static::BELONGS_TO, 'ElementRecord', 'id', 'required' => true, 'onDelete' => static::CASCADE),
			'form'    => array(static::BELONGS_TO, 'SimpleForm_FormRecord', 'required' => true, 'onDelete' => static::CASCADE),
		);
	}
}