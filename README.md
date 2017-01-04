# Form

Extends Canarium to handle dynamic form creation. This allows administrators to view, create, update, and delete forms for guests and users to fill up in the website.

# Installation

Install via composer: 

`composer require unarealidad/canarium-modules-form dev-master`

Add `Form` to your Appmaster's `config/application.config.php` or your Appinstance's `config/instance.config.php` under the key `modules`

Go to your Appinstance directory and run the following to update your database:

`./doctrine-module orm:schema-tool:update --force`

# Configuration

_None_

# Exposed Pages

URL | Template | Access | Description

----- | ----- | ----- | ----- | -----
/form/:form-id | form/index.phtml | Public | Shows the specified form
/form/thank-you | form/thank-you.phtml | Public | The thank you page after a successful submission
/form/submitted-forms | form/submitted-forms.phtml | User | List of forms submissions viewable to the current logged in user
/admin/form | admin/index.phtml | Admin |Shows the list of forms created by the logged in user
/admin/form/submitted-forms | admin/submitted-forms.phtml | Admin | Shows the list of submitted forms that the user has access to
/admin/form/settings | admin/settings.phtml | Admin | Form related customizations

\* All template locations are relative to the Appinstance root's /public/templates/form/. Sample templates are provided in the module's view/ directory.

# Additional Customization
 
_None_

# Exposed Services
`form_form_service` - Main service. Handles form related operations
