<?php
namespace Craft;

class SimpleFormElementType extends BaseElementType
{
	public function getName()
	{
		return Craft::t('SimpleForm');
	}

	public function getSources($context = null)
	{
		$sources = array(
			'*' => array(
				'label' => Craft::t('All SimpleForm submissons.'),
			),
		);

		foreach (craft()->simpleform->getAllForms() as $form )
		{
			$key = 'formId:' . $form->id;

			$sources[$key] = array(
				'label'    => $form->name,
				'criteria' => array('form' => $form->id)
			);
		}

		return $sources;
	}

	public function defineSearchableAttributes()
	{
		return array();
	}

	public function defineTableAttributes($source = null)
	{
		return array(
			'id'          => Craft::t('ID'),
			'dateCreated' => Craft::t('Date'),
			'data'        => Craft::t('Submission Data'),
		);
	}

	/**
	 * Returns the table view HTML for a given attribute.
	 *
	 * @param BaseElementModel $element
	 * @param string $attribute
	 * @return string
	 */
	public function getTableAttributeHtml(BaseElementModel $element, $attribute)
	{
		return '';
	}

	public function defineCriteriaAttributes()
	{
		return array();
	}

	/**
	 * Modifies an element query targeting elements of this type.
	 *
	 * @param DbCommand $query
	 * @param ElementCriteriaModel $criteria
	 * @return mixed
	 */
	public function modifyElementsQuery(DbCommand $query, ElementCriteriaModel $criteria)
	{
		$query
			->addSelect('simpleforms_entries.formId, simpleforms_entries.data')
			->join('simpleform simpleforms_entries', 'simpleforms_entries.id = elements.id');

		// if ($criteria->firstName) {
		// 	$query->andWhere(DbHelper::parseParam('landingpages.firstName', $criteria->firstName, $query->params));
		// }

		// if ($criteria->lastName) {
		// 	$query->andWhere(DbHelper::parseParam('landingpages.lastName', $criteria->lastName, $query->params));
		// }

		// if ($criteria->email) {
		// 	$query->andWhere(DbHelper::parseParam('landingpages.email', $criteria->email, $query->params));
		// }

		// if ($criteria->landingPage) {
		// 	$query->andWhere(DbHelper::parseParam('landingpages.landingPage', $criteria->landingPage, $query->params));
		// }

	}

	/**
	 * Populates an element model based on a query result.
	 *
	 * @param array $row
	 * @return array
	 */
	public function populateElementModel($row)
	{
		return SimpleForm_EntryModel::populateModel($row);
	}

}