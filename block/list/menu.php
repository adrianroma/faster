<style>
    
    
 .list_main{
     float:left;
     width:90%;
     margin-left:5%;
     margin-right:5%;
     margin-top:30px;
     height:30px;
     list-style-type: none;
     }
 .item_main{
      
     color:#3374B2;
     float:right;
     width:10%;
     border:1px solid silver;
     margin-top:2px;
     margin-bottom:2px; 
     height:30px;
     text-align: left;
     }
     
.item_start{
     color:#3374B2;
     float:left;
     width:10%;
     border:1px solid silver;
     margin-top:2px;
     margin-bottom:2px; 
     height:30px;
     text-align: left; 
}    
     
 .option_main{  
     
 text-decoration:none;       
 outline: none !important;   
 font-size: 20px;
 width:100px;
 height:25px;
 border:1px solid red;
 float:left;
 text-align:center;
 
     }    
 
    @media (max-width: 12450px) {   
    
 .list_main{border:1px solid black;}
 .item{ color:#3374B2;}
 
     }
     
  @media (max-width: 800px) { 
       
  .list_main{border:1px solid black;}
 .item_main{ color:#3374B2;}
       
       } 
     
</style>
<script id="list" type="text/jsx" >

class Menu extends React.Component {
  constructor(props) {
    super(props);
    this.menu= Object.values(store.lists.list.main);
    this.state = { sample: 'sample' };
    this.service = this.service.bind(this)
     
  }
  
  service = (action,input)=>
  {
           console.log(action);
           console.log(input);
  }
  
 
  
  render() {
    return (
      <div className="list_main">
                    <span className="item_start" ><a href={url+'/'} className="option_main" >START</a></span> 
      
                    { 
                     this.menu.map(item=>{
                                return <span className="item_main" key={item.prop.name+'_key'+'_main'}   ><a href={url+'/'+item.prop.action} className="option_main" key={item.prop.name}  >{item.prop.text.es}</a></span>
                    })
                    }
         
      </div>
    );
  }
}

</script>