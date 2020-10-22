<?require_once APP_DIR. '/View/todo-actions.php'?>
<div class="container">
  <div class="row">
  <?foreach ($tasks as $task):?>
    <div data-id="<?=$task[id]?>" class="col-lg-12 task-item">
      <div>
        <span><?=$task[created_at]?></span>
        <?if(is_array($users[$task['user_id']][0])):?>
              <span class="text-success"><?=$users[$task['user_id']][0]['lastname']?> <?=$users[$task['user_id']][0]['name']?> (<?=$users[$task['user_id']][0]['email']?>)</span>
              <?endif?>
        <h5 contenteditable="true" id="task-title-<?=$task[id]?>" class="card-title task-title editable-field"><?=$task[title]?></h5>
        <div contenteditable="true" id="task-description-<?=$task[id]?>" class="card-text task-description editable-field"><?=$task[description]?></div>
        <?if($task[important]):?><span id="task-important-<?=$task[id]?>" class="badge badge-danger">Срочно!</span><?endif?>
        <span class="badge <?=$statuses[$task[status_id]]['class']?>"><?=$statuses[$task[status_id]][title]?></span>
        <span data-id="<?=$task[id]?>" class="delete-btn delete-task bottom"></span>
      </div>
    </div>
  <?endforeach?>
  </div>
</div>

<div class="container">
  <div class="row">

<nav aria-label="Page navigation">
  <ul class="pagination">
    
    <?for($i=1;$i<=$nav['pageCount'];$i++):?>
      <li class="page-item<?if($i == $nav['currentPage']):?> active<?endif?>"><a class="page-link" href="/?view=list&page=<?=$i?>&sort=<?=$sort?>"><?=$i?></a></li>
    <?endfor?>
    
  </ul>
</nav>
</div>
</div>