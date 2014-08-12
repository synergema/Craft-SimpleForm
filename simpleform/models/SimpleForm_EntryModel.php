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
			'title'  => AttributeType::String,
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

	public function _normalizeDataForElementsTable()
	{
		$data = unserialize($this->data);

		$data = $this->_filterPostKeys($data);

		// Pop off the first (2) items from the data array
		$data = array_slice($data, 0, 2);

		$newData = (string) "";

		foreach ($data as $key => $value)
		{
			$newData .= "<p><strong>{$key}</strong>: {$value}</p>";
		}

		$this->__set('data', $newData);

		return $this;
	}

	private function _filterPostKeys($post)
	{
		$filterKeys = array(
			'required',
			'action',
			'simpleformhandle',
			'redirect',
			'honeypot',
		);

		if (is_array($post))
		{
			foreach ($post as $k => $v)
			{
				if (in_array(strtolower($k), $filterKeys))
				{
					unset($post[$k]);
				}
			}
		}

		return $post;
	}

}