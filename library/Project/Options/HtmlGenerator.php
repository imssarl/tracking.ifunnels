<?php

class Project_Options_HtmlGenerator {
	private $_smarty;
	private $config;
	private $_model;
	private $templatePath = "";
	
	public function init($params){
		
		$this->config=Zend_Registry::get( 'config' );
		$this->_smarty = new Core_Parsers_Smarty();		
		$this->templatePath = $this->config->path->relative->source.'advanced_options'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR;
		switch ($params['type_view']) {
			case "snippetsshow": // completed
				$this->_model = new Project_Options_Snippets();
				$this->viewSnippets($params);
				break;
				
			case "snippetstrack":
				$this->_model = new Project_Options_Snippets();
				$this->SnippetsTrack($params);
				break;	
				
			case "showarticles": // completed
				$this->_model = new Project_Options_SavedSelection();
				$this->viewSavedSelection($params);
				break;
				
			case "showarticlesnippets": // completed
				$this->_model = new Project_Options_SavedSelection();
				$this->viewSavedSelectionSnipets($params);
				break;	
							
			case "showvideo": // completed
				$this->viewVideo($params);
				break;

			default:
				break;
		}
	}

	/**
	 * Вывод видео
	 *
	 * @param array $params - $_GET
	 * @return false - если пустой id для видео
	 */
	private  function viewVideo($params = array()){

		$id = intval(Project_Options_Encode::decode($params['id']));
		$title = (isset($params['title']) && $params['title'] == 1)? 1:0;
		if (empty($id)){
			return false;
		}
		Project_Embed::getInstance()->onlyOne( $id )->withIds()->getList( $video );
		$templateData = array(
			"title" => $video['title'],
			"video" => $video['body'],
			"display_title" => $title,
		);
		$this->_smarty->template( $_strRes, $templateData, $this->templatePath.'view_video.tpl' );
		echo $_strRes;
		die();
	}

	
	/**
	 * Вывод статей для опции Saved Article Selection:
	 *
	 * @param array $params - $_GET
	 */
	private  function viewSavedSelection($params = array()) {
		$id = null;
		$category_id = null;
		$defcategory = null;

		if (isset($params['id']) && !is_numeric($params['id'])) {
			$id = intval(Project_Options_Encode::decode($params['id']));
		} elseif(isset($params['id'])) {
			$id = intval($params['id']);
		}
		if (isset($params['category_id']) && !is_numeric($params['category_id'])) {
			$category_id =  Project_Options_Encode::decode($params['category_id']) ;
		} elseif(isset($params['category_id'])) {
			$category_id =  $params['category_id'] ;
		}

		if (isset($params['defcategory']) && !is_numeric($params['defcategory'])) {
			$defcategory =  Project_Options_Encode::decode($params['defcategory']) ;
		} elseif(isset($params['defcategory'])) {
			$defcategory =  $params['defcategory'] ;
		}


		if (!empty($id)){ // вывод одной статьи

			$article = $this->_model->getArticleById($id);
			$templateData = array(
				"type_view" => "one",
				"article" => $article
			);
		}

		if (is_numeric($category_id)) { // вывод статей для одной категории
			$articles = $this->_model->getArticleByCategory($category_id);
			$templateData = array(
				"type_view" => "multi",
				"articles"  => $articles
			);
		}

		if ($category_id && isset($params['nb'])) { // вывод нескольких статей для одной категории рандомом
			$articles = $this->_model->getArticleByCategoryRandom($category_id,intval($params["nb"]));
			$templateData = array(
				"type_view" => "multi",
				"articles"  => $articles
			);
		}

		if ($defcategory && isset($params['keyword'])) { // вывод статьи для одной категории по ключевым словам
			$article = $this->_model->getArticleByKeywordAndCat($defcategory,$params["keyword"]);
			$templateData = array(
				"type_view" => "one",
				"article"  => $article
			);
		} elseif(isset($params['keyword'])){ // вывод статьи по ключевым словам
			$article = $this->_model->getArticleByKeywords($params["keyword"]);
			unset($article['body']);
			$templateData = array(
				"type_view" => "one",
				"article"  => $article,
				"no_body"  => 1
			);
		}

		$this->_smarty->template( $_strRes, $templateData, $this->templatePath.'view_article.tpl' );
		echo $_strRes;
		die();
	}


	/**
	 * Вывод статей для опции Saved Article Selection: по сниппетам
	 *
	 * @param unknown_type $params - $_GET
	 */
	private function viewSavedSelectionSnipets($params = array()){
		if (isset($params['category_id']) && !is_numeric($params['category_id'])) {
			$category_id =  Project_Options_Encode::decode($params['category_id']) ;
		} elseif(isset($params['category_id'])) {
			$category_id =  $params['category_id'] ;
		}

		if ($category_id && isset($params['nb']) && !isset($params['source'])) {
			$articles = $this->_model->getArticleByCategoryRandom($category_id,intval($params["nb"]));
			$templateData = array(
				"type_view" => "multi",
				"articles" 	=> $articles,
				"path" 		=> $this->config->engine->project_domain
			);
		}

		if ($category_id && isset($params['nb']) && isset($params['source'])) {
			$articles = $this->_model->getArticleBySource($category_id,intval($params["nb"]), intval($params['source']));
			$templateData = array(
				"type_view" => "multi",
				"articles" 	=> $articles,
				"path" 		=> $this->config->engine->project_domain
			);
		}

		$this->_smarty->template( $_strRes, $templateData, $this->templatePath.'view_article_snippets.tpl' );
		echo $_strRes;
		die();
	}

	/**
	 * Вывод информации для опции Rotating ad / snippets  
	 *
	 * @param unknown_type $params - $_GET
	 */
	private  function viewSnippets($params) {
		if (!empty($params['id']) && !is_numeric($params['id'])) {
			$id = intval(Project_Options_Encode::decode($params['id']));
		} elseif(isset($params['id'])) {
			$id = intval($params['id']);
		}
		if ($id) {
			$snippet = $this->_model->getSnippetShowPart($id);
			if (empty( $snippet ) ){
				return false;
			}
			$viewData = $this->_model->changeLinksWithTrackURLs( $snippet["link"], $snippet["id"] );
			$templateData = array(
				"html" => $viewData
			);
		} else {
			$templateData = array(
				"error" => 1
			);
		}
		$this->_smarty->template( $_strRes, $templateData, $this->templatePath.'view_snippets.tpl' );
		echo $_strRes;
		die();
	}

	private function SnippetsTrack($params){
		$ids = explode("-",$params["id"]);
		$pid = intval($ids[0]);
		$redirect = intval($ids[1]);
		$snippet = $this->_model->updatePartClicked($pid, $redirect);
		$redirectto = $this->_model->getTrackUrlToRedirect($redirect);
		header("location: $redirectto");
		die();
	}
}
?>