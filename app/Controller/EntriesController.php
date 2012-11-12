<?php
class EntriesController extends Controller {
    var $name = 'Entries';
    var $primaryFeedUrl = 'http://pipes.yahoo.com/pipes/pipe.run?_id=972140ac54b27166f7d66abc0c1da269&_render=rss';
    var $socialFeedUrl = 'http://pipes.yahoo.com/pipes/pipe.run?_id=ffa7faeb6922ae5f20c3db53276c0146&_render=rss';
    var $nationalFeedUrl = 'http://pipes.yahoo.com/pipes/pipe.run?_id=0c23e1cd0998d792fdd847948f3f2df1&_render=rss';
    var $localFeedUrl = 'http://pipes.yahoo.com/pipes/pipe.run?_id=684e1289175e867d0722b34301605398&_render=rss';
    
    function view($id, $title){
        echo $id;
    }
    
    
    function all(){
        
        App::uses('Xml', 'Utility', 'Helper');
        $feedArray = Xml::toArray(Xml::build($this->primaryFeedUrl));
        $feedItems = $feedArray['rss']['channel']['item'];
        $feedItems = isset($feedItems['title']) ? array($feedItems) : $feedItems;
        foreach($feedItems as $feedItem){
            
            $type = 'article';
            $title = strip_tags($feedItem['title']);
            
            $link = isset($feedItem['link']) ? $feedItem['link'] : $feedItem['guid']['@'];
            if(isset($feedItem['guid']['@']) && filter_var($feedItem['guid']['@'], FILTER_VALIDATE_URL) !== false){
                $link = $feedItem['guid']['@'];
            }
            
            
            if(filter_var($link, FILTER_VALIDATE_URL) !== false){
                
                $description = (isset($feedItem['description']) ? html_entity_decode($feedItem['description']) : '');
                $date = $feedItem['pubDate'];
                $author = isset($feedItem['author']) ? $feedItem['author'] : null;
                $sourceUrl = parse_url($link);
                if(!isset($sourceUrl['host'])){
                    var_dump($feedItem);
                }
                $sourceUrl['host'] = str_replace('www.', '', $sourceUrl['host']);
                $sourceUrl['domain'] = preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $sourceUrl['host'], $regs);
                $sourceUrl['domain'] = $regs['domain'];
                
                $thumbnail = null;
                $thumbnailUrl = explode('src="', $description);
                if(count($thumbnailUrl) > 1 ){
                    $thumbnailUrl = explode('"', $thumbnailUrl[1]);
                    $thumbnailUrl = $thumbnailUrl[0];
                    if(filter_var($thumbnailUrl, FILTER_VALIDATE_URL) !== false){
                        $thumbnail = array(
                            'url' => $thumbnailUrl
                        );
                    }
                }
                
                switch($sourceUrl['domain']){
                    case 'flickr.com':
                        $type = 'image';
                        break;
                    case 'instagram.com':
                        $type = 'image';
                        $author = $feedItem['media:credit']['@'];
                        $thumbnail = array(
                            'url' => $feedItem['media:thumbnail']['@url'],
                            'width' => $feedItem['media:thumbnail']['@width'],
                            'height' => $feedItem['media:thumbnail']['@height']);
                        break;
                    
                    case 'youtube.com':
                        $type = 'video';
                        
                        break;
                        
                    case 'facebook.com':
                    case 'twitter.com':
                        $type = 'social';
                        
                        break;
                    case 'cougarupdate.com':
                    case 'deepshadesofblue.com':
                        $thumbnail = null;
                        break;
                }
                
                $displayItem[] = array(
                    'title' => $title,
                    'description' => '',
                    'link' => $link,
                    'author' => $author,
                    'thumbnail' => $thumbnail,
                    'date' => $date,
                    'type' => $type,
                    'source' => $sourceUrl['domain']);
            }
                
        }
		$this->set('entries', $displayItem);
	}
}
?>