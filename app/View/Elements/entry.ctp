<?php $minutesAgo = floor((strtotime('now') - strtotime($entry['Entries']['date'])) / 60);
if($minutesAgo > 60){ 
	$hoursAgo = floor($minutesAgo / 60);
	if($hoursAgo > 24){ $timeAgo = floor($hoursAgo / 24); $unit = 'day'; }else{ $timeAgo = $hoursAgo; $unit = 'hour'; }
}else{ 
	$timeAgo = $minutesAgo; $unit = 'minute';
}
if($timeAgo > 1){ $unit = $unit . 's'; }
if($timeAgo < 0){ $timeString = 'added recently'; }else{ $timeString = 'added ' . $timeAgo . ' ' . $unit . ' ago'; } ?>

    <?php switch($entry['Entries']['type']){ 
    
        case 'article': ?>
            <li data-controller='entries' data-action='view' data-id='<?php echo $entry['Entries']['id'] ?>' data-id='<?php echo $entry['Entries']['id'] ?>' class='entry article <?php if(!$entry['Entries']['thumbnailURL']){ echo 'no-thumbnail'; } ?>'>
                <a class='' target='_blank' href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', $entry['Entries']['id'])); ?>'>
                    <?php if($entry['Entries']['thumbnailURL']){ ?>
                        <div class='thumbnail'><img src='<?php echo $entry['Entries']['thumbnailURL'] ?>' alt=''></div>
                    <?php } ?>
                    <div class='data'>
                        <h2><?php echo $entry['Entries']['title'] ?></h2>
                        <span class='supplementary'><?php echo $timeString ?> <?php if($entry['Entries']['author']){ ?>by <span class='source' data-href='<?php echo $entry['Entries']['author'] ?>'><?php echo $entry['Entries']['author'] ?></span><?php } ?> from <span class='source' data-href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['Entries']['targetURL'])))); ?>'><?php echo $entry['Entries']['sourceDomain'] ?></span></span>
                    </div>
                </a>
            </li>
            
        <?php break;
        case 'image': 
                $title = preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '', $entry['Entries']['title']);
                $title = $title == '' ? 'Untitled' : $title;
                $hashtagArray = explode(' ', $entry['Entries']['title']);
                foreach($hashtagArray as $key => $word){
                    if(substr($word, 0, 1) != '#'){
                        unset($hashtagArray[$key]);
                    }
                }
                $hashtagString = implode(' ', $hashtagArray);
            ?>
            <li data-controller='entries' data-action='view' data-id='<?php echo $entry['Entries']['id'] ?>' data-id='<?php echo $entry['Entries']['id'] ?>' class='entry image <?php if(!$entry['Entries']['thumbnailURL']){ echo 'no-thumbnail'; } ?>'>
                <a class='' target='_blank' href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', $entry['Entries']['id'])); ?>'>
                <?php if($entry['Entries']['thumbnailURL']){ ?>
                    <div class='thumbnail'><img width='120' height='120' src='<?php echo $entry['Entries']['thumbnailURL'] ?>' alt=''></div>
                <?php } ?>
                    <div class='data'>
                        <h2><?php echo ($title == 'Untitled' ? $title : '"' . $title . '"'); ?></h2>
                        <span class='supplementary'>by @<span class='author' data-href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['Entries']['targetURL'])))); ?>'><?php echo $entry['Entries']['author'] ?></span> on <span class='source' data-href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['Entries']['targetURL'])))); ?>'><?php echo $entry['Entries']['sourceDomain'] ?></span> <?php echo $hashtagString. ' ' ?></span>
                        <span class='supplementary'><?php echo $timeString ?></span>
                    </div>
                </a>
            </li>
        
        <?php break;
        case 'video': ?>
            
            <li data-controller='entries' data-action='view' data-id='<?php echo $entry['Entries']['id'] ?>' data-id='<?php echo $entry['Entries']['id'] ?>' class='entry video <?php if(!$entry['Entries']['thumbnailURL']){ echo 'no-thumbnail'; } ?>'>
                <a class='' target='_blank' href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', $entry['Entries']['id'])); ?>'>
                    <?php if($entry['Entries']['thumbnailURL']){ ?>
                        <div class='thumbnail'><img src='<?php echo $entry['Entries']['thumbnailURL'] ?>' alt=''></div>
                    <?php } ?>
                    <div class='data'>
                        <h2><?php echo $entry['Entries']['title'] ?></h2>
                        <span class='supplementary'><?php echo $timeString ?> from <span class='source' data-href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['Entries']['targetURL'])))); ?>'><?php echo $entry['Entries']['sourceDomain'] ?></span></span>
                    </div>
                </a>
            </li>
        <?php break;
        case 'social': ?>
            
            <li data-controller='entries' data-action='view' data-id='<?php echo $entry['Entries']['id'] ?>' data-id='<?php echo $entry['Entries']['id'] ?>' class='entry social <?php if(!$entry['Entries']['thumbnailURL']){ echo 'no-thumbnail'; } ?>'>
                <a class='' target='_blank' href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', $entry['Entries']['id'])); ?>'>
                    <?php if($entry['Entries']['thumbnailURL']){ ?>
                        <div class='thumbnail'><img src='<?php echo $entry['Entries']['thumbnailURL'] ?>' alt=''></div>
                    <?php } ?>
                    <div class='data'>
                        <h2><?php echo ($entry['Entries']['title'] == 'Untitled' ? $entry['Entries']['title'] : '"' . $entry['Entries']['title'] . '"'); ?></h2>
                        <span class='supplementary'><?php echo $timeString ?> on <span class='source' data-href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'view', urlencode(urlencode($entry['Entries']['targetURL'])))); ?>'><?php echo $entry['Entries']['sourceDomain'] ?></span></span>
                    </div>
                </a>
            </li>
        <?php break; ?>
<?php } ?>