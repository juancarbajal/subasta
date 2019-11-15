$(function(){
    var classMenu=".dropdown-toggle";
    var Main = {
       start: function(a) {
           Main.menu(classMenu);
       },
       menu: function(a) {
            var A = $(a);
            A.dropdown()
       }
    };
    Main.start();
});