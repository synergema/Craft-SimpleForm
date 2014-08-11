<?php
namespace Craft;

class SimpleForm_EntryModel extends BaseElementModel
{
	protected $elementType = 'SimpleForm';

	function __toString()
	{
		return $this->id;
	}

	protected function defineAttributes()
	{
		return array_merge(parent::defineAttributes(), array(
			'id'     => AttributeType::Number,
			'formId' => AttributeType::Number,
			'data'   => AttributeType::Mixed,
		));
	}

	/**
	 * Returns whether the current user can edit the element.
	 *
	 * @return bool
	 */
	public function isEditable()
	{
		return true;
	}

	/**
	 * Returns the element's CP edit URL.
	 *
	 * @return string|false
	 */
	public function getCpEditUrl()
	{
		return UrlHelper::getCpUrl('simpleform/entries/'.$this->id);
	}
}