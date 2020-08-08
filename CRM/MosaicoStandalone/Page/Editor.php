<?php

use CRM_Mosaico_ExtensionUtil as E;

class CRM_MosaicoStandalone_Page_Editor extends CRM_Mosaico_Page_Editor {
  const DEFAULT_MODULE_WEIGHT = 200;

  public function run() {
    CRM_Utils_System::setTitle(E::ts('Mosaico'));

    $smarty = CRM_Core_Smarty::singleton();
    $smarty->assign('baseUrl', CRM_Mosaico_Utils::getMosaicoDistUrl('relative'));
    $smarty->assign('scriptUrls', $this->getScriptUrls());
    $smarty->assign('styleUrls', $this->getStyleUrls());
    $smarty->assign('mosaicoConfig', json_encode(
      $this->createMosaicoConfig(),
      defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0
    ));
    echo $smarty->fetch(self::getTemplateFileName());
    CRM_Utils_System::civiExit();
  }

  protected function getScriptUrls() {
    $cacheCode = CRM_Core_Resources::singleton()->getCacheCode();
    $mosaicoDistUrl = CRM_Mosaico_Utils::getMosaicoDistUrl('relative');
    $mosaicoExtUrl = CRM_Core_Resources::singleton()->getUrl('uk.co.vedaconsulting.mosaico');
    return [
      "{$mosaicoDistUrl}/vendor/knockout.js?r={$cacheCode}",
      "{$mosaicoDistUrl}/vendor/jquery.min.js?r={$cacheCode}",
      "{$mosaicoDistUrl}/vendor/jquery-ui.min.js?r={$cacheCode}",
      "{$mosaicoDistUrl}/vendor/jquery.ui.touch-punch.min.js?r={$cacheCode}",
      "{$mosaicoDistUrl}/vendor/load-image.all.min.js?r={$cacheCode}",
      "{$mosaicoDistUrl}/vendor/canvas-to-blob.min.js?r={$cacheCode}",
      "{$mosaicoDistUrl}/vendor/jquery.iframe-transport.js?r={$cacheCode}",
      "{$mosaicoDistUrl}/vendor/jquery.fileupload.js?r={$cacheCode}",
      "{$mosaicoDistUrl}/vendor/jquery.fileupload-process.js?r={$cacheCode}",
      "{$mosaicoDistUrl}/vendor/jquery.fileupload-image.js?r={$cacheCode}",
      "{$mosaicoDistUrl}/vendor/jquery.fileupload-validate.js?r={$cacheCode}",
      "{$mosaicoDistUrl}/vendor/knockout-jqueryui.min.js?r={$cacheCode}",
      "{$mosaicoDistUrl}/vendor/tinymce.min.js?r={$cacheCode}",
      "{$mosaicoDistUrl}/mosaico.min.js?v=0.15?&={$cacheCode}",
    ];
  }

  protected function getStyleUrls() {
    $cacheCode = CRM_Core_Resources::singleton()->getCacheCode();
    $mosaicoDistUrl = CRM_Mosaico_Utils::getMosaicoDistUrl('relative');
    // $mosaicoExtUrl = CRM_Core_Resources::singleton()->getUrl('uk.co.vedaconsulting.mosaico');
    return [
      "{$mosaicoDistUrl}/mosaico-material.min.css?v=0.10&r={$cacheCode}",
      "{$mosaicoDistUrl}/vendor/notoregular/stylesheet.css?r={$cacheCode}",
    ];
  }


  /**
   * Generate the configuration options for `Mosaico.init()`.
   *
   * @return array
   */
  protected function createMosaicoConfig() {
    $config = parent::createMosaicoConfig();
    $config = $this->getTemplateConfig($config);
    return $config;
  }

  protected function getTemplateConfig($config) {
    $mailingID = CRM_Utils_Request::retrieveValue('mailingid', 'Positive', NULL);
    if ($mailingID) {
      $mailing = civicrm_api3('Mailing', 'getsingle', ['id' => $mailingID]);
      $config['mosaicoTemplateMetadata'] = $mailing['template_options']['mosaicoMetadata'];
      $config['mosaicoTemplateContent'] = $mailing['template_options']['mosaicoContent'];
      $config['mosaicoMailingID'] = $mailingID;
    }
    else {
      $template = CRM_Utils_Request::retrieveValue('template', 'String', NULL);
      $config['mosaicoTemplateName'] = $template;
      $config['mosaicoTemplatePath'] = CRM_Mosaico_Utils::getTemplatesUrl('absolute', "{$config['mosaicoTemplateName']}/template-{$config['mosaicoTemplateName']}.html");
    }
    return $config;
  }

  /**
   * Get the URL for a Civi route.
   *
   * @param string $path
   *   Ex: 'civicrm/admin/foo'.
   * @param string $query
   *   Ex: 'reset=1&id=123'.
   * @param bool $frontend
   * @return string
   */
  protected function getUrl($path, $query, $frontend) {
    // This function shouldn't really exist, but it's tiring to set `$htmlize`
    // to false every.single.time we need a URL.
    // These URLs should be absolute -- this influences the final URLs
    // for any uploaded images, and those will need to be absolute to work
    // correctly in all forms of composition/delivery.
    return CRM_Utils_System::url($path, $query, TRUE, NULL, FALSE, $frontend);
  }

  /**
   * @return int
   */
  public function getMaxFileSize() {
    $fakeUnlimited = 25 * 1024 * 1024;
    $iniVal = ini_get('upload_max_filesize') ? CRM_Utils_Number::formatUnitSize(ini_get('upload_max_filesize'), TRUE) : $fakeUnlimited;
    $settingVal = Civi::settings()->get('maxFileSize') ? (1024 * 1024 * Civi::settings()->get('maxFileSize')) : $fakeUnlimited;
    return (int) min($iniVal, $settingVal);
  }

}
