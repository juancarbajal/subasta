var Cookie = {
    
    /*defaults: {expiryDays:7},*/
    /**------------------------------------------------------------------------------
     * Set a cookie
     * @param {string} n name
     * @param {scalar} v value
     * @param {int} days
     *//*---------------------------------------------------------------------------*/   
    create: function(name,value,days) {
        if(days){
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }else{ var expires = ""; }
        document.cookie = name+"="+value+expires+"; path=/";
        return this;
    },
  
    /*-------------------------------------------------------------------------------
     * get a cookie
     * @param {string} n name
     *//*----------------------------------------------------------------------------*/
    read: function(name){
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++){
            var c = ca[i]; while(c.charAt(0)==' '){ c=c.substring(1,c.length); }
            if(c.indexOf(nameEQ)==0){ return c.substring(nameEQ.length,c.length); }
        } return null;
    },
        
    /*--------------------------------------------------------------------------------
     * Delete a cookie
     * @param {string} n name
     *//*-----------------------------------------------------------------------------*/
    del: function(name){
        return this.create(name, "", -1);
    }
};