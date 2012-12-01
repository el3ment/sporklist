<div class='ad'>
    <h2>What is a good man fart?/h2>
    <p>Sporklist combines all local, national, and social news about your favorite sports teams and puts it all in one beautiful location.</p>
    <p>We are hoping to improve - if you have any suggestions or comments please <a href='mailto:schlosser.shawn@gmail.com'>let us know</a>!</p>
</div>
<ul id='container-entities'>

    <?php foreach($entries as $entry){ ?>
    
    <?php $minutesAgo = floor((strtotime('now') - strtotime($entry['date'])) / 60);
    if($minutesAgo > 60){ 
		$hoursAgo = floor($minutesAgo / 60);
    	if($hoursAgo > 24){ $timeAgo = floor($hoursAgo / 24); $unit = 'day'; }else{ $timeAgo = $hoursAgo; $unit = 'hour'; }
    }else{ 
		$timeAgo = $minutesAgo; $unit = 'minute';
	}
	if($timeAgo > 1){ $unit = $unit . 's'; }
    if($timeAgo < 0){ $timeString = 'added recently'; }else{ $timeString = 'added ' . $timeAgo . ' ' . $unit . ' ago'; } ?>
    
        <?php switch($entry['type']){ 
        
            case 'article': ?>
                <li data-controller='entries' data-action='view' data-id='<?php echo $entry['id'] ?>' data-id='<?php echo $entry['id'] ?>' class='entry article <?php if(!$entry['thumbnail']){ echo 'no-thumbnail'; } ?> <?php echo ($entry['isRead'] ? 'read' : '') ?>'>
                    <a class='' target='_blank' href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['link'])))); ?>'>
                        <?php if($entry['thumbnail']){ ?>
                            <div class='thumbnail'><img src='<?php echo $entry['thumbnail']['url'] ?>' alt=''></div>
                        <?php } ?>
                        <div class='data'>
                            <h2><?php echo $entry['title'] ?></h2>
                            <span class='supplementary'><?php echo $timeString ?> <?php if($entry['author']){ ?>by <span class='source' data-href='<?php echo $entry['author'] ?>'><?php echo $entry['author'] ?></span><?php } ?> from <span class='source' data-href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['link'])))); ?>'><?php echo $entry['source'] ?></span></span>
                        </div>
                    </a>
                </li>
                
            <?php break;
            case 'image': 
                    $title = preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '', $entry['title']);
                    $title = $title == '' ? 'Untitled' : $title;
                    $hashtagArray = explode(' ', $entry['title']);
                    foreach($hashtagArray as $key => $word){
                        if(substr($word, 0, 1) != '#'){
                            unset($hashtagArray[$key]);
                        }
                    }
                    $hashtagString = implode(' ', $hashtagArray);
                ?>
                <li data-controller='entries' data-action='view' data-id='<?php echo $entry['id'] ?>' data-id='<?php echo $entry['id'] ?>' class='entry image <?php if(!$entry['thumbnail']){ echo 'no-thumbnail'; } ?> <?php echo ($entry['isRead'] ? 'read' : '') ?>'>
                    <a class='' target='_blank' href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['link'])))); ?>'>
                    <?php if($entry['thumbnail']){ ?>
                        <div class='thumbnail'><img width='120' height='120' src='<?php echo $entry['thumbnail']['url'] ?>' alt=''></div>
                    <?php } ?>
                        <div class='data'>
                            <h2><?php echo ($title == 'Untitled' ? $title : '"' . $title . '"'); ?></h2>
                            <span class='supplementary'>by @<span class='author' data-href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['link'])))); ?>'><?php echo $entry['author'] ?></span> on <span class='source' data-href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['link'])))); ?>'><?php echo $entry['source'] ?></span> <?php echo $hashtagString. ' ' ?></span>
                            <span class='supplementary'><?php echo $timeString ?></span>
                        </div>
                    </a>
                </li>
            
            <?php break;
            case 'video': ?>
                
                <li data-controller='entries' data-action='view' data-id='<?php echo $entry['id'] ?>' data-id='<?php echo $entry['id'] ?>' class='entry video <?php if(!$entry['thumbnail']){ echo 'no-thumbnail'; } ?> <?php echo ($entry['isRead'] ? 'read' : '') ?>'>
                    <a class='' target='_blank' href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['link'])))); ?>'>
                        <?php if($entry['thumbnail']){ ?>
                            <div class='thumbnail'><img src='<?php echo $entry['thumbnail']['url'] ?>' alt=''></div>
                        <?php } ?>
                        <div class='data'>
                            <h2><?php echo $entry['title'] ?></h2>
                            <span class='supplementary'><?php echo $timeString ?> from <span class='source' data-href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['link'])))); ?>'><?php echo $entry['source'] ?></span></span>
                        </div>
                    </a>
                </li>
            <?php break;
            case 'social': ?>
                
                <li data-controller='entries' data-action='view' data-id='<?php echo $entry['id'] ?>' data-id='<?php echo $entry['id'] ?>' class='entry social <?php if(!$entry['thumbnail']){ echo 'no-thumbnail'; } ?> <?php echo ($entry['isRead'] ? 'read' : '') ?>'>
                    <a class='' target='_blank' href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['link'])))); ?>'>
                        <?php if($entry['thumbnail']){ ?>
                            <div class='thumbnail'><img src='<?php echo $entry['thumbnail']['url'] ?>' alt=''></div>
                        <?php } ?>
                        <div class='data'>
                            <h2><?php echo ($entry['title'] == 'Untitled' ? $entry['title'] : '"' . $entry['title'] . '"'); ?></h2>
                            <span class='supplementary'><?php echo $timeString ?> on <span class='source' data-href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['link'])))); ?>'><?php echo $entry['source'] ?></span></span>
                        </div>
                    </a>
                </li>
            <?php break; ?>
        <?php } ?>
        
    <?php } ?>
</ul>