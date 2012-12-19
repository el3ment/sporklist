<?php
class EntriesController extends Controller {
    var $uses = array('Entries');

    function _buildSortCriteria($page, $returnKey = null){
        
        $sortCriteria = array('order' => 'Entries.date DESC', 'limit' => 10, 'page' => $page);
        
        if($returnKey){ 
            return $sortCriteria[$returnKey];
        }else{
            return $sortCriteria;
        }
    }
    
    function _sort($entries){
        return $entries;
    }

    function view($id){
        $entry = $this->Entries->findById($id);        
        $this->redirect($entry['Entries']['targetURL']);
        
    }
    
    function viewAllByDomain($sourceDomain = 'cnn.com', $page = 0){
        
        $entries = $this->Entries->findAllBySourcedomain($sourceDomain, array(),  
            $this->_buildSortCriteria($page, 'order'), 
            $this->_buildSortCriteria($page, 'limit'),
            $this->_buildSortCriteria($page, 'page'));
        
        $this->set('entries', $this->_sort($entries));
        $this->set('pageIndex', $page);
    }
    
    function viewAllByFeedType($feedType = 'local', $page = 0){
        
        $entries = $this->Entries->findAllBySourcefeedtype($feedType, array(),   
            $this->_buildSortCriteria($page, 'order'), 
            $this->_buildSortCriteria($page, 'limit'),
            $this->_buildSortCriteria($page, 'page'));
        
        $this->set('entries', $this->_sort($entries));
        $this->set('pageIndex', $page);
        $this->set('feedType', $feedType);
    }
    
    function all($page = 0){
        
        $entries = $this->Entries->find('all', $this->_buildSortCriteria($page));
        
        $this->set('entries', $this->_sort($entries));
        $this->set('pageIndex', $page);
    }
}
?>