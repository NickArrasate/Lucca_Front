<div class="paging">
 <?php echo $paginator->prev('<< '.__('previous', true), array('url' => $this->passedArgs), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers(array('url' => $this->passedArgs));?>
 <?php echo $paginator->next(__('next', true).' >>', array('url' => $this->passedArgs), null, array('class'=>'disabled'));?></div>