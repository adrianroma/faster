<style>
    
    .footer{
        float:left;
        height: 300px;
        width:100%;
        bottom: 0px;
        border-top:1px solid black;
        border-bottom:1px solid black;
        
    }
    
    .groupList{
        float:left;
        width:33.33%;
        height:300px;
        border:0px solid black;
    }
    
    .titleGroup{
        float:left;
        width:90%;
        margin-left:5%;
        margin-right:5%;
        margin-top:40px;
        height:30px;
        color:#3374B2;
        
    }
    
      @media (max-width: 12450px) { 
    
    .footer{
        border-top:1px solid black;
    }
    .groupList{
         background-color: #E5E5E5;
    }
    

    
    }
    
    
     @media (max-width: 800px) { 
     
    .footer{
        border-top:1px solid black;
    }
    .groupList{
         background-color: #E5E5E5;
    }
    

     
     }
    
</style>
<script id="footer" type="text/jsx" >
class Footer extends React.Component {
  constructor(props) {
    super(props);
 
   
     this.HOW = Object.values(store.lists.list.how);

    
  }
  
  
  Action(){
      console.log('service');   
  }
  render() {
    return (
      <div className='footer'>
           <div className='groupList'>
             <span className='titleGroup' >{ store.lists.list.id.how.prop.text.es }</span>
             <List list={this.HOW} action={this.Action} />            
           </div>
            
           <div className='groupList'>
         
           </div>
            
           <div className='groupList' >
        
           </div>
             
      </div>
    );
  }
}

</script>
