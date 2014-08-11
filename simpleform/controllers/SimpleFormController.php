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
		$variables['forms'] = craft()->simpleForm->getAllForms();

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

		$form->name                     = craft()->request->getPost('name');
		$form->handle                   = craft()->request->getPost('handle');
		$form->description              = craft()->request->getPost('description');
		$form->emailSubject             = craft()->request->getPost('emailSubject');
		$form->fromEmail                = craft()->request->getPost('fromEmail');
		$form->replyToEmail             = craft()->request->getPost('replyToEmail');
		$form->toEmail                  = craft()->request->getPost('toEmail');
		$form->notificationTemplatePath = craft()->request->getPost('notificationTemplatePath');

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

	public function actionEntriesIndex()
	{
		
		// Get the data
		$variables = craft()->simpleForm->getAllEntries();

		// Render the template!
		$this->renderTemplate('simpleform/entries/index', $variables);
	}

	public function actionViewEntry(array $variables = array())
	{
		$entry              = craft()->simpleForm->getFormEntryById($variables['entryId']);
		$variables['entry'] = $entry;
		// die(var_dump($entryRecord));

		if (empty($entry))
		{
			throw new HttpException(404);
		}

		$variables['data'] = $this->_filterPostKeys(unserialize($entry->data));

		$this->renderTemplate('simpleform/entries/_view', $variables);
	}

	public function actionSaveFormEntry()
	{
		$this->requirePostRequest();

		$errors['required'] = array();

		//die(var_dump(craft()->request->getPost()));

		$simpleFormHandle = craft()->request->getPost('simpleFormHandle');

		// Required attributes
		$required = craft()->request->getPost('required');

		if ($required)
		{
			foreach ($required as $index => $key)
			{
				$value = craft()->request->getPost($key);

				if ($value == '')
				{
					$errors['required'][$key] = "$key is a required field.";
				}
			}
		}

		if (!empty($errors['required']))
		{
			craft()->userSession->setError($errors);
			craft()->userSession->setFlash('post', craft()->request->getPost());

			$this->redirect(craft()->request->getUrl());
		}

		if (!$simpleFormHandle)
		{
			throw new HttpException(404);
		}

		// Get the form model, need this to save the entry
		$form = craft()->simpleForm->getFormByHandle($simpleFormHandle);

		if (!$form)
		{
			throw new HttpException(404);
		}

		// @todo Need to exclude certain keys
		$excludedPostKeys = array();

		// Form data
		$data = serialize(craft()->request->getPost());

		$simpleFormEntry = new SimpleForm_EntryModel();

		// Set entry attributes
		$simpleFormEntry->formId = $form->id;
		$simpleFormEntry->data   = $data;

		// Save it
		if (craft()->simpleForm->saveFormEntry($simpleFormEntry))
		{
			// Time to make the notifications
			if ($this->_sendEmailNotification($simpleFormEntry, $form)) {
				$message =  Craft::t('Thank you, we have received your submission and we\'ll be in touch shortly.');
				craft()->userSession->setFlash('thankYou', $message);
				$this->redirectToPostedUrl($simpleFormEntry);
			}
			else {
				craft()->userSession->setError(Craft::t('We\'re sorry, but something has gone wrong.'));
			}

			craft()->userSession->setNotice(Craft::t('Entry saved.'));
			$this->redirectToPostedUrl($simpleFormEntry);
		}
		else
		{
			craft()->userSession->setNotice(Craft::t("Couldn't save the form."));
		}

		// Send the saved form back to the template
		craft()->urlManager->setRouteVariables(array(
			'entry' => $simpleFormEntry
		));
	}

	protected function _sendEmailNotification($record, $form)
	{
		// Put in work setting up data for the email template.
		$data = new \stdClass($data);

		$data->entryId   = $record->id;

		$postData = unserialize($record->data);
		$postData = $this->_filterPostKeys($postData);

		foreach ($postData as $key => $value)
		{
			$data->$key = $value;
		}

		// Email template
		$template = 'simpleform/email/default';
		$variables = array(
			'data' => $postData,
			'form' => $form,
		);
		$message  = craft()->templates->render($template, $variables);

		// Send the message
		if (craft()->simpleForm->sendEmailNotification($form, $message, true, null)) {
			return true;
		} else {
			return false;
		}
	}

	protected function _filterPostKeys($post)
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