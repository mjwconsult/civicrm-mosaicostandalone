<?php

use CRM_Mosaico_ExtensionUtil as E;

class CRM_MosaicoStandalone_Page_EditorIframe extends CRM_Core_Page {
  const DEFAULT_MODULE_WEIGHT = 200;

  public function run() {
    CRM_Utils_System::setTitle(E::ts('Mosaico'));

    $editorURLParams = [
      'snippet' => 1,
    ];
    $mailingID = CRM_Utils_Request::retrieveValue('mailingid', 'Positive', NULL);
    if ($mailingID) {
      $editorURLParams['mailingid'] = $mailingID;
    }
    $template = CRM_Utils_Request::retrieveValue('template', 'String', NULL);
    if ($template) {
      $editorURLParams['template'] = $template;
    }
    if (!$mailingID && !$template) {
      throw new CRM_Core_Exception('One of mailingid or template param is required');
    }

    $smarty = CRM_Core_Smarty::singleton();
    $smarty->assign('mosaicoEditorIframe', CRM_Utils_System::url('civicrm/mosaicostandalone/editor', $editorURLParams, TRUE, NULL, FALSE));
    parent::run();
  }

}
