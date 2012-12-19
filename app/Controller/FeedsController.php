<?php
class FeedsController extends Controller {
    var $name = 'Feeds';
    var $uses = array('Entries');
    
    var $primaryFeedUrl = 'http://pipes.yahoo.com/pipes/pipe.run?_id=972140ac54b27166f7d66abc0c1da269&_render=rss';
    //var $socialFeedUrl = 'http://pipes.yahoo.com/pipes/pipe.run?_id=ffa7faeb6922ae5f20c3db53276c0146&_render=rss';
    //var $nationalFeedUrl = 'http://pipes.yahoo.com/pipes/pipe.run?_id=0c23e1cd0998d792fdd847948f3f2df1&_render=rss';
    //var $localFeedUrl = 'http://pipes.yahoo.com/pipes/pipe.run?_id=684e1289175e867d0722b34301605398&_render=rss';
    
    // From StackOverflow
    function _multidimentionalImplode($array){
        $out = "";
        $g = " ";
        $c = count($array);
        $i = 0;
        foreach ($array as $val){
            if (is_array($val)){
                $out .= $this->_multidimentionalImplode($val);
            } else {
                $out .= (string)$val;
            }
            $i++;
            if ($i<$c){
                $out .= $g;
            }
        }
        return $out;
    }
    
    function _readDomain($url){
           
            $sourceUrl = parse_url($url);
            $sourceUrl['host'] = str_replace('www.', '', $sourceUrl['host']);
            $sourceUrl['domain'] = preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $sourceUrl['host'], $regs);
            $sourceUrl['domain'] = $regs['domain'];
            
            return $sourceUrl['domain'];
            
    }
    function _getRemoteFilesize($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //not necessary unless the file redirects (like the PHP example we're using here)
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data === false) {
          return -1;
        }
        
        $contentLength = -1;
        $status = 'unknown';
        if (preg_match('/^HTTP\/1\.[01] (\d\d\d)/', $data, $matches)) {
          $status = (int)$matches[1];
        }
        if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
          return (int)$matches[1];
        }
    }
    function _parseImage($html){
        
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $dom->preserveWhiteSpace = false;
        
        $imgs = $dom->getElementsByTagName("img");
        $links = array();
        
        $maxFilesize = 1080; // It's got to be bigger than 1080 bytes - this hides most of the tracking/share icons/etc 
        $maxThumbnailUrl = '';
        for($i = 0; $i < $imgs->length; $i++) {
            $url = $imgs->item($i)->getAttribute("src");
            $remoteFilesize = $this->_getRemoteFilesize($url);
            if($remoteFilesize > $maxFilesize){
                $maxFilesize = $remoteFilesize;
                $maxThumbnailUrl = $url;
            }
        }
        if($maxThumbnailUrl == ''){

            preg_match_all('!http://[a-zA-Z0-9\-\.\/]+\.(?:jpe?g|png|gif)!Ui', $html, $matches, PREG_PATTERN_ORDER);
            
            foreach($matches as $urlMatches){
                if(count($urlMatches) > 0){
                    foreach($urlMatches as $url){
                        if(filter_var($url, FILTER_VALIDATE_URL) !== false){
                            $remoteFilesize = $this->_getRemoteFilesize($url);
                            if($remoteFilesize > $maxFilesize){
                                $maxFilesize = $remoteFilesize;
                                $maxThumbnailUrl = $url;
                            }
                        }
                    }
                }
            }
        }
        
        return $maxThumbnailUrl;
        
    }
    
    function _parseByDomain($displayItem){
        
        return $displayItem;
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
        
        if(filter_var($displayItem['targetURL'], FILTER_VALIDATE_URL) == false){
            
            preg_match_all('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', $feedItem['guid']['@'], $matches, PREG_PATTERN_ORDER);
            if(is_array($matches) && is_array($matches[0])){
                $displayItem['targetURL'] = $matches[0][0] . $displayItem['targetURL'];
            }else{
                return false;
            }
        }

        $displayItem['date'] = date('Y-m-d H:i:s', strtotime($feedItem['pubDate']));
        $displayItem['author'] = isset($feedItem['author']) ? $feedItem['author'] : '';
        $displayItem['description'] = isset($feedItem['description']) ? html_entity_decode($feedItem['description']) : '';
        
        $displayItem['sourceDomain'] = $this->_readDomain($displayItem['targetURL']);
        $displayItem['thumbnailURL'] = $this->_parseImage($this->_multidimentionalImplode($feedItem));    
        
        
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
            if($parsedItem){
                $parsedItem['sourceFeed'] = $this->primaryFeedUrl;
                $saveData = array('Entries' => $parsedItem);
                $this->Entries->create();
                $this->Entries->save($saveData);
            }
            
        }
        
    }
}
?>