<?php
namespace Craft;

class SimpleFormService extends BaseApplicationComponent
{
	public function getAllEntries()
	{

	}

	public function getAllForms()
	{
		
	}

	public function getFormById($id)
	{
		$formRecord = SimpleForm_FormRecord::model()->findById($id);
	}

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
		$formRecord->name        = $form->name;
		$formRecord->handle      = $form->handle;
		$formRecord->description = $form->description;

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
}