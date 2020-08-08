# mosaico-standalone

**This extension is NOT ready for use!**

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* CiviCRM Mosaico extension 2.3+.

## Installation

See: https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/#installing-a-new-extension

## Usage

Create a new "Mailing" by opening the URL with `template` as parameter. Eg:
`https://example.org/civicrm/mosaicostandalone/editoriframe?template=versafix-1`

You can edit, save and close the "mailing". It will be saved in the `civicrm_mailing` table.

To re-open once closed you need the mailing ID (shown in console when saving with message `saved(ID))`.
The use the `mailingid` parameter. Eg:
`https://example.org/civicrm/mosaicostandalone/editoriframe?mailingid=59`


## Known Issues

#### Permissions
* No work has been done on permissions. So you'll require the same permissions as the normal mosaico editor at the moment.
* The idea is that we'll be able to assign a special permission that only grants access to this editor for the mailings that the CMS user created.

**Question:**: How should we present this interface to the user - what is the workflow?

#### Mailing Metadata

We don't allow selection of any mailing metadata (eg. subject, from etc.). This could easily be added via this extension in a before/after form.

**Question:**: How/when should this be presented?

#### Starting/editing a mailing

How should we present the interface to the user? Should they be allowed to select from a set of templates?

- A set of templates could be made available via hardcoded links or we could do something cleverer.

How should we show existing mailings?

- We could generate a list of links.

#### Users

Will the users have a CMS login and a CiviCRM contact record? Even if they don't have access to CiviCRM we could use that to control permissions over what mailings they can see.

#### Storing drafts

We are using the `civicrm_mailing` table which means they will show up in CiviCRM. We *could* easily use an alternative table and provide an "approval" process that moves them across to `civicrm_mailing`.

