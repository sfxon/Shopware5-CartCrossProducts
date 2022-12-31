<?php

namespace mvCartCrossProducts;

use Doctrine\ORM\Tools\SchemaTool;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Menu\Menu;
use Shopware\Components\Theme\LessDefinition;
use Shopware\Components\Api\Manager;

 
/////////////////////////////////////////////////////////////
// Mindfav Custom Cart Class
/////////////////////////////////////////////////////////////
class mvCartCrossProducts extends Plugin
{
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		// Events abonnieren, bzw. eigene Funktionen daran binden.
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		/*
		public static function getSubscribedEvents()
    {
				$events = array(
						//'Enlight_Controller_Dispatcher_ControllerPath_Backend_Doccreator' => 'registerController',
						//'Shopware_CronJob_MvDocCreatorSendInvoiceMail' => 'onSendInvoiceMail'
				);
				
				return $events;
    }
		*/
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		// Register backend controller.
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		/*
		public function registerController(\Enlight_Event_EventArgs $args) {
				return $this->getPath() . "/Controller/Backend/mvCustomTopBar.php";
		}
		*/
		
		///////////////////////////////////////////////////////////////////////
		// Events abonnieren.
		///////////////////////////////////////////////////////////////////////
		public static function getSubscribedEvents() {
        return array(
            //'Enlight_Controller_Action_PreDispatch' => 'addTemplateDir',			//Template Verzeichnis hinzufügen.
						//'Enlight_Controller_Action_PostDispatch' => 'onPostDispatch',			//Plugin Einstellungen im Template zur Verfügung stellen.
						//'Theme_Compiler_Collect_Plugin_Less' => 'onCollectLessFiles'//,			//Einstellungen als Variablen im LESS Stylesheet bereitstellen.
						//'Theme_Compiler_Collect_Plugin_Javascript' => 'addJsFiles'
        );
		}
		
		///////////////////////////////////////////////////////////////////////
		// Plugin Einstellungen im Template zur Verfügung stellen.
		///////////////////////////////////////////////////////////////////////
    /*
		public function onPostDispatch(\Enlight_Event_EventArgs $args) {
			  $shop = false;
				
				if ($this->container->initialized('shop')) {
						$shop = $this->container->get('shop');
				}
		
				if (!$shop) {
						$shop = $this->container->get('models')->getRepository(\Shopware\Models\Shop\Shop::class)->getActiveDefault();
				}
				
				$pluginConfig = Shopware()->Container()->get('shopware.plugin.cached_config_reader')->getByPluginName('mvHanosanHeader', $shop);
				
				$controller = $args->getSubject();
		    $view = $controller->View();
				
				$view->assign('mv_hanosan_header_settings', $pluginConfig);
    }
		
		///////////////////////////////////////////////////////////////////////
		// Template Verzeichnis hinzufügen!
		///////////////////////////////////////////////////////////////////////
    public function addTemplateDir(\Enlight_Event_EventArgs $args) {
				$controller = $args->get('subject');
        $view = $controller->View();
				
        $view->addTemplateDir(
            $this->getPath() . '/Resources/views/'
        );
				
        //$view->extendsTemplate('backend/mv_custom_top_bar/app.js');
    }
		
		///////////////////////////////////////////////////////////////////////
		// Einstellungen als Variablen im LESS Stylesheet bereitstellen.
		// Thanks go to suud from shopware forums:
		// https://forum.shopware.com/discussion/40800/plugin-settings-als-less-variable
		// Im LESS kann die Variable dann mit @plugin_config_[meine_variable] verwendet werden.
		///////////////////////////////////////////////////////////////////////
		public function onCollectLessFiles(\Enlight_Event_EventArgs $args) {
			 $shop = false;
				
				if ($this->container->initialized('shop')) {
						$shop = $this->container->get('shop');
				}
		
				if (!$shop) {
						$shop = $this->container->get('models')->getRepository(\Shopware\Models\Shop\Shop::class)->getActiveDefault();
				}
        $pluginPath = Shopware()->Container()->getParameter('mv_hanosan_header.plugin_dir');

				
        $less = new LessDefinition(
            array(),
						array(
                $pluginPath . '/Resources/Views/frontend/_public/less/all.less'			 //less files to compile
            )
        );

        return $less;
    }
		*/
		
		///////////////////////////////////////////////////////////////////////
		// Javascript einbinden.
		///////////////////////////////////////////////////////////////////////
		/*
		public function addJsFiles(\Enlight_Event_EventArgs $args) {
			 $shop = false;
				
				if ($this->container->initialized('shop')) {
						$shop = $this->container->get('shop');
				}
		
				if (!$shop) {
						$shop = $this->container->get('models')->getRepository(\Shopware\Models\Shop\Shop::class)->getActiveDefault();
				}
        $pluginPath = Shopware()->Container()->getParameter('mv_hanosan_header.plugin_dir');
			
        $jsFiles = array($plugin_path . '/Resources/frontend/js/hanosan_header.js');
        
				
				return new ArrayCollection($jsFiles);
				//return array();
    }*/
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////
    // @inheritdoc
		//////////////////////////////////////////////////////////////////////////////////////////////////////
	  public function install(InstallContext $context) {
				//Freitextfeld hinzufügen, in dem hinterlegt werden kann, dass diese Option für ein bestimmtes Land gilt (als ISO Code)
				/*
				$service = $this->container->get('shopware_attribute.crud_service');
        $service->update(
						's_premium_dispatch_attributes', 
						'mv_shipping_by_weight', 
						'string',
						array( 
								'displayInBackend' => true,
								'label' => 'Länder für Versand nach Gewicht',
								'supportText' => 'Länder angeben, die für die Versand nach Gewicht-Anzeige verwendet werden sollen. Komma-separierte ISO-2 Liste, bpsw.: DE,FR,AT für Deutschland, Frankreich, Österreich ',
								'helpText' => 'Länder-Kürzel können aus der Shopware Länder Tabelle entnommen werden.'
						)
				);
				*/
				
				//$this->installDatabaseAndDemoData();
				//$this->installBackendMenuBarEntries($context);
				
				parent::install($context);
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////
    // {@inheritdoc}
    //////////////////////////////////////////////////////////////////////////////////////////////////////
		public function activate(ActivateContext $activateContext) {
        $installer = $this->container->get('theme_installer');
				$installer->synchronize();
				
				$activateContext->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
    }

		//////////////////////////////////////////////////////////////////////////////////////////////////////
    // @inheritdoc
		//////////////////////////////////////////////////////////////////////////////////////////////////////
    public function uninstall(UninstallContext $context) {
				//$service = $this->container->get('shopware_attribute.crud_service');
        //$service->delete('s_premium_dispatch_attributes', 'mv_shipping_by_weight');
			
        parent::uninstall($context);
    }

}