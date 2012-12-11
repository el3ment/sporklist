<?php
class FeedsController extends Controller {
    var $name = 'Feeds';
    var $uses = array('Entries');
    
    var $primaryFeedUrl = 'http://pipes.yahoo.com/pipes/pipe.run?_id=972140ac54b27166f7d66abc0c1da269&_render=rss';
    //var $socialFeedUrl = 'http://pipes.yahoo.com/pipes/pipe.run?_id=ffa7faeb6922ae5f20c3db53276c0146&_render=rss';
    //var $nationalFeedUrl = 'http://pipes.yahoo.com/pipes/pipe.run?_id=0c23e1cd0998d792fdd847948f3f2df1&_render=rss';
    //var $localFeedUrl = 'http://pipes.yahoo.com/pipes/pipe.run?_id=684e1289175e867d0722b34301605398&_render=rss';
    
    function _parseDomain($url){
           
            $sourceUrl = parse_url($url);
            $sourceUrl['host'] = str_replace('www.', '', $sourceUrl['host']);
            $sourceUrl['domain'] = preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $sourceUrl['host'], $regs);
            $sourceUrl['domain'] = $regs['domain'];
            
            return $sourceUrl['domain'];
            
    }
    
    function _parseImage($html){
        
        $thumbnailUrl = explode('src="', $html);
        if(count($thumbnailUrl) > 1 ){
            $thumbnailUrl = explode('"', $thumbnailUrl[1]);
            $thumbnailUrl = $thumbnailUrl[0];
        }else{
            $thumbnailUrl = '';
        }
        
        return $thumbnailUrl;
        
    }
    
    function _parseItem($feedItem){

        // Type and Title
        $displayItem['type'] = 'article';
        $displayItem['title'] = strip_tags($feedItem['title']);
        
        // Use the GUID if it's a full URL, otherwise, leave it as whatever was in the 'link' property
        if(isset($feedItem['guid']['@']) && filter_var($feedItem['guid']['@'], FILTER_VALIDATE_URL) !== false){
            $displayItem['targetURL'] = $feedItem['guid']['@'];
        }else{
           $displayItem['targetURL'] = $feedItem['link']; 
        }
        
        if(filter_var($displayItem['targetURL'], FILTER_VALIDATE_URL) !== false){

            $displayItem['date'] = date('Y-m-d H:i:s', strtotime($feedItem['pubDate']));
            $displayItem['author'] = isset($feedItem['author']) ? $feedItem['author'] : '';
            $displayItem['description'] = isset($feedItem['description']) ? html_entity_decode($feedItem['description']) : '';
            
            $displayItem['sourceDomain'] = $this->_parseDomain($displayItem['targetURL']);
            $displayItem['thumbnailURL'] = $this->_parseImage($displayItem['description']);
            
            
            switch($displayItem['sourceDomain']){
                case 'flickr.com':
                    $type = 'image';
                    break;
                case 'instagram.com':
                    $displayItem['type'] = 'image';
                    $displayItem['author'] = $feedItem['media:credit']['@'];
                    $displayItem['thumbnailURL'] = $feedItem['media:thumbnail']['@url'];
                        
                    break;
                
                case 'youtube.com':
                    $displayItem['type'] = 'video';
                    
                    break;
                    
                case 'facebook.com':
                case 'twitter.com':
                    $displayItem['type'] = 'social';
                    
                    break;
                case 'cougarupdate.com':
                case 'deepshadesofblue.com':
                case 'sltrib.com':
                case 'cbssports.com':
                    $displayItem['thumbnailURL'] = '';
                    break;
            }
        }
        
        return $displayItem;
        
    }
    
    function _readData($feedUrl){
        App::uses('Xml', 'Utility', 'Helper');
        $feedArray = Xml::toArray(Xml::build($feedUrl));
        if(isset($feedArray)){
    
            $feedItems = $feedArray['rss']['channel']['item'];
            
            // In a rare case that it's a single item
            $feedItems = isset($feedItems['title']) ? array($feedItems) : $feedItems;
            
            return $feedItems;
        }
        
        return false;
    }
    
    function download(){

        $rawItems = $this->_readData($this->primaryFeedUrl);
        
        foreach($rawItems as $rawItem){
            $parsedItem = $this->_parseItem($rawItem);
            $parsedItem['sourceFeed'] = $this->primaryFeedUrl;
            $saveData = array('Entries' => $parsedItem);
            $this->Entries->create();
            $this->Entries->save($saveData);
            
            // save each record
        }
        
    }
}
?>