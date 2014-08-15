# SimpleForm

A simple form submission logger and email notifier for [Craft 2](http://buildwithcraft.com).

-----

**Note: This is still alphs. Not for production use! Or for the faint of heart!** 

-----

This plugin leaves much of the implementation up to the developer and assumes some things about your forms.

## Control Panel

**SimpleForm** adds it's own CP section, aptly named `SimpleForm`. You'll see it in Craft's main nav once you install it. **Note:** This is still *very* much a work-in-progress. It will get better.

By default, you'll be taken to `Forms`. Here you can add new form. All relative form settings are done here. The `handle` field is what you'll use in your [front-end form](#front-end-form-sample) to relate the form submission to this form.

Once you have form entries, the `Entries` tab will display those and allow you to filter by form.

## Email Notifications

This part is still very rough, but `SimpleForm` should generate email notifications of all form data via the settings in the form entry itself.

**Note:** There is a `Notification Template Path` setting in the form which is **not** implemented yet. The idea here will be that you can create your own Twig templates which will be parsed and sent as the email's HTML body. That will be in like version `6.2`.


## Sample Front-End Form {#front-end-form-sample}

	<form method="post" accepted-charset="utf-8">

		{% if craft.session.hasFlash('error') %}
			{% for error in craft.session.getFlash('error').required %}
			<p><strong>{{ error }}</strong></p>
			{% endfor %}
		{% endif %}

		{% set post = craft.session.getFlash('post') %}

		<input type="hidden" name="action" value="simpleForm/saveFormEntry">
		<input type="hidden" name="simpleFormHandle" value="contactForm">
		<input type="hidden" name="redirect" value="{{ craft.request.getUrl() ~ '/thanks' }}">
		<input type="hidden" name="honeypot" value="yesPlease">
		<input type="hidden" name="required[]" value="name">

		<p>
			<label for="name">Name</label>
			<input type="text" name="name" value="{{ post.name|default('') }}">
		</p>
		
		<p>
			<label for="email">Email</label>
			<input type="text" name="email" value="{{ post.email|default('') }}">
		</p>

		<p>
			<label for="message">Message</label>
			<input type="text" name="name" value="{{ post.message|default('') }}">
		</p>

		<button type="submit">
			<strong>Submit</strong>
		</button>

	</form>

## To-Dos

Just a few of the many that I coud think of. There are certainly more.

* Simple validation. This will come by way of a hidden input array named `required`. More to come.
* Honeypot implementation. This will also be a hidden field named `simpleFormHoneypot` and the value should be the name of the honeypot input.
* Form submission titles for display in the CP. You guessed it, another hidden input field called `simpleFormTitle`. Other ideas for this spinning as well.