<section>
	<div class="row">
							<div class="col-md-10">
								<!-- BOX -->
								<div class="box border red" id="formWizard">
									<div class="box-title">
										<h4><i class="fa fa-bars"></i><?php _e('Yayınlama'); ?> - <span class="stepHeader"><?php _e('Aşama'); ?> 1 / 5</h4>
									</div>
									<div class="box-body form">
										<!-- <form id="wizForm" action="#" class="form-horizontal" > -->
									<?php 
										$form=$this->beginWidget('CActiveForm', array(
											'id'=>'wizForm',
											'enableAjaxValidation'=>false,
											'htmlOptions'=>array(
										                     //   'onsubmit'=>"return false;",/* Disable normal form submit */
										                       //'onkeypress'=>" if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
										                     ),
										)); 
										?>
										<div class="wizard-form">
										   <div class="wizard-content">
											  <ul class="nav nav-pills nav-justified steps">
												 <li>
													<a href="#host" data-toggle="tab" class="wiz-step">
													<span class="step-number">1</span>
													<span class="step-name"><i class="fa fa-check"></i> <?php _e('Sunucu Bilgileri'); ?> </span>   
													</a>
												 </li>
												 <li>
													<a href="#book" data-toggle="tab" class="wiz-step">
													<span class="step-number">2</span>
													<span class="step-name"><i class="fa fa-check"></i> <?php _e('Kaynak Bilgileri'); ?> </span>   
													</a> 
												 </li>
												 <li>
													<a href="#category" data-toggle="tab" class="wiz-step">
													<span class="step-number">3</span>
													<span class="step-name"><i class="fa fa-check"></i> <?php _e('Kategori Bilgileri'); ?> </span>   
													</a> 
												 </li>
												 <li>
													<a href="#money" data-toggle="tab" class="wiz-step">
													<span class="step-number">4</span>
													<span class="step-name"><i class="fa fa-check"></i> <?php _e('Fiyat Bilgileri'); ?> </span>   
													</a> 
												 </li>
												 <li>
													<a href="#confirm" data-toggle="tab" class="wiz-step">
													<span class="step-number">5</span>
													<span class="step-name"><i class="fa fa-check"></i> <?php _e('Onay'); ?> </span>   
													</a> 
												 </li>
											  </ul>
											  <div id="bar" class="progress progress-striped progress-sm active" role="progressbar">
												 <div class="progress-bar progress-bar-warning"></div>
											  </div>
											  <div class="tab-content">
												 <div class="alert alert-danger display-none">
													<a class="close" aria-hidden="true" href="#" data-dismiss="alert">×</a>
													Your form has errors. Please correct them to proceed.
												 </div>
												 <div class="alert alert-success display-none">
													<a class="close" aria-hidden="true" href="#" data-dismiss="alert">×</a>
													Your form validation is successful!
												 </div>

												 

												

												 <div class="tab-pane active" id="host">
													
													<?php
													foreach ($hosts as $key => $host) {
														$contentHostIds[$host->hosting_client_id]=$host->hosting_client_IP.' : '.$host->hosting_client_port;
													}
													$contentHostIds['GIWwMdmQXL']='cloud.lindneo.com : 2222';
													?>


													<?php echo $form->hiddenField($model,'contentId'); ?>
													<?php echo $form->hiddenField($model,'organisationId'); ?>
													<?php echo $form->hiddenField($model,'organisationName'); ?>
													<?php echo $form->hiddenField($model,'created'); ?>
												

													<div class="form-group">
														<label  class="col-md-3 control-label">
														<?php _e("Sunucular"); ?><!-- <span class="required">*</span> -->
														</label>
														<div class="col-md-9">
															<!-- <div class="checker" id="uniform-PublishBookForm_contentHostId_2">
																<span class="checked">
																	<input class="uniform" id="host_2" checked="checked" value="GIWwMdmQXL" type="checkbox" name="host[]">
																</span>
															</div>
															<label for="host_2">cloud.lindneo.com : 2222</label>
															<br> -->
														<?php echo $form->checkBoxList($model,'contentHostId',$contentHostIds,array('class'=>'uniform','name'=>'host')); ?>
														</div>
													</div>
												</div>
												<div class="tab-pane" id="book">
																

													<div class="form-group">
														<label class="control-label col-md-3" for="PublishBookForm_contentTitle"><?php _e('Eser Adı') ?><span class="required">*</span></label>
														
														<div class="col-md-4">
															<?php echo $form->textField($model,'contentTitle',array('class'=>'form-control','name'=>'contentTitle','placeholder'=>__("Lütfen bir isim girin!"))); ?>
															<span class="error-span"></span>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-md-3" for="PublishBookForm_contentType"><?php _e('Eser Tipi') ?><span class="required">*</span></label>
														<div class="col-md-4">
															<?php echo $form->radioButtonList($model,'contentType',array('epub'=>'Epub','epdf'=>'Epdf','pdf'=>'pdf'),array('class'=>'uniform','name'=>'contentType')); ?>
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-md-3"><?php _e('Eser Açıklaması'); ?><span class="required">*</span></label>
														<div class="col-md-4">
															<?php echo $form->textArea($model,'contentExplanation',array('class'=>'form-control','name'=>'contentExplanation','placeholder'=>__('Lütfen bir açıklama girin!'))); ?>
														  <span class="error-span"></span>
													   </div>
													</div>

													<div id="detailed">
													<div class="form-group">
														<label for="" class="control-label col-md-3"><?php _e('Özet'); ?></label>
														<div class="col-md-4">
															<?php echo $form->textArea($model,'abstract',array('class'=>'form-control','name'=>'abstract')); ?>
															<span class="error-span"></span>
														</div>
													</div>
													<div class="form-group">
														<label for="" class="control-label col-md-3"><?php _e('Dil'); ?></label>
														<div class="col-md-4">
															<?php echo $form->radioButtonList($model,'language',array('Türkçe'=>'Türkçe','English'=>'English','Arabic'=>'العربية','Asturian'=>'Asturianu','German'=>'Deutsch','Russian'=>'Русский язык','Spanish'=>'Español'),array('class'=>'uniform','name'=>'language')); ?>
															<span class="error-span"></span>
														</div>
													</div>
													<div class="form-group">
														<label for="" class="control-label col-md-3"><?php _e('Konu'); ?></label>
														<div class="col-md-4">
															<?php echo $form->textArea($model,'subject',array('class'=>'form-control','name'=>'subject')); ?>
															<span class="error-span"></span>
														</div>
													</div>
													<div class="form-group">
														<label for="" class="control-label col-md-3"><?php _e('Edition'); ?></label>
														<div class="col-md-4">
															<?php echo $form->textField($model,'edition',array('class'=>'form-control','name'=>'edition')); ?>
															<span class="error-span"></span>
														</div>
													</div>
													<div class="form-group">
														<label for="" class="control-label col-md-3"><?php _e('Yazar'); ?></label>
														<div class="col-md-4">
															<?php echo $form->textField($model,'author',array('class'=>'form-control','name'=>'author')); ?>
															<span class="error-span"></span>
														</div>
													</div>
													<div class="form-group">
														<label for="" class="control-label col-md-3"><?php _e('Çeviren'); ?></label>
														<div class="col-md-4">
															<?php echo $form->textField($model,'translator',array('class'=>'form-control','name'=>'translator')); ?>
															<span class="error-span"></span>
														</div>
													</div>
													<div class="form-group">
														<label for="" class="control-label col-md-3"><?php _e('ISSN/ISBN'); ?></label>
														<div class="col-md-4">
															<?php echo $form->textField($model,'issn',array('class'=>'form-control','name'=>'issn')); ?>
															<span class="error-span"></span>
														</div>
													</div>
													</div>
													<div class="form-group">
														<div class="col-md-3"></div>
														<div class="col-md-4">
															<a href="javascript:;" class="btn btn-primary detailBtn">
																<?php _e('Detay'); ?> <i class="fa fa-arrow-circle-down"></i>
														   </a>
														</div>
													</div>
												</div>
												<div class="tab-pane active" id="category">
													
													<?php
													foreach ($categories as $key => $category) {
														$categoryIds[$category->category_id]=$category->category_name;
													}
													?>

													<div class="form-group">
														<label  class="col-md-3 control-label">
														<?php _e("Kategoriler"); ?>
														</label>
														<div class="col-md-9">
														<?php echo $form->checkBoxList($model,'categories',$categoryIds,array('class'=>'uniform','name'=>'categories')); ?>
														</div>
													</div>
												</div>
												<div class="tab-pane" id="money">

													<div class="form-group">
														<label for="PublishBookForm_contentIsForSale" class="control-label col-md-3"><?php _e('Is For Sale?'); ?><span class="required">*</span></label>
														<div class="col-md-4">
														<?php echo $form->radioButtonList($model,'contentIsForSale',array('Yes'=>__('Evet'),'Free'=>__('Hayır')),array('class'=>'uniform','name'=>'contentIsForSale')); ?>
														</div>
													</div>

													<div class="form-group">
													<label for="PublishBookForm_contentPriceCurrencyCode" class="control-label col-md-3"><?php _e('Para Birimi'); ?><span class="required">*</span></label>
													<div class="col-md-4">
													<?php echo $form->radioButtonList($model,'contentPriceCurrencyCode',array('949'=>'Türk Lirası','998'=>'Dolar','978'=>'Euro'),array('class'=>'uniform','name'=>'contentCurrency')); ?>
													</div></div>

													<div class="form-group">
														<label for="PublishBookForm_contentPrice" class="control-label col-md-3"><?php _e('Eser Fiyatı') ?><span class="required">*</span></label>
														<div class="col-md-4">
														<?php echo $form->textField($model,'contentPrice',array('class'=>'form-control','name'=>'contentPrice','placeholder'=>__('Lütfen bir fiyat girin!'))); ?>
													</div>
													</div>

													<div class="form-group">
														<label for="PublishBookForm_contentReaderGroup" class="control-label col-md-3"><?php _e('Reader Group'); ?><span class="required">*</span></label>
														<div class="col-md-4">
															<?php echo $form->textField($model,'contentReaderGroup',array('class'=>'form-control','name'=>'contentReaderGroup')); ?>
														</div>
													</div>
												 </div>
												 <div class="tab-pane" id="confirm">
												 	<h3 class="block">Detay Özeti <a href="javascript:;" class="btn btn-primary detayRevBtn">
																<i class="fa fa-arrow-circle-down"></i>
														   </a></h3>
													<div class="well" id="detayRev">
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e("Özet"); ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="abstract"></p>
														   </div>
														</div>
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e('Dil') ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="language"></p>
														   </div>
														</div>
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e('Konu') ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="subject"></p>
														   </div>
														</div>
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e('Edition'); ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="edition"></p>
														   </div>
														</div>
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e('Yazar'); ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="author"></p>
														   </div>
														</div>
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e('Çeviren'); ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="translator"></p>
														   </div>
														</div>
														
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e('ISSN/ISBN') ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="issn"></p>
														   </div>
														</div>
													</div>
													<h3 class="block">Yayınlama Özeti</h3>
													<div class="well">
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e("Sunucu"); ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="host"></p>
														   </div>
														</div>
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e('Eser Adı') ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="contentTitle"></p>
														   </div>
														</div>
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e('Eser Tipi') ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="contentType"></p>
														   </div>
														</div>
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e('Eser Açıklaması'); ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="contentExplanation"></p>
														   </div>
														</div>
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e('Kategoriler'); ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="categories"></p>
														   </div>
														</div>
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e('Is For Sale?'); ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="contentIsForSale"></p>
														   </div>
														</div>
														
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e('Eser Fiyatı') ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="contentPrice"></p>
														   </div>
														</div>
														<div class="form-group">
														   <label class="control-label col-md-3"><?php _e('Reader Group'); ?>:</label>
														   <div class="col-md-4">
															  <p class="form-control-static" data-display="contentReaderGroup"></p>
														   </div>
														</div>
														<div class="form-group">
														   <div class="col-md-2">
														   </div>
														   <div class="col-md-1" style="text-align:right">
															  <input type="checkbox" id="rights" name="rights" class="uniform" value="accepted" checked /> 
														   </div>
														   
														   <label class="control-label col-md-3" style="text-align:left"> Kullanıcı Sözleşmesini Kabul Ediyorum.</label>
														</div>
													</div>
													
												 </div>
											  </div>
										   </div>
										   <div class="wizard-buttons">
											  <div class="row">
												 <div class="col-md-12">
													<div class="col-md-offset-3 col-md-9">
													   <a href="javascript:;" class="btn btn-default prevBtn">
														<i class="fa fa-arrow-circle-left"></i> <?php _e('Geri'); ?> 
													   </a>
													   <a href="javascript:;" class="btn btn-primary nextBtn">
														<?php _e('Devam'); ?> <i class="fa fa-arrow-circle-right"></i>
													   </a>
													   <a href="javascript:;" class="btn btn-success submitBtn">
														<?php _e('Yayınla'); ?> <i class="fa fa-arrow-circle-right"></i>
													   </a>                            
													</div>
												 </div>
											  </div>
										   </div>
										</div>
									 <?php $this->endWidget(); ?>
									 <!-- </form> -->
									</div>
								</div>
								<!-- /BOX -->
							</div>
						</div>
</section>
<script>
		jQuery(document).ready(function() {		
			App.setPage("wizards_validations");  //Set current page
			App.init(); //Initialise plugins and elements
			FormWizard.init();
		});
	</script>

<script type="text/javascript">
 	$('form').addClass('form-horizontal');

 	var bookId="<?php echo $bookId; ?>";

</script>