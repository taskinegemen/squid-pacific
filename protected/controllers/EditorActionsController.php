<?php

class EditorActionsController extends Controller
{

	public $response=null; 
	public $errors=null; 

	public function response($response_avoition=null){
		$response['result']=$response_avoition ? $response_avoition : $this->response;
		if ($this->errors) $response['errors']=$this->errors;
		echo json_encode($response);
	}
 
	public function error($domain='EditorActions',$explanation='Error', $arguments=null,$debug_vars=null ){
		$error=new error($domain,$explanation, $arguments,$debug_vars);
		$this->errors[]=$error; 
		return $error;
	}

	public function actionListBooks(){
		$books=Book::model()->findAll();
		
		foreach ($books as $key => $book) {
			$this->response['books'][]=$book->attributes;
		}

		return $this->response();

	}

	public function actionAddToUndoStack($id,$type,$undoAction, $undoParam){
		$username=Yii::app()->user->name;
	}

	public function get_templates(){
			return $templateBooks=Book::model()->findAll(array("condition"=>"workspace_id='layouts'"));
	}

	public function actionGetTemplates(){
		$templateBooks=$this->get_templates();

		foreach ($templateBooks as $key => $templateBook) {
			$return->bookTemplates[]=$templateBook->attributes;

		}
		return $this->response($return);


	}

	public function getPagesOfBook($bookId){
		$defaultChapter=Chapter::model()->find(array("condition"=>"book_id=:book_id","params"=> array('book_id' => $bookId )));

		$bookPages=Page::model()->findAll(array("condition"=>"chapter_id=:chapter_id","params"=> array('chapter_id' => $$defaultChapter->chapter_id )));

		if (!$bookPages) {
			$this->error('getPagesOfBook','Book not found');
			return false;

		}

		return $bookPages;

	}

	public function getPageComponents($page_id){
		$pages=Page::model()->findAll(array("condition"=>"page_id=:page_id","order"=>'`order` asc ,  created asc',"params"=> array('page_id' => $page_id )));
	}

	public function addTemplate(){
		
	}

	public function get_page_components($pageId){
		$page=Page::model()->findByPk($pageId);
		if (!$page) {
			$this->error("EA-GPCom","Page Not Found",func_get_args(),$page);
			return false;
		}
		
		

		$components= Component::model()->findAll(  array('condition' => 'page_id=:page_id',
			'params' =>  array(':page_id' =>  $pageId )  )  );


		if(!$components)  {
			$this->error("EA-GPCom","Component Not Found",func_get_args());
			return false;
		}

		$get_page_components= array();

		foreach ($components as $key => &$component) {
			$component->data=$component->get_data();
			$get_page_components[]=$component->attributes;
		}


		return $get_page_components;
	}


	public function actionGetPageComponents($pageId){
		$response=null;
		if($return=$this->get_page_components($pageId)){
			$response['components']=$return;
		} 
		return $this->response($response);
	}


	public function actionGetTemplatePages($template_book_id){
		$templatePages=getPagesOfBook($template_book_id);
		

		foreach ($templatePages as $key => $templatePage) {
			$return->templatePages[]=$templatePage->attributes;
		}

	}


	public function actionIndex(){
		$methodNames=get_class_methods('EditorActionsController');
		foreach ($methodNames as $key => $methodName) {
			# cdoe...
			
			$r = new ReflectionMethod('EditorActionsController', $methodName);
			$params = $r->getParameters();
			foreach ($params as $param) {
				$paramn->name=$param->getName();
				$paramn->isOptional=$param->isOptional() ? "Optional" : "Not Optional";
				if ($param->isDefaultValueAvailable()) $paramn->DefaultValue=var_export($param->getDefaultValue(),true);
				if(substr($methodName,0,6)=='action') 
					$parameters[$methodName]['params'][]=$paramn; 
			    //$param is an instance of ReflectionParameter
			    //echo $param->getName();
			    //echo $param->isOptional();
			    unset($paramn); 
			}
		}
		new dBug($parameters);
	}

	public function addChapter($bookId,$attributes=null){
		
		$book=Book::model()->findByPk($bookId);
		if (!$book) {
			$this->error("EA-AC1","Book Not Found",func_get_args(),$book);
			return false;
		}

		$new_chapter= new Chapter;
		$new_id=functions::new_id();
		
		$new_chapter->chapter_id=$new_id;
		$new_chapter->book_id=$book->book_id;

		$new_chapter->save();
		
		$result= Chapter::model()->findByPk($new_id);
		
 
		if(!$result) {
			$this->error("EA-AC1","Chapter couldn't Found!",func_get_args(),$new_id);
			return false;
		}
		$return->chapter=$result->attributes;
		$return->pages[]=$this->AddPage($result->chapter_id)->attributes;
		return $return;
	}



	public function actionAddChapter($bookId,$attributes=null)
	{
		if($return=$this->addChapter($bookId,$attributes)){
			$response['chapter']=$return->chapter;
			$response['pages']=$return->pages;
		}
		return $this->response($return);
	}




	public function addComponent($pageId,$attributes=null){
		
		$page=Page::model()->findByPk($pageId);

		if (!$page) {
			$this->error("EA-ACom","Page Not Found",func_get_args(),$page);
			return false;
		}

		$new_component= new Component;
		$new_id=functions::new_id();
		
		$new_component->id=$new_id;
		$new_component->page_id=$page->page_id;



		$component_attribs=json_decode($attributes);



		if($component_attribs->data->img->src  ) {
			$component_attribs->data->img->src = functions::compressBase64Image($component_attribs->data->img->src);
		}

		if($component_attribs->data->imgs)
			foreach ($component_attribs->data->imgs as $gallery_key => &$gallery_image) {
				if($gallery_image->src)
					$gallery_image->src=functions::compressBase64Image($gallery_image->src);
			}
		//know bug : component type validation


		$new_component->type=$component_attribs->type;
		$new_component->set_data($component_attribs->data);
		//new dBug($component_attribs);
		
		if(!$new_component->save()){
			$this->error("EA-ACom","Component Not Saved",func_get_args(),$new_component);
			return false;
		} 
		$result= Component::model()->findByPk($new_id);

		
		$result->data=$result->get_data();

		

		if(!$result)  {
			$this->error("EA-ACom","Component Not Found",func_get_args(),$new_component);
			return false;
		}


		return $result->attributes;

	}

	public function actionAddComponent($pageId,$attributes=null)
	{
		$response=false;

		if($return=$this->addComponent($pageId,$attributes)){
				$response['component']=$return; 
		}
		return $this->response($response);
	}


	public function addPage($chapterId,$attributes=null){
		$chapter=Chapter::model()->findByPk($chapterId);
		if (!$chapter) { 
			$this->error("EA-ACom","Chapter Not Found",func_get_args(),$chapter);
			return false;
		}

		$new_page= new Page;
		$new_id=functions::new_id();
		
		$new_page->page_id=$new_id;
		$new_page->chapter_id=$chapter->chapter_id;



		$new_page->save();
		
		$result= Page::model()->findByPk($new_id);
		

		if(!$result) {
			$this->error("EA-ACom","Page Not Found",func_get_args(),$new_component);
			return false;
		}

		return $result->attributes;

	}
	
	public function actionAddPage($chapterId,$attributes=null) 
	{
		if($return=$this->addPage($chapterId,$attributes)){
			$response['page']=$return;
		}
		return $this->response($response);
		
	}
	
	public function deleteChapter($chapterId){
		$result=Chapter::model()->findByPk($chapterId);
		if(!$result){
			$this->error("EA-DC","Chapter Not Found!");
			return false;
		} 
		if( $result->delete() ){return $chapterId;}

	}

	public function actionDeleteChapter($chapterId)
	{	

			return $this->response($this->deleteChapter($chapterId));


	}

	public function deleteComponent($componentId){
		$component=Component::model()->findByPk($componentId);
		if (!$component) {
			$this->error("EA-DCom","Component Not Found",func_get_args(),$component);
			return false;
		}

		if($component->model()->deleteByPk($componentId))
			return true;
		else {
			$this->error("EA-DCom","Component Could Not Deleted",func_get_args(),$componentId);
			return false;
		}
	}

	public function actionDeleteComponent($componentId)
	{
		$response= array( );

		if($return=$this->deleteComponent($componentId) ){
				$response['delete']=$componentId;
		}

		return $this->response($response);
	}


	public function deletePage($pageId){
		$result=Page::model()->findByPk($pageId);
		if(!$result){
			$this->error("EA-DP","Page Not Found!");
			return false;
		} 
		if( $result->delete() ){return $pageId;}

	}

	public function actionDeletePage($pageId)
	{
		return $this->response($this->deletePage($pageId));
	}

	public function UpdateChapter($chapterId,$title=null,$order=null){
		$chapter=Chapter::model()->findByPk($chapterId);
		if (!$chapter) {
			$this->error("EA-UChapter","Chapter Not Found",func_get_args(),$chapterId);
			return false;
		}
		$chapter->title=$title;
		$chapter->order=$order;


		if(!$chapter->save()){
			$this->error("EA-UChapter","Chapter Not Saved",func_get_args(),$chapterId);
			return false;
		}
		return $chapter->attributes;


	}


	public function actionUpdateChapter($chapterId,$title=null,$order=null)
	{

		$response=false;

		if($return=$this->UpdateChapter($chapterId,$title,$order) ){
				$response['chapter']=$return; 
		}

		return $this->response($response);

	}

	public function actionUpdateComponentData($componentId,$data_field,$data_value)
	{
		$this->render('updateComponent');
	}


	public function updateComponent($componentId,$jsonProperties){
		$component=Component::model()->findByPk($componentId);
		if (!$component) {
			$this->error("EA-UWholeCom","Component Not Found",func_get_args(),$component);
			return false;
		}

		// For revision: Save Component State for Undo etc. Here!


		$component_attribs=json_decode($jsonProperties);
		//know bug : component type validation
 


		$component->set_data($component_attribs->data);
		//new dBug($component_attribs);
		
		if(!$component->save()){
			$this->error("EA-UWholeCom","Component Not Saved",func_get_args(),$component);
			return false;
		} 
		 
		$result= Component::model()->findByPk($componentId);
		$result->data=$result->get_data();


		if(!$result)  {
			$this->error("EA-UWholeCom","Component Not Found",func_get_args(),$result);
			return false;
		}


		return $result->attributes;

	}

 

	public function actionUpdateWholeComponentData($componentId,$jsonProperties)
	{
		$response=false;

		if($return=$this->updateComponent($componentId,$jsonProperties) ){
				$response['component']=$return; 
		}

		return $this->response($response);

	}

	public function UpdatePage($pageId,$chapterId,$order){
		
		$page=Page::model()->findByPk($pageId);
		if (!$page) {
			$this->error("EA-UPage","Page Not Found",func_get_args(),$pageId);
			return false;
		}

		$page->chapter_id=$chapterId;
		$page->order=$order;


		if(!$page->save()){
			$this->error("EA-UPage","UPage Not Saved",func_get_args(),$pageId);
			return false;
		}
		
		return $page->attributes;


	}
 
	public function actionUpdatePage($pageId,$chapterId,$order)
	{

		$response=false;

		if($return=$this->UpdatePage($pageId,$chapterId,$order) ){
				$response['component']=$return; 
		}

		return $this->response($response);
	}

	public function SearchOnBook($currentPageId,$searchTerm=' '){

		$currentPage= Page::model()->findByPk($currentPageId) ;
		$chapter=Chapter:: model()->findByPk($currentPage->chapter_id) ;
		$bookId=$chapter->book_id;

		if(strlen($searchTerm)<2) {
			$this->error("EA-SearchOnBook","Too Short Seach Term",func_get_args(),$searchTerm);
			return null;
		}


		$sql="select * from component 
right join page  using (page_id) 
right join chapter using (chapter_id) 
right join book using (book_id) where book_id='$bookId' ;";
 		//echo $sql;

		$components = Component::model()->findAllBySql($sql);
		foreach ($components as $keyz => &$value) {
			$searchable="";
			if ($value->get_data())
			foreach ($value->get_data() as $key2 => $items) {
				foreach ($items as $key => $value2) {
					if($key!='css') $searchable.=serialize($value2);
				}
			}
 
			$searchable.=" ";


			$searchable=str_replace(array('O:',':','"','{','}',';'), ' ', $searchable);
			$searchable_small=functions::ufalt($searchable);
				
			if( 
			 	substr_count ( $searchable_small , functions::ufalt($searchTerm) )==0 
			 ) 
				unset($components[$keyz]);
			else {


				$value->data = $value->get_data();
				$value=$value->attributes;
 
				$value[search]->searchable=$searchable;
				$value[search]->searchTerm=$searchTerm;
				$value[search]->position=strpos($searchable_small,functions::ufalt($searchTerm));

				$value[search]->next_space_position= strpos($searchable, " ", $value[search]->position + strlen($searchTerm)+1 );
				

				$value[search]->previous_space_position= strrpos(substr($searchable,0,$value[search]->position),' ' );



				$value[search]->similar_result=substr($searchable,$value[search]->previous_space_position+1,  $value[search]->next_space_position - $value[search]->previous_space_position);
				$value[search]->similar_result_old=substr($searchable,$value[search]->position,  $value[search]->next_space_position - $value[search]->position);
				

			}
		
		} 
		//new dBug($components);
		usort($components,'sortify');

		return $components;



	}

 
	public function actionSearchOnBook($currentPageId,$searchTerm=' '){
		

		$response=false;

		if($return=$this->SearchOnBook($currentPageId,$searchTerm) ){
				$response['components']=$return; 
		}

		return $this->response($response);
		
	}



	public function actionExportBook($bookId=null){
		$book=Book::model()->findByPk($bookId);
		$ebook=new epub3($book);
		if ($ebook) readfile($ebook->download() );
	}



	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}

function sortify($a,$b){
	if( levenshtein($a[search]->similar_result,$a[search]->searchTerm) > levenshtein($b[search]->similar_result,$b[search]->searchTerm) ){
		return 1;
	}
	else return -1;
}