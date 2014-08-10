<?php
namespace Craft;

class SimpleFormController extends BaseController
{
	protected $allowAnonymous = true;

	/**
	 * View all entries
	 * @return (object) Array of entries.
	 */
	public function actionIndex()
	{
		$variables['tabs'] = $this->_getTabs();

		return $this->renderTemplate('simpleform/index', $variables);
	}

	public function actionNewForm()
	{
		$variables['form'] = new SimpleForm_FormModel;

		return $this->renderTemplate('simpleform/forms/_edit', $variables);
	}

	public function actionEditForm()
	{

	}

	public function actionSaveForm()
	{
		$this->requirePostRequest();

		$form = new SimpleForm_FormModel;

		$form->name        = craft()->request->getPost('name');
		$form->handle      = craft()->request->getPost('handle');
		$form->description = craft()->request->getPost('description');

		if (craft()->simpleForm->saveForm($form))
		{
			craft()->userSession->setNotice(Craft::t('Form saved.'));
			$this->redirectToPostedUrl($form);
		}
		else
		{
			craft()->userSession->setNotice(Craft::t("Couldn't save the form."));
		}

		// Send the saved form back to the template
		craft()->urlManager->setRouteVariables(array(
			'form' => $form
		));
	}

	protected function _getTabs()
	{
		return array(
			'forms' => array(
				'label' => "Forms", 
				'url'   => UrlHelper::getUrl('simpleform/'),
			),
			'entries' => array(
				'label' => "Entries", 
				'url'   => UrlHelper::getUrl('simpleform/entries'),
			),
		);
	}
}