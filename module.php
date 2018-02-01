<?php
if (!defined('_PS_VERSION_')) {
  exit;
}
 
class MyModule extends Module
{
  public function __construct()
  {
    $this->name = 'mymodule';
    $this->tab = 
    // 'administration' //Administration
    // 'advertising_marketing' //Advertising & Marketing
    // 'analytics_stats' //Analytics & Stats
    // 'billing_invoicing' //Billing & Invoices
    // 'checkout' //Checkout
       'content_management' //Content Management
    // 'dashboard' //Dashboard
    // 'emailing' //E-mailing
    // 'export' //Export
    // 'front_office_features' //Front Office Features
    // 'i18n_localization' //I18n & Localization
    // 'market_place' //Market Place
    // 'merchandizing' //Merchandizing
    // 'migration_tools' //Migration Tools
    // 'mobile' //Mobile
    // 'others' //Other Modules
    // 'payments_gateways' //Payments & Gateways
    // 'payment_security' //Payment Security
    // 'pricing_promotion' //Pricing & Promotion
    // 'quick_bulk_update' //Quick / Bulk update
    // 'search_filter' //Search & Filter
    // 'seo' //SEO
    // 'shipping_logistics' //Shipping & Logistics
    // 'slideshows' //Slideshows'smart_shopping' //Smart Shopping
    // 'social_networks' //Social Networks
  ;
    $this->version = '0.0.0';
    $this->author = 'Alex Glebov';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
    $this->bootstrap = true;
 
    parent::__construct();
 
    $this->displayName = $this->l('My module');
    $this->description = $this->l('Description of my module.');
 
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
 
    if (!Configuration::get('MYMODULE_NAME')) {
      $this->warning = $this->l('No name provided');
    }
  }
  public function install()
  {
    if (Shop::isFeatureActive()) {
      Shop::setContext(Shop::CONTEXT_ALL);
    }

    if (!parent::install() ||
      !$this->registerHook('leftColumn') ||
      !$this->registerHook('header') ||
      !Configuration::updateValue('MYMODULE_NAME', 'my friend')
    ) {
      return false;
    }

    return true;
  }
  public function getContent() 
  {
      $output = null;

      if (Tools::isSubmit('submit'.$this->name))
      {
          $my_module_name = strval(Tools::getValue('MYMODULE_NAME'));
          if (!$my_module_name
            || empty($my_module_name)
            || !Validate::isGenericName($my_module_name))
              $output .= $this->displayError($this->l('Invalid Configuration value'));
          else
          {
              Configuration::updateValue('MYMODULE_NAME', $my_module_name);
              $output .= $this->displayConfirmation($this->l('Settings updated'));
          }
      }
      return $output.$this->displayForm();
  }
  public function displayForm()
  {
      // Get default language
      $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

      // Init Fields form array
      $fields_form[0]['form'] = array(
          'legend' => array(
              'title' => $this->l('Settings'),
          ),
          'input' => array(
              array(
                  'type' => 'text',
                  'label' => $this->l('Configuration value'),
                  'name' => 'MYMODULE_NAME',
                  'size' => 20,
                  'required' => true
              )
          ),
          'submit' => array(
              'title' => $this->l('Save'),
              'class' => 'btn btn-default pull-right'
          )
      );

      $helper = new HelperForm();

      // Module, token and currentIndex
      $helper->module = $this;
      $helper->name_controller = $this->name;
      $helper->token = Tools::getAdminTokenLite('AdminModules');
      $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

      // Language
      $helper->default_form_language = $default_lang;
      $helper->allow_employee_form_lang = $default_lang;

      // Title and toolbar
      $helper->title = $this->displayName;
      $helper->show_toolbar = true;        // false -> remove toolbar
      $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
      $helper->submit_action = 'submit'.$this->name;
      $helper->toolbar_btn = array(
          'save' =>
          array(
              'desc' => $this->l('Save'),
              'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
              '&token='.Tools::getAdminTokenLite('AdminModules'),
          ),
          'back' => array(
              'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
              'desc' => $this->l('Back to list')
          )
      );

      // Load current value
      $helper->fields_value['MYMODULE_NAME'] = Configuration::get('MYMODULE_NAME');

      return $helper->generateForm($fields_form);
  }
// ref to useful calls
// Configuration::get('myVariable'): retrieves a specific value from the database.
// Configuration::getMultiple(array('myFirstVariable', 'mySecondVariable', 'myThirdVariable')): retrieves several values from the database, and returns a PHP array.
// Configuration::updateValue('myVariable', $value): updates an existing database variable with a new value. If the variable does not yet exist, it creates it with that value.
// Configuration::deleteByName('myVariable'): deletes the database variable.
/*
Configuration::get('PS_LANG_DEFAULT'): retrieves the ID for the default language.
Configuration::get('PS_TIMEZONE'): retrieves the name of the current timezone, in standard TZ format (see: http://en.wikipedia.org/wiki/List_of_tz_database_time_zones).
Configuration::get('PS_DISTANCE_UNIT'): retrieves the default distance unit ("km" for kilometers, etc.).
Configuration::get('PS_SHOP_EMAIL'): retrieves the main contact e-mail address.
Configuration::get('PS_NB_DAYS_NEW_PRODUCT'): retrieves the number of days during which a newly-added product is considered "New" by PrestaShop.
  if (Shop::isFeatureActive()) {
    Shop::setContext(Shop::CONTEXT_ALL);
  }
  */
}
