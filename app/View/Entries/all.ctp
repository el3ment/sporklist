<div class='ad'>
    <h2>What is a good man fart?</h2>
    <p>Sporklist combines all local, national, and social news about your favorite sports teams and puts it all in one beautiful location.</p>
    <p>We are hoping to improve - if you have any suggestions or comments please <a href='mailto:schlosser.shawn@gmail.com'>let us know</a>!</p>
</div>
<ul id='container-entities'>
    <?php foreach($entries as $entryData){
        echo $this->element('entry', array('entry' => $entryData));
    } ?>
</ul>
<!--
<a href='<?php echo $this->Html->url(array('controller' => 'entries', 'action' => 'all', ($pageIndex + 1))); ?>'>Next Page</a>
-->