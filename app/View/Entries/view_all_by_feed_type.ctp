<ul id='container-entities'>
    <?php foreach($entries as $entryData){
        echo $this->element('entry', array('entry' => $entryData));
    } ?>
</ul>
<!--
<a href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'viewAllByFeedType', $feedType, ($pageIndex + 1))); ?>'>Next Page</a>
-->