<?php

namespace mvCartCrossProducts\Subscriber;
use Enlight\Event\SubscriberInterface;
use Enlight_Template_Manager;
use Shopware\Models\Article\Detail;
//use Shopware\Components\Plugin\ConfigReader;
//use mvCustomCart\Components\CartExtender;

class RouteSubscriber implements SubscriberInterface
{
		private $templateManager;
		private $pluginBaseDirectory;

		public function __construct(Enlight_Template_Manager $templateManager, $pluginBaseDirectory) {
			$this->templateManager = $templateManager;
			$this->pluginBaseDirectory = $pluginBaseDirectory;
		}

		public static function getSubscribedEvents() {
			return array(
				'Enlight_Controller_Action_PreDispatch_Frontend' => 'onPreDispatch'
			);
		}

		public function onPreDispatch(\Enlight_Controller_ActionEventArgs $args) {
			$this->templateManager->addTemplateDir($this->pluginBaseDirectory . '/Resources/views');

			$shop = Shopware()->Shop();
			$config = Shopware()->Container()->get('shopware.plugin.cached_config_reader')->getByPluginName('mvCartCrossProducts', $shop);

			if(isset($config['mv_pcs_articles'])) {
				$mv_pcs_articles = $config['mv_pcs_articles'];
			} else {
					return;
			}


				/$mv_pcs_articles = $config->getByNamespace("mvCartCrossProducts", "mv_pcs_articles");

			$products = array();

			$myBasket = Shopware()->Modules()->Basket()->sGetBasket(); 
			$articles = array();

			if(is_array($myBasket) && is_array($myBasket['content'])) {
				$entityManager = Shopware()->Container()->get('models');
				$articleDetailsRepositoy = $entityManager->getRepository(Detail::class);

				foreach($myBasket['content'] as $content) {
					$tmp_article_id = $content['articleID'];

					//Cross-Selling Artikel aus der Datenbank auslesen
					$cross_selling_products = $this->loadCrossSellingProductsByArticleId($tmp_article_id);

					foreach($cross_selling_products as $prod) {
						$rel_article_id = $prod['relatedarticle'];

						if(!isset($articles[$rel_article_id])) {
							$tmp_article = Shopware()->Modules()->Articles()->sGetPromotionById('fix', 0, $rel_article_id, true);

							if(NULL != $tmp_article) {
								$articles[$rel_article_id] = $tmp_article;
							}
						}
					}


					if(is_array($articles) && count($articles) >= 5) {
						break;
					}
				}
			}

			foreach($articles as $art) {
					$products[] = $art;
			}


			$this->templateManager->assign('mvAjaxCartCrossSelling', $products);
		}

		// Cross-Selling Artikel aus Datenbank auslesen.
		public function loadCrossSellingProductsByArticleId($article_id) {
			$sql = 'SELECT `relatedarticle` FROM `s_articles_relationships` WHERE `articleID`=?';
			$result = Shopware()->Db()->fetchAll($sql, array($article_id));

		if (!empty($result)) {
			return $result;
		}

		return array();
	}

}