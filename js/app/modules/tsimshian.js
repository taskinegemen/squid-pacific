// Tsimshian Tribe Library for Co-working
// Triggers Framework events and Coworking events
'use strict'; 

window.lindneo = window.lindneo || {};
 
// tsimshian module
window.lindneo.tsimshian = (function(window, $, undefined){

  var socket;
  var myComponent='';
 
  var serverName = function (){
    return "http://ugur.dev.lindneo.com:1881";
  }; 


  var componentUpdated = function (component) {    

    window.lindneo.tsimshian.myComponent = component.id;
    //console.log('Sending');
    //console.log(window.lindneo.tsimshian.myComponent);
    this.socket.emit('updateComponent', component);

  };

  var componentCreated = function (component) {    

  	window.lindneo.tsimshian.myComponent = component.id;
  	//console.log('Sending');
  	//console.log(window.lindneo.tsimshian.myComponent);
  	this.socket.emit('newComponent', component);

  };


  var componentDestroyed = function(componentId){
 
    //console.log(componentId);

    //window.lindneo.tsimshian.myComponent = componentId;
    //console.log('Sending');

    this.socket.emit('destroyComponent', componentId);
  };

  var emitSelectedComponent = function ( component ) {

    window.lindneo.tsimshian.myComponent = component.id();

    this.socket.emit( 'emitSelectedComponent',   component.id()  );
  };

 
  var changePage = function (pageId){
    var user={
      pageid:pageId,
      name:window.lindneo.user.name,
      username:window.lindneo.user.username
    }
    
   
 
   
    window.lindneo.tsimshian.socket.emit('changePage',user);
  };

  var init = function (serverName){

    this.socket = io.connect("http://ugur.dev.lindneo.com:1881");
    this.socket.on('connection', function (data) {
      var user=window.lindneo.tsimshian.getCurrentUser();
       this.socket.emit('changePage',user);

    });
  
        
  
       this.socket.on('newComponent', function(component){
          console.log(component.id) ;
          console.log(window.lindneo.tsimshian.myComponent) ;
        if(window.lindneo.tsimshian.myComponent!=component.id ){
          console.log('Its new');
          window.lindneo.nisga.createComponent(component);
        } else {
          window.lindneo.tsimshian.myComponent='';
          console.log('I had sent it');
        }
       } );

 
       this.socket.on('destroyComponent', function(componentId){
          console.log(componentId) ;
          console.log(window.lindneo.tsimshian.myComponent) ;
        if(window.lindneo.tsimshian.myComponent!=componentId ){
          console.log('Its new');
          window.lindneo.nisga.destroyComponent(componentId);
        } else {
          console.log('I had sent it');
        }
       } );




       this.socket.on('updateComponent', function(component){
          console.log(componentId) ;
          console.log(window.lindneo.tsimshian.myComponent) ;
        if(window.lindneo.tsimshian.myComponent!=component.id ){
          console.log('Its new');
          window.lindneo.nisga.destroyComponent(component.id);
          window.lindneo.nisga.createComponent(component);
        } else {
          console.log('I had sent it');
        }
       } );

      this.socket.on('emitSelectedComponent', function( select_item ) {
       var componentId=select_item.componentId;
       var activeUser=select_item.user;



        console.log(select_item);
        console.log(window.lindneo.tsimshian.myComponent);

        if( window.lindneo.tsimshian.myComponent != componentId ) { 
          window.lindneo.nisga.setBgColorOfSelectedComponent( componentId,activeUser );
        }
      });


       this.socket.on('userListUpdate', function(userList){
          console.log(userList) ;
         
       } );
 

  }; 

  return {
    

    componentUpdated: componentUpdated,
    changePage: changePage,
    componentDestroyed: componentDestroyed,
    componentCreated: componentCreated,
    myComponent: myComponent,
    emitSelectedComponent: emitSelectedComponent,
    serverName: serverName,
    init: init
  };

})( window, jQuery );
