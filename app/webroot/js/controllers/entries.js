$.when(
    //window.marengo.utilities.require('models/api.js', 'model', 'api', true)
).done(function(){
    
    // Convience variables
    var _marengo = window.marengo;
    var _util = _marengo.utilities;
    var _parentController = {}; // Allows us to reference this controller easily

    _parentController = {
        events : {
            self : {
                onInit : function(e, data){ },
                onView : function(e, data){
                    data.selector.addClass('read');
                }
            }
        },
        assets : {}
    };
        
    _marengo.utilities.register('controller', 'entries', _parentController);
    
}); // end of controller / deferment