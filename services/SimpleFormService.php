<?php
namespace Craft;

class SimpleFormService extends BaseApplicationComponent
{
	public function getAllEntries()
	{
		$entries = SimpleForm_EntryRecord::model()->findAll();

		return $entries;
	}

	public function getAllForms()
	{
		$forms = SimpleForm_FormRecord::model()->findAll();

		return $forms;
	}

	public function getFormById($id)
	{
		$formRecord = SimpleForm_FormRecord::model()->findById($id);

		return SimpleForm_FormModel::populateModel($formRecord);
	}

	public function getFormByHandle($handle)
	{
		$formRecord = SimpleForm_FormRecord::model()->findByAttributes(array(
			'handle' => $handle,
		));

		if (!$formRecord)
		{
			return false;
		}

		return SimpleForm_FormModel::populateModel($formRecord);
	}

	/**
	 * Save a form.
	 * @param  SimpleForm_FormModel $form [description]
	 * @return [type]                     [description]
	 */
	public function saveForm(SimpleForm_FormModel $form)
	{
		if ($form->id)
		{
			$formRecord = SimpleForm_FormRecord::model()->findById($form->id);

			if (!$formRecord)
			{
				throw new Exception(Craft::t('No form exists with the ID "{id}"', array('id' => $form->id)));
			}

			$oldForm   = SimpleForm_FormModel::populateModel($formRecord);
			$isNewForm = false;
		}
		else
		{
			$formRecord = new SimpleForm_FormRecord();
			$isNewForm  = true;
		}

		// Set record attributes
		$formRecord->name                     = $form->name;
		$formRecord->handle                   = $form->handle;
		$formRecord->description              = $form->description;
		$formRecord->successMessage           = $form->successMessage;
		$formRecord->emailSubject             = $form->emailSubject;
		$formRecord->fromEmail                = $form->fromEmail;
		$formRecord->fromName                 = $form->fromName;
		$formRecord->replyToEmail             = $form->replyToEmail;
		$formRecord->toEmail                  = $form->toEmail;
		$formRecord->notificationTemplatePath = $form->notificationTemplatePath;


		$formRecord->validate();
		$form->addErrors($formRecord->getErrors());

		if (!$form->hasErrors())
		{
			$transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;

			try
			{
				$formRecord->save(false);

				if (!$form->id)
				{
					$form->id = $formRecord->id;
				}

				if ($transaction !== null)
				{
					$transaction->commit();
				}
			}
			catch (\Exception $e)
			{
				if ($transaction !== null)
				{
					$transaction->rollback();
				}

				throw $e;
			}

			return true;
		}
		else
		{
			return false;
		}
	}

	public function getFormEntryById($id)
	{
		return craft()->elements->getElementById($id, 'SimpleForm');
	}

	public function deleteFormById($formId)
	{
		if (!$formId)
		{
			return false;
		}

		$transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
		try
		{
			// Grab the entry ids so we can clean the elements table.
			$entryIds = craft()->db->createCommand()
				->select('id')
				->from('simpleform_entries')
				->where(array('formId' => $formId))
				->queryColumn();

			craft()->elements->deleteElementById($entryIds);

			$affectedRows = craft()->db->createCommand()->delete('simpleform_forms', array('id' => $formId));

			if ($transaction !== null)
			{
				$transaction->commit();
			}

			return (bool) $affectedRows;
		}
		catch (\Exception $e)
		{
			if ($transaction !== null)
			{
				$transaction->rollback();
			}

			throw $e;
		}
	}

	/**
	 * Save the form entry element.
	 * @param  SimpleForm_EntryModel $entry [description]
	 * @return [type]                       [description]
	 */
	public function saveFormEntry(SimpleForm_EntryModel $entry)
	{
		$entryRecord = new SimpleForm_EntryRecord();

		// Set attributes
		$entryRecord->formId = $entry->formId;
		$entryRecord->data   = $entry->data;

		$entryRecord->validate();
		$entry->addErrors($entryRecord->getErrors());

		if (!$entry->hasErrors())
		{
			$transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;

			try
			{
				if (craft()->elements->saveElement($entry))
				{
					$entryRecord->id = $entry->id;
					$entryRecord->save(false);

					if ($transaction !== null)
					{
						$transaction->commit();
					}

					return $entryRecord->id;
				}
				else
				{
					return false;
				}
			}
			catch (\Exception $e)
			{
				if ($transaction !== null)
				{
					$transaction->rollback();
				}

				throw $e;
			}

			return true;
		}
		else
		{
			return false;
		}
	}

	public function sendEmailNotification($form, $message, $html = true, $email = null)
	{
		// Generic errors bool
		$errors = false;

		$email = new EmailModel();

		$email->fromEmail = $form->fromEmail;
		$email->replyTo   = $form->replyToEmail;
		$email->sender    = $form->fromEmail;
		$email->fromName  = 'SimpleForm Bot';
		$email->subject   = $form->emailSubject;
		$email->htmlBody  = $message;

		// Support for sending multiple emails
		$emailTo = explode(',', $form->toEmail);

		foreach ( $emailTo as $emailAddress ) {
			$email->toEmail = trim( $emailAddress );

			if ( ! craft()->email->sendEmail($email) ) {
				$errors = true;
			}
		}

		return $errors ? false : true;

	}

}