# SimpleForm for Craft CMS

A simple form submission logger and email notifier for [Craft 2](http://buildwithcraft.com).

-----

**Note: This is still alpha and may not quite be ready for production use. Please log any issues you come across.** 

-----

* [Features](#features)
* [Control Panel](#control-panel)
* [Front-End Setup](#front-end-setup)
* [Email Notifications](#email-notifications)
* [Sample Front End Form](#sample-front-end-form)

## Features {#features}


* Create any number of front-end forms with their own settings.
* Log form submissions to the CP.
* Send email notifications when forms are submitted from the front-end. *Note: User email notifications not yet implemented.*
* Set required fields for your form.
* Custom redirects after submission, or just redirect back to the same page.
* Flash data sent back to the form on error and successful submission:
	- `error (array)`: This will be present if there are required fields that are not filled in.
	- `error['required']`: Array of required keys and error messages.
	- `success (string)`: If the form has been saved and the email notification sent, this will contain the success message from the form settings. This will be present even if redirected to another route.
	- `form (array)`: This will contain all of the POST form data, useful on errors to re-populate form fields (see sample form).
* Honeypot spam protection.

### This plugin leaves much of the implementation up to the developer and assumes some things about your forms. Please read.

## Control Panel {#control-panel}

**SimpleForm** adds it's own CP section, aptly named `SimpleForm`. You'll see it in Craft's main nav once you install it.

By default, you'll be taken to `Forms`. Here you can add a new form. All form settings are done here. The `handle` field is what you'll use in your [front-end form](#front-end-form-sample)(see the `sampleForm.html` in the root) to relate the form submission to this form by way of a hidden input.

Once you have form entries posted from the front-end of your website, the `Entries` tab will display those and allow you to filter by form.

## Front-End Setup {#front-end-setup}

### SimpleForm Handle

This a required field on the front-end and is used to relate entries to the form.

	<input type="hidden" name="simpleFormHandle" value="contactForm">

### Required Fields

This is optional. You can create as many hidden input fields as needed to specify which fields are required. Hidden inputs should be setup this way:

#### Example:
	<input type="hidden" name="required[fieldName]" value="Error message to be flashed back.">

So, if you have an an input for first name named `first_name`, to make this a required field, create a hidden input field:

	<input type="hidden" name="required[first_name]" value="Please fill in your first name.">

### Custom Redirect

After a successful form submission, by default you'll be redirected back to the same page. You can override this by setting a custom redirect via a hidden input:

	<input type="hidden" name="redirect" value="path/to/page">

### Honeypot Spam Protection

You can set a honeypot field for basic spam protection. Create a hidden input named `simpleFormHoneypot`. The value should be the name of your honeypot field. For example, if you want a honeypot field called `sillySpammers`, you'll need (2) inputs:

	<!-- The honeypot setting -->
	<input type="hidden" name="simpleFormHoneypot" value="sillySpammers">

	<!-- The honeypot field -->
	<input type="text" name="sillySpammers" value="" style="display:none;">

If the honeypot field is not empty, the request will end and be redirected back to the same page. The entry will not be logged and emails won't be sent.

## Email Notifications {#email-notifications}

`SimpleForm` can generate email notifications on successful submissions. *Note: At this time, user email notificaitons are not implemented.*

To send admin email notifications, configure all of the settings in the form in the CP. There is an optional field in the form settings, `Template Path to Notification Email`. You can specify your own Twig template to be used when sending the email notification. This should be the relative path to the template from the root of your site's templates directory, similair to how native entry type templates are configured. If this is not set, there is a default template within `simpleform` at `templates/email/default.html`. This will be used for the notification if you do not specify your own template path.

You will access to the following data in your email template:

* `data (array)`: All form POST data. *Note: The data is automatically filtered to include the hidden settings fields.*
* `entry.id (int)`: The ID of the form entry submission.
* `form (array)`: The form record itself. This gives you access to all data from the form record:
	- `id`
	- `name`
	- `description`
	- `successMessage`
	- `emailSubject`
	- `fromEmail`
	- `fromName`
	- `replyToEmail`
	- `toEmail`
	- `notificationTemplatePath`

## Sample Front-End Form {#sample-front-end-form}

See `sampleForm.html` in the root for a sample front-end implementation of SimpleForm.









