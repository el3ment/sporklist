<?php
class EntriesController extends Controller {
    var $uses = array('Entries');

    function view($id){
        $entry = $this->Entries->findById($id);        
        $this->redirect($entry['Entries']['targetURL']);
        
    }
    
    function viewAllByDomain($sourceDomain = 'cnn.com'){
        
        $entries = $this->Entries->findAllBySourcedomain($sourceDomain);
        
        $this->set('entries', $entries);
        $this->render('all');
    }
    
    function all($page = 0){
        $entries = $this->Entries->find('all', array('order' => 'Entries.date DESC', 'limit' => 100, 'page' => $page));
        $this->set('entries', $entries);
    }
}
?>