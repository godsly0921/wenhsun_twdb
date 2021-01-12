<div class="panel panel-default" style="width=100%; overflow-y:scroll;">
	<div class="panel-body">
		<?php if(!empty($model_author_event->id)){?>
			<div class="form-group text-center">
				<button type="button" class="btn btn-danger btn-lg" onclick="delGallery(this)">取消</button>
			</div>
		<?php }?>
		<input type="hidden" id="id_<?=$i?>" name="BookAuthorEvent[<?=$i?>][id]" value="<?=$model_author_event->id?>">
		<div class="form-group">
			<?php echo $form->labelEx($model_author_event,'title', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-8">
				<input type="text" id="title_<?=$i?>" size="200", maxlength="200" name="BookAuthorEvent[<?=$i?>][title]" class="form-control" value="<?=$model_author_event->title?>">
			</div>
			<?php echo $form->error($model_author_event,'title'); ?>
		</div>
		<div class="form-group">
			<?php echo $form->labelEx($model_author_event,'description', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-8">
				<textarea id="description_<?=$i?>" rows="6" cols="50" class="form-control" name="BookAuthorEvent[<?=$i?>][description]" value="<?=$model_author_event->description?>"><?=$model_author_event->description?></textarea>
			</div>
			<?php echo $form->error($model_author_event,'description'); ?>
		</div>
		<!--
		<div class="form-group">
			<?php #echo $form->labelEx($model_author_event,'image_link', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-8">
				<select class="form-control image_link" id="image_link_<?=$i?>" name="BookAuthorEvent[<?=$i?>][image_link]">
	                <option value="">請選擇</option>
	                <?php 
	                // foreach ($single as $key => $value){
	                	// $data_tokens = array();
	                	// array_push($data_tokens, $value['single_id']);
	                	// if(!empty($value['keyword'])) array_push($data_tokens, $value['keyword']);
	                	// if(!empty($value['people_info'])) array_push($data_tokens, $value['people_info']);
	                	// if(!empty($value['event_name'])) array_push($data_tokens, $value['event_name']);
	                	// if(!empty($value['filming_location'])) array_push($data_tokens, $value['filming_location']);
	                	// if(!empty($value['filming_date'])) array_push($data_tokens, $value['filming_date']);
	                	// if(!empty($value['filming_name'])) array_push($data_tokens, $value['filming_name']);
	                	// if(!empty($value['object_name'])) array_push($data_tokens, $value['object_name']);
	                	// if(!empty($value['people_info'])) array_push($data_tokens, $value['people_info']);
	                	// if(!empty($value['description'])) array_push($data_tokens, $value['description']);
	                	// if(!empty($value['photo_source'])) array_push($data_tokens, $value['photo_source']);
	                	// if(!empty($value['filming_date_text'])) array_push($data_tokens, $value['filming_date_text']);
	                ?>
	                    <option value="<?#=$value['single_id']?>" data-tokens="<?#=implode(",",$data_tokens)?>" data-content="<img class='data_thumbnail lazyload' width='100' height='auto'<?php #if($model_author_event->image_link==$value['single_id']) echo "src='".DOMAIN."image_storage/P/". $value['single_id'] .".jpg'"?> data-src='<?#=DOMAIN."image_storage/P/".$value['single_id']?>.jpg' data-original='<#?=DOMAIN."image_storage/P/".$value['single_id']?>.jpg'></img> <?#=$value['single_id']?>" <?#=$model_author_event->image_link==$value['single_id']?'selected':''?> <?#=$model_author_event->image_link==$value['single_id']?'selected':''?>><?#=$value['single_id']?></option>
	                <?php #}?>
	            </select>
			</div>
			<?php #echo $form->error($model_author_event,'image_link'); ?>
		</div>
		-->
		<div class="form-group">
			<?php echo $form->labelEx($model_author_event,'year', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-8">
				<input type="text" id="year_<?=$i?>" size="4", maxlength="4" name="BookAuthorEvent[<?=$i?>][year]" required="required" data-date-format="yyyy" class="form-control datepicker event_year" value="<?=$model_author_event->year?>" placeholder="年表-年">
			</div>
			<?php echo $form->error($model_author_event,'year'); ?>
		</div>

		<div class="form-group">
			<?php echo $form->labelEx($model_author_event,'month', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-8">
				<input type="text" id="month_<?=$i?>" size="2", maxlength="2" name="BookAuthorEvent[<?=$i?>][month]" data-date-format="mm" class="form-control datepicker event_month" value="<?=$model_author_event->month?>" placeholder="年表-月">
			</div>
			<?php echo $form->error($model_author_event,'month'); ?>
		</div>

		<div class="form-group">
			<?php echo $form->labelEx($model_author_event,'day', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-8">
				<input type="text" id="day_<?=$i?>" size="2", maxlength="2" name="BookAuthorEvent[<?=$i?>][day]" data-date-format="dd" class="form-control datepicker event_day" value="<?=$model_author_event->day?>" placeholder="年表-日">
			</div>
			<?php echo $form->error($model_author_event,'day'); ?>
		</div>
	</div>
</div>